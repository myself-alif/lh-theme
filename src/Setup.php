<?php

namespace LH_THEME;

class Setup
{
    /**
	 * Setup action & filter hooks
	 *
	 * @return Setup
	 */
	public function __construct() {
        
        $this->register_install_uninstall_hooks();

        new ThemeSettings();
        
        new Admin_Interfaces();
        new Ajax();

        new Filters\Menu();
        new Filters\Post();

        // new Plugins_Integrations\Advanced_Custom_Fields_Integration();
        // new Plugins_Integrations\Gravity_Forms_Integration();
    }

    /**
     * Register Install / uninstall hooks
     *
     * @return Void
     */
    private function register_install_uninstall_hooks() {
        register_activation_hook(LH_THEME_FILE, ['\LH_THEME\Install_Uninstall', 'activation_hook']);
        register_deactivation_hook(LH_THEME_FILE, ['\LH_THEME\Install_Uninstall', 'deactivation_hook']);
        register_uninstall_hook(LH_THEME_FILE, ['\LH_THEME\Install_Uninstall', 'uninstall_hook']);
    }

}