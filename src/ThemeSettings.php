<?php

namespace LH_THEME;

class ThemeSettings {
    /**
	 * Setup action & filter hooks
	 *
	 * @return ThemeSettings
	 */
	public function __construct() {
        add_action('after_setup_theme',     [$this,                         'setup_theme']);
        add_action('init',                  [$this,                         'init']);
        add_action('wp_default_scripts',    [$this,                         'wp_default_scripts']);
        add_action('widgets_init',          [$this,                         'widgets_init']);
        add_action('wp_print_styles',       [$this,                         'in_head_style']);
        add_action('wp_print_scripts',      ['\LH_THEME\ThemeSettings',  'wp_json']);

        add_filter('excerpt_length',        [$this, 'excerpt_length'],  999);
        add_filter('excerpt_more',          [$this, 'excerpt_more'],    999);
    }

    /**
     * Setup theme feature 
     *
     * @return void
     */
    public function setup_theme() {
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('menus');

        //add_post_type_support('page', 'excerpt');

        $this->register_menus();
    }

    /**
     * Load FE assets onload
     *
     * @return void
     */
    public function init() {
        $header_logo        = '';
        $header_symbol_logo = '';
        $footer_logo        = '';

        if (function_exists('get_field')) {
            $header_logo        = get_field('main_logo', 'option');
            $header_symbol_logo = get_field('main_logo_symbol', 'option');
            $footer_logo        = get_field('secondary_logo', 'option');
        }

        if (!defined('LH_HEADER_LOGO_URL')) {
            if (!empty($header_logo)) {
                define('LH_HEADER_LOGO_URL', $header_logo);
            } else {
                define('LH_HEADER_LOGO_URL', LH_THEME_ASSETS_URL . '/img/alogo.svg');
            }
        }

        if (!defined('LH_HEADER_LOGO_SYMBOL_URL')) {
            if (!empty($header_symbol_logo)) {
                define('LH_HEADER_LOGO_SYMBOL_URL', $header_symbol_logo);
            } else {
                define('LH_HEADER_LOGO_SYMBOL_URL', LH_THEME_ASSETS_URL . '/img/alogo-no-copy.svg');
            }
        }

        if (!defined('LH_FOOTER_LOGO_URL')) {
            if (!empty($footer_logo)) {
                define('LH_FOOTER_LOGO_URL', $footer_logo);
            } else {
                define('LH_FOOTER_LOGO_URL', LH_THEME_ASSETS_URL . '/img/alogo-white.svg');
            }
        }
        
        add_action('wp_enqueue_scripts',    [$this, 'enqueue_scripts'],         0);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);
        add_action('style_loader_tag',      [$this, 'style_loader_tag'],        10, 4);
        add_action('script_loader_tag',     [$this, 'script_loader_tag'],       10, 3);
        add_action('wp_enqueue_scripts',    [$this, 'dequeue_global_styles'],   999);

        /* Register image sizes */
        // lh_add_image_size('author_thumbnail_image',    74, 74, true);
        // lh_add_image_size('post_thumbnail_image',      568, 789, true);
    }

    /**
     * Register sidebars
     *
     * @return Void
     */
    public function widgets_init() {
        register_sidebar(
            [
                'id'            => 'footer_sidebar',
                'name'          => __('Footer sidebar', LH_THEME_SLUG),
                'description'   => __('Footer sidebar widgets.', LH_THEME_SLUG),
                'before_widget' => '',
                'after_widget'  => '',
                'before_title'  => '',
                'after_title'   => ''
            ]
        );
    }

    /**
     * Register and load scripts and styles
     *
     * @return void
     */
    public function enqueue_scripts() {
        wp_register_style(
            'lh-fontawesome-css',
            'https://use.fontawesome.com/releases/v5.0.6/css/all.css',
            [],
            false,
            'screen');

        wp_register_style('lh-theme-css',
            get_stylesheet_directory_uri() . '/assets/dist/css/styles.min.css',
            [],
            LH_THEME_VERSION,
            'screen');

        wp_register_script('lh-theme-js',
            get_stylesheet_directory_uri() . '/assets/dist/js/scripts.min.js',
            [],
            LH_THEME_VERSION, false);

        wp_register_style('lh-theme-extra-css',
            get_stylesheet_directory_uri() . '/style.css',
            ['LH-theme-css'],
            LH_THEME_VERSION,
            'screen');

        wp_register_script('selectize-js',
            get_stylesheet_directory_uri() . '/assets/dist/plugins/selectize/selectize.min.js',
            [],
            LH_THEME_VERSION, false);

        wp_register_style('selectize-css',
            get_stylesheet_directory_uri() . '/assets/dist/plugins/selectize/selectize.min.css',
            [],
            LH_THEME_VERSION,
            'screen');

        wp_register_script('fancybox-js',
            get_stylesheet_directory_uri() . '/assets/dist/plugins/fancybox/fancybox.min.js',
            [],
            LH_THEME_VERSION, false);

        wp_register_style('fancybox-css',
            get_stylesheet_directory_uri() . '/assets/dist/plugins/fancybox/fancybox.min.css',
            [],
            LH_THEME_VERSION,
            'screen');

        wp_register_script('jquery-blockui-js',
            get_stylesheet_directory_uri() . '/assets/dist/plugins/jquery.blockui/jquery.blockui.js',
            [],
            '2.70.0', false);

        wp_register_style('slick-css',
            get_stylesheet_directory_uri() . '/assets/dist/plugins/slick/slick.min.css',
            ['lh-theme-css'],
            '1.8.0',
            'screen');

        wp_register_script('slick-js',
            get_stylesheet_directory_uri() . '/assets/dist/plugins/slick/slick.min.js',
            ['lh-theme-js'],
            '1.8.0', false);

        wp_register_style('splide-css',
            get_stylesheet_directory_uri() . '/assets/dist/plugins/splide/splide.min.css',
            ['lh-theme-css'],
            '4.1.2',
            'screen');

        wp_register_script('splide-js',
            get_stylesheet_directory_uri() . '/assets/dist/plugins/splide/splide.min.js',
            ['lh-theme-js'],
            '4.1.2', false);

        wp_register_script('splide-grid-js',
            get_stylesheet_directory_uri() . '/assets/dist/plugins/splide-grid/splide-extension-grid.js',
            ['lh-theme-js'],
            '0.4.1', false);

        wp_register_script('splide-video-js',
            get_stylesheet_directory_uri() . '/assets/dist/plugins/splide-video/splide-extension-video.min.js',
            ['lh-theme-js'],
            '0.8.0', false);

        wp_register_script('lh-accordion-js',
            get_stylesheet_directory_uri() . '/assets/dist/js/plugins/lh_accordion.min.js',
            ['lh-theme-js'],
            LH_THEME_VERSION, false);

        wp_register_script('lh-ajax-js',
            get_stylesheet_directory_uri() . '/assets/dist/js/plugins/lh_ajax.min.js',
            ['lh-theme-js'],
            LH_THEME_VERSION, false);

        wp_register_script('jquery-mousewheel-js',
            get_stylesheet_directory_uri() . '/assets/dist/plugins/jquery.mousewheel/jquery.mousewheel.min.js',
            ['lh-theme-js'],
            '3.1.13', false);

        wp_register_script('vimeo-js',
            get_stylesheet_directory_uri() . '/assets/dist/plugins/vimeo/player.js',
            [],
            '2.20.1',
            false);

        wp_register_script('lh-mapbox-js',
            'https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.js',
            ['jquery'],
            '2.14.1', false);

        wp_register_style('lh-mapbox-css',
            'https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.css',
            [],
            '2.14.1',
            'screen');

        wp_enqueue_style('lh-theme-css');
        wp_enqueue_script('lh-theme-js');
        wp_enqueue_script('jquery-blockui-js');

        if (!is_admin() && !lh_is_login_page()) {
            $wp_jquery_js = '';

            ob_start();
            include (ABSPATH . 'wp-includes/js/jquery/jquery.min.js');
            $wp_jquery_js = ob_get_contents();
            ob_end_clean();

            wp_register_script('jquery', '');
            wp_add_inline_script('jquery', $wp_jquery_js);
            wp_enqueue_script('jquery');
        } else if (is_admin()) {
            wp_register_script('jquery', '');
            wp_add_inline_script('jquery', '');
            wp_enqueue_script('jquery');
        }

        /**
         * Uncomment the following line if we need to allow the client to
         * be able to easily add custom CSS by editing the ./style.css file
         * from the theme editor.
         */
        // wp_enqueue_style('lh-theme-extra-css');
    }

    /**
     * Register and load admin scripts and styles
     *
     * @return void
     */
    public function admin_enqueue_scripts($hook) {
        wp_register_script('lh-admin-theme-js',
            get_stylesheet_directory_uri() . '/assets/dist/js/lh-admin.min.js',
            [],
            LH_THEME_VERSION, false);

        // In admin Menus page set the menu depth restrictions
        if ($hook == 'nav-menus.php') {
            wp_localize_script(
                'lh-admin-theme-js',
                'lh_menu_depths',
                [
                    'top-nav'           => 1,
                    'top-nav-buttons'   => 0,
                    'footer-menu'       => 0,
                    'social-menu'       => 0
                ]
            );
        }

        wp_register_style('lh-google-font-css',
            'https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap',
            [],
            LH_THEME_VERSION,
            'screen');

        wp_enqueue_style('lh-google-font-css');
        wp_enqueue_script('lh-admin-theme-js');
    }

    /**
     * Filters the HTML link tag of an enqueued style.
     *
     * @param String $html
     * @param String $handle
     * @param String $href
     * @param String $media
     * 
     * @return String
     */
    public function style_loader_tag($html, $handle, $href, $media) {
        $defered_css_handles = [
            'lh-theme-css',
            'lh-theme-extra-css',
            'selectize-css',
            'fancybox-css',
            'slick-css',
            'splide-css',
            'algolia-autocomplete',
            'js_composer_front',
            'gravity_forms_theme_foundation',
            'gravity_forms_theme_reset',
            'gravity_forms_theme_framework',
            'gravity_forms_orbital_theme',
            'gforms_reset_css',
            'gforms_formsmain_css',
            'gforms_browsers_css',
        ];

        if (in_array($handle, $defered_css_handles)) {
            $html = '<link rel="preload" href="' . $href . '" as="style" id="' . $handle . '" media="' . $media . '" onload="this.onload=null;this.rel=\'stylesheet\'">'
                        . '<noscript>' . $html . '</noscript>';
        }

        return $html;
    }

    /**
     * Filters the HTML script tag of an enqueued script.
     *
     * @param String $tag
     * @param String $handle
     * @param String $src
     * 
     * @return String
     */
    public function script_loader_tag($tag, $handle, $src) {
        $remove_js_handles = [
            'jquery-core'
        ];

        $defered_js_handles = [
            'lh-theme-js',
            'selectize-js',
            'fancybox-js',
            'jquery-blockui-js',
            'slick-js',
            'splide-js',
            'jquery-mousewheel-js',
            'vimeo-js'
        ];

        if (in_array($handle, $remove_js_handles) && !is_admin() && !lh_is_login_page()) {
            return '';
        }

        if (in_array($handle, $defered_js_handles)) {
            return str_replace(' src', ' async defer src', $tag);
        }

        if (str_starts_with($handle, 'lh-acf-block-')) {
            return str_replace(' src', ' async defer src', $tag);
        }

        return $tag;
    }

    /**
     * Dequeue the gloabl styles added by WP
     *
     * @return void
     */
    public function dequeue_global_styles() {
        wp_dequeue_style('global-styles');
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('classic-theme-styles');
    }

    /**
	 * Remove jQuery Migrate script from the jQuery bundle only in front end.
	 *
	 * @since 1.0
	 *
	 * @param WP_Scripts $scripts WP_Scripts object.
	 */
    public function wp_default_scripts($scripts) {
        if (!is_admin() && isset( $scripts->registered['jquery'])) {
			$script = $scripts->registered['jquery'];
			
			if ( ! empty( $script->deps ) ) { // Check whether the script has any dependencies
				$script->deps = array_diff($script->deps, ['jquery-migrate']);
			}
		}
    }

    /**
     * Register theme menus
     *
     * @return Void
     */
    public function register_menus() {
        register_nav_menu('top-nav',            __('Navigation menu', LH_THEME_SLUG));
        register_nav_menu('top-nav-buttons',    __('Top buttons menu', LH_THEME_SLUG));
        register_nav_menu('footer-menu',        __('Footer menu', LH_THEME_SLUG));
        register_nav_menu('social-menu',        __('Social menu', LH_THEME_SLUG));
    }

    /**
     * Adds the style that should be in a <style> tag in the <head> tag
     *
     * @return Void
     */
    public function in_head_style() {
        ?>
        <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin="anonymous" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="anonymous" />
        <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet" />

        <style type="text/css" id="lh-theme-in-head-css">
            <?php include LH_THEME_ASSETS_PATH . '/css/in_head.min.css' ?>
        </style>
        <?php
    }

    /**
     * Setup global js object for wp features
     *
     * @return Void
     */
    static function wp_json() {
        global $post, $lh_wp_json;

        if (!$lh_wp_json) {
            $lh_wp_json = [];
        }

        $post_id = isset($post) && isset($post->ID) ? $post->ID : null;

        $data = [
            'wp' => array_merge([
                'ajax_url'          => admin_url('admin-ajax.php'),
                'post_id'           => $post_id,
                'is_user_logged_in' => is_user_logged_in()
            ], $lh_wp_json)
        ];

        ?>
        <script type="text/javascript">
            var waktools = <?php echo json_encode( $data ); ?>;
            waktools.functions = {};
            waktools.plugins = {};
            waktools.functions.lh_register_class = function(the_class){
                window.waktools.plugins[the_class.name] = the_class;
            };
        </script>
        <?php
    }

    /**
     * Filters the maximum number of words in a post excerpt.
     *
     * @param Integer $length
     * 
     * @return Integer
     */
    function excerpt_length($length) {
        return 20;
    }

    /**
     * Filters the string in the “more” link displayed after a trimmed excerpt.
     * 
     * This filter is called by the wp_trim_excerpt() function. By default,
     * the filter is set to echo ‘[…]’ as the excerpt more string at the end of the excerpt.
     *
     * @param String $more
     * 
     * @return String
     */
    function excerpt_more($more) {
        return '&hellip;';
    }
}