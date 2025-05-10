<?php
    if (!defined('ABSPATH')) { header('Location: /'); exit; }

    get_header();

    global $post;
?>

<section class="404-page">
    <header class="entry-header-main">
        <div class="header-icon">
            <h1 class="entry-title">404 - Page not found</h1>
        </div>
    </header>

    <div id="main" class="page content">
        <article <?php post_class(); ?>>
            <strong>It looks like nothing was found at this location. Maybe try a search?</strong>

            <br /><br />

            <?php get_search_form(); ?>
        </article>
    </div>
</section>

<?php get_footer(); ?>