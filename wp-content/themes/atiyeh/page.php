<?php
/**
 * Generic page: hero, content card, links to child pages, call to action.
 *
 * @package Atiyeh
 */

get_header();
while ( have_posts() ) :
	the_post();
	$atiyeh_parent = wp_get_post_parent_id( get_the_ID() );
	get_template_part( 'template-parts/page-hero', null, array( 'kicker' => $atiyeh_parent ? get_the_title( $atiyeh_parent ) : '' ) );
	?>
	<article <?php post_class( 'container content-card prose rv' ); ?>>
		<?php the_content(); ?>
		<?php
		$atiyeh_children = get_pages( array( 'parent' => get_the_ID(), 'sort_column' => 'menu_order' ) );
		if ( $atiyeh_children ) :
			?>
			<div class="link-grid">
				<?php foreach ( $atiyeh_children as $i => $atiyeh_child ) : ?>
					<a class="sweep link-tile <?php echo esc_attr( $i % 2 ? 'link-tile--alt' : '' ); ?>" href="<?php echo esc_url( get_permalink( $atiyeh_child ) ); ?>"><?php echo esc_html( $atiyeh_child->post_title ); ?> <span aria-hidden="true">←</span></a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</article>
	<?php
	get_template_part( 'template-parts/blocks/cta' );
endwhile;
get_footer();
