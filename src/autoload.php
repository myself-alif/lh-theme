<?php

spl_autoload_register('lh_theme_autoload');

/**
 * Load theme classes
 *
 * @param String $class_name
 * 
 * @return Void
 */
function lh_theme_autoload($class_name) {
    if (strpos($class_name, 'LH_THEME') !== false) {
        $class_name = str_replace('LH_THEME', '', $class_name);
        $classes_dir = realpath( plugin_dir_path( __FILE__ ) );
        $class_file = str_replace('\\', DIRECTORY_SEPARATOR, $class_name) . '.php';

        if (file_exists($classes_dir . $class_file)) {
            require_once($classes_dir . $class_file);
        }
    }
}