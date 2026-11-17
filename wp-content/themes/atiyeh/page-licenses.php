<?php
/**
 * «مجوزها و رتبه‌بندی‌ها»: certificate cards (CSS illustrations) with placeholder titles until the scans arrive.
 *
 * @package Atiyeh
 */

get_header();
while ( have_posts() ) :
	the_post();
	get_template_part( 'template-parts/page-hero', null, array( 'kicker' => 'درباره ما', 'desc' => 'گواهی صلاحیت خدمات مشاوره آماری از مرکز آمار ایران، رتبه‌بندی شرکت‌های انفورماتیکی و مجوزهای اداره کار.', 'img' => 'trust-plaques.webp' ) );
	$atiyeh_intro = atiyeh_page_intro();
	?>
	<section class="container section-pad certs">
		<?php if ( $atiyeh_intro ) : ?>
			<div class="team__intro prose rv"><?php echo $atiyeh_intro; // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
		<?php endif; ?>
		<ul class="certs__grid" role="list">
			<?php foreach ( atiyeh_licenses() as [ $atiyeh_title, $atiyeh_meta ] ) : ?>
				<li class="cert rvs">
					<span class="cert__paper" aria-hidden="true">
						<i class="cert__head"></i><i></i><i class="w80"></i><i></i><i class="w55"></i>
						<span class="cert__seal"><?php echo atiyeh_icon( 'award', 26 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<span class="cert__shine"></span>
					</span>
					<b class="cert__title"><?php echo esc_html( $atiyeh_title ); ?></b>
					<span class="mut cert__meta"><?php echo esc_html( $atiyeh_meta ); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php get_template_part( 'template-parts/blocks/profile-download' ); ?>
	</section>
	<?php
	get_template_part( 'template-parts/blocks/about-nav' );
	get_template_part( 'template-parts/blocks/cta' );
endwhile;
get_footer();
