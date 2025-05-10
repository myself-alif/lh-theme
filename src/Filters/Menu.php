<?php

namespace LH_THEME\Filters;

class Menu 
{
    /**
	 * Setup action & filter hooks
	 *
	 * @return Menu
	 */
	public function __construct() {
        add_filter('nav_menu_css_class',        [$this, 'nav_menu_css_class'], 1, 3);
        add_filter('nav_menu_link_attributes',  [$this, 'nav_menu_link_attributes'], 1, 3);
    }

    /**
     * Add classes set on add_li_class on each <li> element
     * 
     * Eg.: Notice the new 'add_li_class' argument
     * $args = [
     *     'container'     => '',
     *     'theme_location'=> 'your-theme-loc',
     *     'add_li_class'  => 'your-class-name1 your-class-name-2'
     * ];
     * wp_nav_menu($args);
     * 
     * @param array     $classes
     * @param \WP_Post  $item
     * @param \stdClass $args
     * 
     * @return array
     */
    function nav_menu_css_class($classes, $item, $args) {
        if (isset($args->add_li_class)) {
            $classes[] = $args->add_li_class;
        }

        $social_media_icon = get_field('social_media_icon', $item->ID);

        if (!empty($social_media_icon) && $social_media_icon !== 'none') {
            $classes[] = 'lh-social-media-icon';
            $classes[] = 'lh-social-media-icon-' . $social_media_icon;
        }

        return $classes;
    }

    /**
     * Add classes set on add_link_class on each <a> element
     * 
     * Eg.: Notice the new 'add_link_class' argument
     * $args = [
     *     'container'      => '',
     *     'theme_location' => 'your-theme-loc',
     *     'add_link_class' => 'your-class-name1 your-class-name-2'
     * ];
     * wp_nav_menu($args);
     * 
     * @param array    $atts
     * @param \WP_Post $item
     * @param \stdClass $args
     * 
     * @return array
     */
    function nav_menu_link_attributes($atts, $item, $args) {
        if (isset($args->add_link_class) && !empty($args->add_link_class)) {
            $classes = [];

            if (!empty($atts['class'])) {
                $classes = explode(' ', $atts['class']);
            }

            $classes[] = $args->add_link_class;

            $atts['class'] = implode(' ', $classes);
        }

        return $atts;
    }
}