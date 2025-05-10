<?php
    /* Template Name: No title */

    if (!defined('ABSPATH')) { header('Location: /'); exit; }

    get_header();

    global $post;
?>

<section class="archive archive-dev <?php echo $post->post_name; ?>">
    <?php
        while (have_posts()) {
            the_post();
    ?>
        <div id="main" class="content">
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <?php the_content(); ?>
            </article>
        </div>
    <?php } ?>
</section>

<?php
    get_footer();
