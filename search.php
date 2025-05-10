<?php
    if (!defined('ABSPATH')) { header('Location: /'); exit; }

    get_header();
?>

<section class="search-results">
    <header class="entry-header-main">
        <div class="header-icon">
            <h1 class="entry-title"><?php _e('Search results for: ', LH_THEME_SLUG); ?>"<?php echo get_search_query(); ?>"</h1>
        </div>
    </header>

    <?php if (have_posts()) { ?>
        <div class="search-list list">
            <div class="container">
                <?php
                    while (have_posts()) {
                        the_post();
                        global $post;

                        get_template_part( 'template-parts/content', 'search' );
                    }

                    the_posts_pagination( array(
                        'prev_text'          => __( 'Previous page', LH_THEME_SLUG ),
                        'next_text'          => __( 'Next page', LH_THEME_SLUG ),
                        'before_page_number' => '',
                        'screen_reader_text' => ' ',
                    ) );
                ?>
            </div>
        </div>
    <?php } else { ?>
        <div class="no-results">
            <strong><?php _e('There are currently no results for selected criteria.', LH_THEME_SLUG); ?></strong>
        </div>
    <?php } ?>
</section>

<?php
    get_footer();
