<?php
if (!function_exists('lh_render_insight_posts')) {
    function lh_render_insight_posts($posts, $grid_columns, $grid_gap)
    {
        if (empty($posts)) return;

        printf(
            "<div class='selected_posts col-%d' role='list' style='gap:%dpx'>",
            esc_attr($grid_columns),
            esc_attr($grid_gap)
        );

        foreach ($posts as $post) {
            $categories = get_the_category($post->ID);
            $thumbnail_id = get_post_thumbnail_id($post->ID);
?>
<article class="selected_post" role="listitem">
    <figure class="post-details">
        <?php echo lh_wp_get_attachment_image_by_sizes($thumbnail_id, [
                        'mobile'      => ['width' => 40, 'height' => 40, 'crop' => true],
                        'mobile_2x'   => ['width' => 80, 'height' => 80, 'crop' => true],
                        'tablet'      => ['width' => 40, 'height' => 40, 'crop' => true],
                        'tablet_2x'   => ['width' => 80, 'height' => 80, 'crop' => true],
                        'desktop'     => ['width' => 40, 'height' => 40, 'crop' => true],
                        'desktop_2x'  => ['width' => 80, 'height' => 80, 'crop' => true],
                    ]); ?>

        <?php if (!empty($categories)) : ?>
        <ul class="post-categories" aria-label="Post categories">
            <?php foreach ($categories as $category) : ?>
            <li><?php echo esc_html($category->name); ?></li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>

        <h2 class="post-title">
            <?php echo esc_html($post->post_title); ?>
        </h2>
    </figure>

    <div class="post-link">
        <p><?php _e('Read this', 'lh_theme') ?> <span>&#11106;</span></p>
    </div>

    <div id="modalWrapper" class="modal-wrapper">
        <div class="modal-backdrop"></div>
        <div class="popup">
            <h4 class="popup-title">
                <?php echo esc_html($post->post_title); ?>
            </h4>
            <div class="popup-excerpt">
                <?php echo wp_trim_words($post->post_content, 100, '...'); ?>
            </div>
            <img class="close-modal" src="<?php echo esc_url(LH_THEME_URL . '/assets/images/icon.png'); ?>"
                alt="cancel icon" role="button">
        </div>
    </div>
</article>
<?php
        }
        echo '</div>';
    }
}
?>

<section id="<?php echo esc_attr($el_id); ?>" class="<?php echo esc_attr(implode(' ', $el_class)); ?>"
    aria-labelledby="<?php echo esc_attr($el_id); ?>-title">
    <?php lh_print_block_style(self::$slug); ?>

    <?php if ($title) : ?>
    <div class="section-title">
        <?php if ($title_icon) : ?>
        <span class="dashicons <?php echo esc_attr($title_icon); ?>"></span>
        <?php endif; ?>
        <?php
            $title_args = ['id' => $el_id . '-title'];
            lh_print_simple_acf_element($title, 'h1', 'lh-h1 lh-latest-insights-block', $title_args);
            ?>
    </div>
    <?php endif; ?>

    <?php
    $post_per_page = 3;
    $categories = get_the_category();
    $category_id = !empty($categories) ? $categories[0]->term_id : null;

    $common_args = [
        'taxonomy_name'   => 'category',
        'page_number'     => max(1, get_query_var('paged')),
        'featured_ids'    => [],
        'exclude_ids'     => [],
        'posts_per_page'  => $post_per_page,
        'template_name'   => 'latest-insights',
        'block_name'      => 'latest-insights',
        'return_fields'   => false,
        'status'          => ['publish'],
        'custom_meta'     => false,
    ];

    if ($select_content_manually) {
        $listing_args = array_merge($common_args, [
            'post_type'       => ['post', 'news'],
            'category_id'     => 0,
            'include_ids'     => $manual_select,
            'show_pagination' => false,
            'orderby'         => ['post__in' => 'ASC'],
        ]);
    } elseif ($recent_posts) {
        $listing_args = array_merge($common_args, [
            'post_type'       => ['post', 'news'],
            'category_id'     => 0,
            'include_ids'     => 0,
            'show_pagination' => false,
            'orderby'         => ['date' => 'DESC'],
        ]);
    } else {
        $listing_args = array_merge($common_args, [
            'post_type'       => $post_type,
            'category_id'     => $category,
            'include_ids'     => [],
            'show_pagination' => true,
            'orderby'         => ['date' => 'DESC'],
        ]);
    }

    $posts = lh_get_post_listing(
        $listing_args['post_type'],
        $listing_args['taxonomy_name'],
        $listing_args['category_id'],
        $listing_args['page_number'],
        $listing_args['featured_ids'],
        $listing_args['include_ids'],
        $listing_args['exclude_ids'],
        $listing_args['posts_per_page'],
        $listing_args['template_name'],
        $listing_args['block_name'],
        $listing_args['show_pagination'],
        $listing_args['return_fields'],
        $listing_args['orderby'],
        $listing_args['status'],
        $listing_args['custom_meta']
    );

    if (($select_content_manually && $manual_select) || $recent_posts || ($post_type && $category)) {
        if (!empty($posts['posts'])) {
            lh_render_insight_posts($posts['posts'], $grid_columns, $grid_gap);
        }
    } else { ?>
    <p class="instruction"><?php echo _e("Please select any query to display posts", 'lh_theme') ?></p>
    <?php
    }



    if (!$select_content_manually && !$recent_posts && !is_singular(['post', 'news']) && $post_type && $category) {
        $base_url = get_pagenum_link(1);
        $total_posts = $posts['all_posts_count'];
        $max_page = ceil($total_posts / $post_per_page);
        $current_page = max(1, get_query_var('paged'));

        if ($total_posts > $post_per_page) {
            lh_print_pagination(
                $base_url,
                $max_page,
                $current_page,
                5,
                1,
                4,
                6,
                false,
                []
            );
        }
    }
    ?>
</section>