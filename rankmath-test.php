<?php

/**
 * Plugin Name: Rank Math Dashboard widget
 * Description: simple dashboard widget
 * Text Domain: rankmath-test
 * Version:           1.10.4
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Abiodun Paul ogunnaike
 */

if (!defined('ABSPATH')) {
    wp_die(__('Do not open this file directly.', 'rankmath-test'));
}


// require 'vendor/autoload.php';
//Basic setup

define('RANKMATHTEST__PLUGIN_FILE', __FILE__);
define('RANKMATHTEST__PLUGIN_DIR', plugin_dir_path(__FILE__));
define('RANKMATHTEST__PLUGIN_URL', plugins_url("", RANKMATHTEST__PLUGIN_FILE));


class Rank_Math_Test
{

    public $table_name;

    public function __construct()
    {
        global $wpdb;


        //Include main plugin file
        require_once RANKMATHTEST__PLUGIN_DIR . '/includes/class-rankmathdashboardwidget.php';




        $this->table_name = Rank_Math_Dashboard_Widget::get_instance()->table_name;
        register_activation_hook(__FILE__, [$this, 'install_create_table']);
        register_activation_hook(__FILE__, [$this, 'insert_dummy_data']);
    }



    public function install_create_table()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $this->table_name;


        $sql = "CREATE TABLE  $table_name (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `period` varchar(25) NOT NULL,
      `name` varchar(25) NOT NULL,
      `uv` int(9) NOT NULL,
      `pv` int(9) NOT NULL,
      `amt` int(9) NOT NULL,
      PRIMARY KEY (`id`)
    ) $charset_collate;";


        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }



    function insert_dummy_data()
    {
        global $wpdb;


        //7days dummy data
        $wpdb->insert(
            $this->table_name,
            array(
                'period'  => '7days',
                'name' => 'Page A',
                'uv' => 400,
                'pv' => 2400,
                'amt' => 2400
            )
        );

        $wpdb->insert(
            $this->table_name,
            array(
                'period'  => '7days',
                'name' => 'Page B',
                'uv' => 300,
                'pv' => 200,
                'amt' => 200
            )
        );

        $wpdb->insert(
            $this->table_name,
            array(
                'period'  => '7days',
                'name' => 'Page C',
                'uv' => 500,
                'pv' => 4000,
                'amt' => 4000
            )
        );

        $wpdb->insert(
            $this->table_name,
            array(
                'period'  => '7days',
                'name' => 'Page D',
                'uv' => 330,
                'pv' => 1500,
                'amt' => 1500
            )
        );

        $wpdb->insert(
            $this->table_name,
            array(
                'period'  => '7days',
                'name' => 'Page D',
                'uv' => 100,
                'pv' => 1500,
                'amt' => 1500
            )
        );

        $wpdb->insert(
            $this->table_name,
            array(
                'period'  => '7days',
                'name' => 'Page E',
                'uv' => 100,
                'pv' => 2400,
                'amt' => 2400
            )
        );

        //15days dummy data
        $wpdb->insert(
            $this->table_name,
            array(
                'period'  => '15days',
                'name' => 'Page A',
                'uv' => 200,
                'pv' => 2000,
                'amt' => 3400
            )
        );

        $wpdb->insert(
            $this->table_name,
            array(
                'period'  => '15days',
                'name' => 'Page B',
                'uv' => 600,
                'pv' => 3000,
                'amt' => 3000
            )
        );

        $wpdb->insert(
            $this->table_name,
            array(
                'period'  => '15days',
                'name' => 'Page C',
                'uv' => 400,
                'pv' => 4500,
                'amt' => 2000
            )
        );

        $wpdb->insert(
            $this->table_name,
            array(
                'period'  => '15days',
                'name' => 'Page D',
                'uv' => 630,
                'pv' => 1000,
                'amt' => 2500
            )
        );

        $wpdb->insert(
            $this->table_name,
            array(
                'period'  => '15days',
                'name' => 'Page E',
                'uv' => 200,
                'pv' => 1400,
                'amt' => 5400
            )
        );

        //1month dummy data

        $wpdb->insert(
            $this->table_name,
            array(
                'period'  => '1month',
                'name' => 'Page A',
                'uv' => 500,
                'pv' => 5000,
                'amt' => 1400
            )
        );

        $wpdb->insert(
            $this->table_name,
            array(
                'period'  => '1month',
                'name' => 'Page B',
                'uv' => 400,
                'pv' => 4000,
                'amt' => 3000
            )
        );

        $wpdb->insert(
            $this->table_name,
            array(
                'period'  => '1month',
                'name' => 'Page C',
                'uv' => 300,
                'pv' => 3500,
                'amt' => 2000
            )
        );

        $wpdb->insert(
            $this->table_name,
            array(
                'period'  => '1month',
                'name' => 'Page D',
                'uv' => 230,
                'pv' => 2000,
                'amt' => 5000
            )
        );

        $wpdb->insert(
            $this->table_name,
            array(
                'period'  => '1month',
                'name' => 'Page E',
                'uv' => 100,
                'pv' => 1400,
                'amt' => 1400
            )
        );
    }
}


//Instantiate Plugin
$rank_math_test = new Rank_Math_Test();
