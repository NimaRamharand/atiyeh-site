<?php
/**
 * Single post / CPT entry.
 *
 * @package Atiyeh
 */

get_header();
while ( have_posts() ) :
	the_post();
	get_template_part( 'template-parts/page-hero', null, array( 'kicker' => atiyeh_post_date() ) );
	?>
	<article <?php post_class( 'container content-card prose' ); ?>>
		<?php if ( has_post_thumbnail() ) : ?>
			<figure class="content-card__media"><?php the_post_thumbnail( 'large', array( 'class' => 'pimg' ) ); ?></figure>
		<?php endif; ?>
		<?php the_content(); ?>
	</article>
	<?php
endwhile;
get_footer();
