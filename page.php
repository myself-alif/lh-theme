<?php
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
                <?php if (!is_front_page()) { ?>
                    <?php print apply_filters('the_content', '<!-- wp:wak/title {"name":"wak/title","data":{"field_6409c8d4c183f":"' . get_the_title() . '","field_6409c918c1840":"0","field_6409c957c1841":""},"mode":"edit"} /-->'); ?>
                <?php } ?>
                <?php the_content(); ?>
            </article>
        </div>
    <?php } ?>
</section>

<?php
    get_footer();
