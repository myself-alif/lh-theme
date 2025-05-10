<?php
    /* Template Name: Just blocks */

	if (!defined('ABSPATH')) { header('Location: /'); exit; }

	global $post;
?>
<html  <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
    	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>

        <?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
        <?php wp_body_open(); ?>

		<div id="root">

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

        </div><!-- end: #root -->

        <?php wp_footer(); ?>
	</body>
</html>