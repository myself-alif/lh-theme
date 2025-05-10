<?php

namespace LH_THEME\Plugins_Integrations;

class Advanced_Custom_Fields_Integration
{
    /**
	 * Setup action & filter hooks
	 * 
	 * @return Advanced_Custom_Fields_Integration
	 */
	public function __construct() {
        if ($this->is_plugin_active()) {
            // add_action('acf/init',  [$this, 'acf_options_page']);
            // add_action('acf/init',  [$this, 'acf_add_local_field_group']);
        } else {
            add_action('admin_notices', [$this, 'show_acf_version_notice']);
            return;
        }
    }

    /**
     * Check if ACF plugin is active
     * 
     * @return boolean
     */
    private function is_plugin_active() {
        return class_exists('ACF');
    }

    /**
     * Register options pages
     * 
     * @return void
     */
    public function acf_options_page() {
        
    }

    /**
     * Register custom field groups
     * 
     * @return void
     */
    public function acf_add_local_field_group() {
        
    }

    /**
     * Show admin notice when ACF is not installed/activated
     * 
     * @return void
     */
    public function show_acf_version_notice() {
        echo '
        <div class="updated">
            <p>
            ' . sprintf(
                    __('<strong>%s</strong> requires <strong><a href="https://wordpress.org/plugins/advanced-custom-fields/" target="_blank">ACF</a></strong> plugin to be installed and activated on your site.',
                    LH_THEME_SLUG),
                    __CLASS__
                ) . '
            </p>
        </div>';
    }
}