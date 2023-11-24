<?php

/**
 * Plugin Name: Rank Math Dashboard widget
 * Description: simple dashboard widget
 * Text Domain: rankmath-test
 * Version:           1.10.3
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Abiodun Paul ogunnaike
 */

if (!defined('ABSPATH')) {
    exit;
}

//Basic setup

define('RANKMATHTEST__PLUGIN_FILE', __FILE__);
define('RANKMATHTEST__PLUGIN_DIR', plugin_dir_path(__FILE__));
define('RANKMATHTEST__PLUGIN_URL', plugins_url("", RANKMATHTEST__PLUGIN_FILE));


/**
 * Add new dashboard widget
 */

add_action('wp_dashboard_setup', 'rankmathtest_dashboard_init');
add_action('admin_enqueue_scripts', 'rankmathtest_enqueue_scripts');

function rankmathtest_dashboard_init()
{
    wp_add_dashboard_widget('dashboard_widget', 'Rank Math Widget', 'rankmath_widget_init');
}

/**
 * Widget function
 */
function rankmath_widget_init()
{
    require_once RANKMATHTEST__PLUGIN_DIR . 'templates/main.php';
}

/**
 * Enqueue scripts and styles
 */

function rankmathtest_enqueue_scripts()
{
    wp_enqueue_style('rankmathtest_style', RANKMATHTEST__PLUGIN_URL . '/build/index.css');
    wp_enqueue_script('rankmathtest_script', RANKMATHTEST__PLUGIN_URL . '/build/index.js', array('wp-element'), '1.0.0', true);
}

/**
 * register rest api route
 */
add_action('rest_api_init', function () {

    register_rest_route('react/v1', '/data/(?P<duration>[a-zA-Z0-9-]+)', array(
        'methods' => 'GET',
        'callback' => 'rankmathtest_get_data',
        'permission_callback' => false
    ));
});


function fetch_db($period)
{
    global $wpdb;

    $formatted_output = array();

    $table_name = $wpdb->prefix . 'graph';

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

    $response = fetch_db($duration);

    return rest_ensure_response($response);
}
