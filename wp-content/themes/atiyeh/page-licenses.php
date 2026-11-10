<?php
/**
 * «مجوزها و رتبه‌بندی‌ها»: certificate cards (CSS illustrations) with placeholder titles until the scans arrive.
 *
 * @package Atiyeh
 */

get_header();
while ( have_posts() ) :
	the_post();
	get_template_part( 'template-parts/page-hero', null, array( 'kicker' => 'درباره ما', 'desc' => 'گواهی‌ها و صلاحیت‌هایی که پشتوانه کیفیت خدمات ما هستند.', 'img' => 'trust-plaques.webp' ) );
	$atiyeh_intro = atiyeh_page_intro();
	?>
	<section class="container section-pad certs">
		<?php if ( $atiyeh_intro ) : ?>
			<div class="team__intro prose rv"><?php echo $atiyeh_intro; // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
		<?php endif; ?>
		<ul class="certs__grid" role="list">
			<?php for ( $atiyeh_k = 1; $atiyeh_k <= 6; $atiyeh_k++ ) : ?>
				<li class="cert rvs">
					<span class="cert__paper" aria-hidden="true">
						<i class="cert__head"></i><i></i><i class="w80"></i><i></i><i class="w55"></i>
						<span class="cert__seal"><?php echo atiyeh_icon( 'award', 26 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<span class="cert__shine"></span>
					</span>
					<b class="cert__title">[ عنوان مجوز یا گواهینامه ]</b>
					<span class="mut cert__meta">[ مرجع صادرکننده ] · [ تاریخ اعتبار ]</span>
				</li>
			<?php endfor; ?>
		</ul>
	</section>
	<?php
	get_template_part( 'template-parts/blocks/about-nav' );
	get_template_part( 'template-parts/blocks/cta' );
endwhile;
get_footer();
