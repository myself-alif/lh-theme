<?php

namespace LH_THEME\Filters;

class Post 
{
    /**
     * Setup action & filter hooks
     *
     * @return Post
     */
    public function __construct() {
        add_filter('body_class', 		[$this, 'body_class'], 			999, 2);
        add_filter('admin_body_class',	[$this, 'admin_body_class'],	999, 1);
    }

    /**
     * Filters the list of CSS body class names for the current post or page.
     *
     * @since 2.8.0
     *
     * @param string[] $classes An array of body class names.
     * @param string[] $class   An array of additional class names added to the body.
     * 
     * @return string[]
     */
    public function body_class($classes, $class) {
        $classes[] = 'lh-fe';

        return $classes;
    }

    /**
	 * Filters the CSS classes for the body tag in the admin.
	 * 
	 * @since 2.3.0
	 * 
	 * @param string $classes Space-separated list of CSS classes.
	 * 
	 * @return string
	 */
	public function admin_body_class ($classes) {
		if (!empty($classes)) {
			$classes = explode(' ', $classes);
		} else {
			$classes = [];
		}

		$classes[] = 'lh-admin';

		return implode(' ', $classes);
	}
}