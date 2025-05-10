<?php

namespace LH_THEME;

class Admin_Interfaces
{
    /**
     * Admin pages registered by the plugin
     */
    const PAGES = [];

    /**
	 * Setup action & filter hooks
	 *
	 * @return Admin_Interfaces
	 */
	public function __construct() {
        // add_action('admin_menu',            [$this, 'admin_menu']);
        add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);
    }

    /**
     * Register admin menu pages
     * 
     * @return void
     */
    public function admin_menu() {
        // add_menu_page(
        //     __('LH API integration', 'textdomain'),
        //     __('LH API', 'textdomain'),
        //     'manage_options',
        //     'lh-api-integration',
        //     [$this, 'admin_page'],
        //     LH_API_ASSETS_URL . 'lh-brand/logo-dot.svg',
        //     81
        // );

        // add_submenu_page(
        //     'lh-api-integration',
        //     __('API Endpoints', 'textdomain'),
        //     __('API Endpoints', 'textdomain'),
        //     'manage_options',
        //     'lh-api-endpoints-groups',
        //     [$this, 'api_endpoints_groups']
        // );

        // add_submenu_page(
        //     NULL,   // Not displayed in the admin menu
        //     __('Create endpoints group', 'textdomain'),
        //     __('Create endpoints group', 'textdomain'),
        //     'manage_options',
        //     'lh-api-endpoints-group-add',
        //     [$this, 'api_endpoints_group_add']
        // );
    }

    /**
     * Register CSS and JS assets for admin pages
     * 
     * @return void
     */
    public function admin_enqueue_scripts() {
        wp_register_style('LH-theme-in-head-admin',
            get_stylesheet_directory_uri() . '/assets/dist/css/in_head.min.css',
            [],
            LH_THEME_VERSION);

        wp_register_style('LH-theme-css-admin',
            get_stylesheet_directory_uri() . '/assets/dist/css/styles.min.css',
            [],
            LH_THEME_VERSION);

        wp_enqueue_style('LH-theme-in-head-admin');
        wp_enqueue_style('LH-theme-css-admin');

        \LH_THEME\ThemeSettings::wp_json();

        if (isset($_GET['page']) && in_array($_GET['page'], self::PAGES)) {
            // wp_enqueue_style(
            //     'bootstrap-4.4.1-css',
            //     LH_BAS_ASSETS_URL . 'bootstrap-4.4.1-dist/css/bootstrap.min.css',
            //     [],
            //     '4.4.1');

            // wp_enqueue_script(
            //     'jquery-3.5.1-js',
            //     LH_BAS_ASSETS_URL . 'jquery-3.5.1/jquery-3.5.1.min.js',
            //     [],
            //     '3.5.1',
            //     true);
        }
    }
}
