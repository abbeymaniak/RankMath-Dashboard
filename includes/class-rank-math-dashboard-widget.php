<?php

/**
 * Rank Math Dashboard Widget
 *
 * @package rankmath-test
 */

/**
 * Rank_Math_Dashboard_Widget Class
 *
 * This class handles the creation and functionality of the Rank Math Dashboard Widget.
 *
 * @package rankmath-test
 */
class Rank_Math_Dashboard_Widget
{

	/** This is the table name for the dashboard data.
	 *
	 * @var string $table_name
	 */
	public $table_name;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		global $wpdb;

		$this->table_name = $wpdb->prefix . 'rm_dashboard_data';

		add_action('wp_dashboard_setup', array($this, 'rankmathtest_dashboard_init'));

		if (is_admin() && admin_url('/')) {
			add_action('admin_enqueue_scripts', array($this, 'rankmathtest_enqueue_scripts'));
		}
		add_action('rest_api_init', array($this, 'register_custom_rest_api_route'));
	}

	/** Instantiates the Class.
	 *
	 * @return self|null
	 */
	public static function get_instance(): self|null
	{
		static $instance = null;

		if (is_null($instance)) {
			$instance = new self();
		}

		return $instance;
	}

	/** This functions adds the dashboard widget.
	 *
	 * @return void
	 */
	public function rankmathtest_dashboard_init(): void
	{
		wp_add_dashboard_widget('dashboard_widget', 'Rank Math Widget', array($this, 'rankmath_widget_init'));
	}

	/** This function addes the template/main.php.
	 *
	 * @return void
	 */
	public function rankmath_widget_init(): void
	{
		require_once RANKMATHTEST__PLUGIN_DIR . 'templates/main.php';
	}

	/** This functions enqueue scripts.
	 *
	 * @return void
	 */
	public function rankmathtest_enqueue_scripts(): void
	{

		$style_file_path = RANKMATHTEST__PLUGIN_DIR . '/build/index.css';
		$style_version   = filemtime($style_file_path);

		wp_enqueue_script('wp-api');
		wp_enqueue_style('rankmathtest_style', RANKMATHTEST__PLUGIN_URL . '/build/index.css', array(), $style_version);
		wp_enqueue_script('rankmathtest_script', RANKMATHTEST__PLUGIN_URL . '/build/index.js', array('wp-element', 'wp-api-fetch', 'wp-i18n', 'wp-components'), '1.0.0', true);
	}

	/** This function creates the rest api endpoint for the plugin.
	 *
	 * @return void
	 */
	public function register_custom_rest_api_route(): void
	{

		register_rest_route(
			'react/v1',
			'/data/(?P<duration>[a-zA-Z0-9-]+)',
			array(
				'methods'             => 'GET',
				'callback'            => array($this, 'rankmathtest_get_data'),
				'permission_callback' => '__return_true',
			)
		);
	}


	/**
	 * Fetches data from the database based on the specified period.
	 *
	 * @param string $period The period for which data needs to be fetched.
	 * @return array An array of formatted output containing name, uv, pv, and amt.
	 */
	public function fetch_db($period): array
	{
		global $wpdb;

		$formatted_output = array();

		$table_name = $this->table_name;

		$sql_query = $wpdb->prepare(
			"SELECT * FROM $table_name WHERE period = %s",
			$period
		);

		$results = $wpdb->get_results($sql_query, ARRAY_A);

		if ($results) {
			foreach ($results as $row) {
				$formatted_output[] = array(
					'name' => $row['name'],
					'uv'   => $row['uv'],
					'pv'   => $row['pv'],
					'amt'  => $row['amt'],
				);
			}
		}

		return $formatted_output;
	}

	/** Returns database data in json format.
	 *
	 * @param string $request This is the Url Parameter.
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function rankmathtest_get_data($request)
	{
		$duration = esc_attr($request['duration']);

		$response = $this->fetch_db($duration);

		return rest_ensure_response($response);
	}
}

// Instantiate Plugin.

Rank_Math_Dashboard_Widget::get_instance();
