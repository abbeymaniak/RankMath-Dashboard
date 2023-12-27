<?php



class Rank_Math_Dashboard_Widget
{
    public $table_name;

    public function __construct()
    {
        global $wpdb;


        $this->table_name = $wpdb->prefix . 'rm_dashboard_data';


        add_action('wp_dashboard_setup', [$this, 'rankmathtest_dashboard_init']);

        if (is_admin() && admin_url('/')) {
            add_action('admin_enqueue_scripts', [$this, 'rankmathtest_enqueue_scripts']);
        }
        add_action('rest_api_init', [$this, 'register_custom_rest_api_route']);
    }


    public static function get_instance()
    {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }

    public function on_activation()
    {
        $this->create_table();
        $this->insert_dummy_data();
    }


    public function rankmathtest_dashboard_init()
    {
        wp_add_dashboard_widget('dashboard_widget', 'Rank Math Widget', [$this, 'rankmath_widget_init']);
    }

    public function rankmath_widget_init()
    {
        require_once RANKMATHTEST__PLUGIN_DIR . 'templates/main.php';
    }

    public function rankmathtest_enqueue_scripts()
    {
        wp_enqueue_script('wp-api');
        wp_enqueue_style('rankmathtest_style', RANKMATHTEST__PLUGIN_URL . '/build/index.css');
        wp_enqueue_script('rankmathtest_script', RANKMATHTEST__PLUGIN_URL . '/build/index.js', array('wp-element', 'wp-api-fetch', 'wp-i18n', 'wp-components'), '1.0.0', true);
    }

    public function register_custom_rest_api_route()
    {


        register_rest_route('react/v1', '/data/(?P<duration>[a-zA-Z0-9-]+)', array(
            'methods' => 'GET',
            'callback' => [$this, 'rankmathtest_get_data'],
            'permission_callback' => '__return_true'
        ));
    }


    function fetch_db($period)
    {
        global $wpdb;

        $formatted_output = array();

        $table_name = $this->table_name;

        $sql_query =  $wpdb->prepare("SELECT * FROM $table_name WHERE period = %s", $period);

        $results = $wpdb->get_results($sql_query, ARRAY_A);

        if ($results) {
            foreach ($results as $row) {
                $formatted_output[] = [
                    'name' => $row['name'],
                    'uv' => $row['uv'],
                    'pv' => $row['pv'],
                    'amt' => $row['amt']
                ];
            }
        }

        return $formatted_output;
    }

    function rankmathtest_get_data($request)
    {
        $duration = esc_attr($request['duration']);

        $response = $this->fetch_db($duration);

        return rest_ensure_response($response);
    }
}

//Instantiate Plugin

Rank_Math_Dashboard_Widget::get_instance();
