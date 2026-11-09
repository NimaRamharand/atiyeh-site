<?php
/**
 * Single project: image, facts (client, city, date, service) and description.
 *
 * @package Atiyeh
 */

get_header();
while ( have_posts() ) :
	the_post();
	$atiyeh_terms   = get_the_terms( get_the_ID(), 'service' );
	$atiyeh_service = is_array( $atiyeh_terms ) ? $atiyeh_terms[0]->name : '';
	get_template_part( 'template-parts/page-hero', null, array( 'kicker' => $atiyeh_service ? $atiyeh_service : 'پروژه' ) );
	$atiyeh_facts = array(
		'کارفرما'   => get_post_meta( get_the_ID(), '_atiyeh_client', true ),
		'شهر'       => get_post_meta( get_the_ID(), '_atiyeh_city', true ),
		'تاریخ اجرا' => get_post_meta( get_the_ID(), '_atiyeh_date', true ),
		'نوع خدمت'  => $atiyeh_service,
	);
	?>
	<article <?php post_class( 'container content-card prose project-single' ); ?>>
		<?php if ( has_post_thumbnail() ) : ?>
			<figure class="content-card__media"><?php the_post_thumbnail( 'large', array( 'class' => 'pimg' ) ); ?></figure>
		<?php endif; ?>
		<dl class="facts">
			<?php foreach ( array_filter( $atiyeh_facts ) as $atiyeh_label => $atiyeh_value ) : ?>
				<div class="facts__item"><dt><?php echo esc_html( $atiyeh_label ); ?></dt><dd><?php echo esc_html( atiyeh_nb( (string) $atiyeh_value ) ); ?></dd></div>
			<?php endforeach; ?>
		</dl>
		<?php the_content(); ?>
		<p class="back-link"><a class="btn btn--outline" href="<?php echo esc_url( (string) get_post_type_archive_link( 'project' ) ); ?>">همه پروژه‌ها <span class="ar" aria-hidden="true">←</span></a></p>
	</article>
	<?php
endwhile;
get_footer();
