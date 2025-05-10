<?php

define('LH_THEME_VERSION',     '1.1.0');
define('LH_THEME_SLUG',        'lh-theme');
define('LH_THEME_PATH',        get_template_directory());
define('LH_THEME_FILE',        __FILE__);
define('LH_THEME_URL',         get_template_directory_uri());
define('LH_THEME_ASSETS_PATH', get_template_directory() . '/assets/dist');
define('LH_THEME_ASSETS_URL',  get_template_directory_uri() . '/assets/dist');

require_once('src/alias_functions.php');
// require_once('vendor/autoload.php');
require_once('src/autoload.php');

new \LH_THEME\Setup();
