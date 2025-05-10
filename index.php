<?php
    if (!defined('ABSPATH')) { header('Location: /'); exit; }

    get_header();
?>

<section id="primary" class="content-area">
    <main id="main" class="site-main">
        <?php
            if (have_posts()) {
                // Load posts loop.
                while (have_posts()) {
                    the_post();
                    get_template_part('template-parts/content', get_post_format());
                }
            } else {
                // If no content, include the "No posts found" template.
                get_template_part('template-parts/content', 'none');
            }
        ?>
    </main><!-- .site-main -->
</section><!-- .content-area -->

<?php
    get_footer();
