<?php

namespace LH_THEME;

class Ajax
{
    /**
	 * Setup action & filter hooks
	 *
	 * @return Ajax
	 */
	public function __construct() {
        add_action('wp_ajax_lh_get_posts_listing',          [$this, 'get_posts_listing']);
        add_action('wp_ajax_nopriv_lh_get_posts_listing',   [$this, 'get_posts_listing']);
    }

    /**
     * AJAX endpoint to get posts listing
     *
     * @return void
     */
    public function get_posts_listing(){
        $postsObject                = $this::get_posts();
        $max_page                   = $postsObject['max_page'];
        $postsObject['pagination']  = $this::get_pagination($max_page);

        wp_send_json($postsObject);
    }

    /**
     * Get posts
     * 
     * The parameters for this function are taken from $_POST global variable
     * 
     * @return array
     */
    public function get_posts() {
        $post_type       = isset($_POST['post_type'])           ? esc_attr($_POST['post_type'])                 : "post";
        $taxonomy_name   = isset($_POST['taxonomy_name'])       ? esc_attr($_POST['taxonomy_name'])             : "category";
        $posts_category  = isset($_POST['posts_category'])      ? esc_attr($_POST['posts_category'])            : 0;
        $page_number     = isset($_POST['posts_page'])          ? (int)esc_attr($_POST['posts_page'])           : 1;
        $posts_per_page  = isset($_POST['posts_per_page'])      ? (int)esc_attr($_POST['posts_per_page'])       : 12;
        $featured_ids    = isset($_POST['featured_ids'])        ? array_map('esc_attr', $_POST['featured_ids']) : [];
        $include_ids     = isset($_POST['included_ids'])        ? array_map('esc_attr', $_POST['included_ids']) : [];
        $exclude_ids     = isset($_POST['excluded_ids'])        ? array_map('esc_attr', $_POST['excluded_ids']) : [];
        $template_name   = isset($_POST['template_name'])       ? esc_attr($_POST['template_name'])             : "post-listing";
        $block_name      = isset($_POST['block_name'])          ? esc_attr($_POST['block_name'])                : "explore-destinations";
        $show_pagination = isset($_POST['show_pagination'])     ? esc_attr($_POST['show_pagination'])           : false;
        $return_fields   = isset($_POST['return_fields'])       ? esc_attr($_POST['return_fields'])             : false;
        $order_by        = isset($_POST['order_by'])            ? esc_attr($_POST['order_by'])                  : ['date' => "DESC"];
        $status          = isset($_POST['status'])              ? esc_attr($_POST['status'])                    : ["publish"];
        $custom_meta     = isset($_POST['custom_meta'])         ? esc_attr($_POST['custom_meta'])               : false;

        // Multiple post types
        if (strpos($post_type, ',') !== false) {
            $post_type = explode(',', $post_type);
        }

        // We dont want any of these statuses to be selectable, 
        // this allows us to select private and published posts
        $status_sanitation = ["future", "draft", "pending", "trash", "auto-draft", "inherit", "new"];

        foreach ($status_sanitation as $status_key) {
            if (in_array($status_key, $status)) {
                $status = ['publish'];
            }
        }
        
        // Turning category slug into an id, we can pass id too from the FE too
        if ($posts_category !== 0 && !is_numeric($posts_category)) {
            $category_id = lh_get_cat_id_from_slug($posts_category, $taxonomy_name);
        } else {
            $category_id = (int)$posts_category;
        }

        if (!empty($custom_meta)) {
            $custom_meta = htmlspecialchars_decode($custom_meta, ENT_QUOTES);
            $custom_meta = stripcslashes($custom_meta);
            $custom_meta = json_decode($custom_meta, true);
        }

        $posts_data = lh_get_post_listing($post_type, $taxonomy_name, $category_id, $page_number, $featured_ids, $include_ids, $exclude_ids, $posts_per_page, $template_name, $block_name, $show_pagination, $return_fields, $order_by, $status, $custom_meta);

        //prepare results object
        $result = [
            'status'    => '',
            'posts'     => '',
            'args'      => ''
        ];

        //regex to match lowercase-words-single-dashes
        $lowercase_dash_regex = '/^[a-z]+(?:-[a-z]+)*$/';

        //check if there are posts and we have a template.
        if(
            !empty($template_name) && 
            !empty($block_name) && 
            preg_match($lowercase_dash_regex, $template_name) && 
            preg_match($lowercase_dash_regex, $block_name) && 
            !empty($posts_data['posts'])
        ) {
            ob_start();
            require(LH_THEME_PATH . '/blocks/' . $block_name . '/' . $template_name . '.php');
            $posts_html = ob_get_contents();
            ob_end_clean();
        } else {
            $result['status'] = "error";
        }

        $all_posts_count    = $posts_data['all_posts_count'];
        $max_page           = ($all_posts_count  > $posts_per_page) ? ceil($all_posts_count  / $posts_per_page) : 1;

        $result['status']       = 'success';
        $result['posts']        = $posts_html;
        $result['show_more']    = $posts_data['show_more'];
        $result['max_page']     = $max_page;
        $result['args']         = $posts_data['args'];

        return $result;
    }

    /**
     * Get pagination HTML for AJAX endpoint to get posts listing
     * 
     * Some parameters for this function are taken from $_POST global variable
     * 
     * @param integer $max_page
     * 
     * @return void
     */
    public function get_pagination($max_page) {
        $base_url                       = isset($_POST['base_url'])             ? esc_attr($_POST['base_url'])                  : "/";
        $page_num                       = isset($_POST['posts_page'])           ? (int)esc_attr($_POST['posts_page'])           : 1;
        $first_pages_count              = isset($_POST['first_pages_count'])    ? (int)esc_attr($_POST['first_pages_count'])    : 3; 
        $last_pages_count               = isset($_POST['last_pages_count'])     ? (int)esc_attr($_POST['last_pages_count'])     : 3;
        $around_current_page_count      = isset($_POST['around_current_page'])  ? (int)esc_attr($_POST['around_current_page'])  : 4;
        $display_all_count_less_than    = isset($_POST['display_all_count'])    ? (int)esc_attr($_POST['display_all_count'])    : 6;
        $ajaxed_pagination = true;

        ob_start();
        lh_print_pagination($base_url, $max_page, $page_num, $first_pages_count, $last_pages_count, $around_current_page_count, $display_all_count, $ajaxed_pagination);    
        $pagination_html = ob_get_contents();
        ob_end_clean();

        return $pagination_html;
    }
}
