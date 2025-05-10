<?php
	if (!defined('ABSPATH')) { header('Location: /'); exit; }

	get_header();

	global $post;
?>

<section class=" <?php echo $post->post_name; ?>">
	<?php while ( have_posts() ) { the_post(); ?>
		<div id="main" class="content">
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php $thumbnail = get_the_post_thumbnail(get_the_ID(), 'post-thumbnail', array('alt'=> get_the_title()) ); ?>
				<?php if( ! empty( $thumbnail ) ) { ?>
					<div class="post_thumbnail">
						<?php echo $thumbnail; ?>
					</div>
				<?php } ?>

				<?php print apply_filters('the_content', '<!-- wp:wak/title {"name":"wak/title","data":{"field_6409c8d4c183f":"' . get_the_title() . '","field_6409c918c1840":"0","field_6409c957c1841":""},"mode":"edit"} /-->'); ?>

				<?php the_content(); ?>
			</article>
		</div>
	<?php } ?>
</section>

<?php
	get_footer();
