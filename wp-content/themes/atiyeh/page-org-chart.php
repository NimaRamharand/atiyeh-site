<?php
/**
 * «چارت سازمانی»: animated tree. The structure is a sample until the company provides its chart.
 *
 * @package Atiyeh
 */

get_header();
while ( have_posts() ) :
	the_post();
	get_template_part( 'template-parts/page-hero', null, array( 'kicker' => 'درباره ما', 'desc' => 'ساختار واحدها و مسیر هماهنگی میان تیم‌های میدانی، اسکن و کنترل کیفیت.' ) );
	$atiyeh_intro = atiyeh_page_intro();
	$atiyeh_org   = atiyeh_org_chart();
	?>
	<section class="container section-pad org">
		<?php if ( $atiyeh_intro ) : ?>
			<div class="team__intro prose rv"><?php echo $atiyeh_intro; // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
		<?php endif; ?>
		<div class="org__tree" data-seen>
			<div class="org-node org-node--top rvs"><b><?php echo esc_html( $atiyeh_org['top'][0] ); ?></b><span><?php echo esc_html( atiyeh_nb( $atiyeh_org['top'][1] ) ); ?></span></div>
			<span class="org__line" aria-hidden="true"></span>
			<div class="org-node org-node--ceo rvs"><b><?php echo esc_html( $atiyeh_org['ceo'][0] ); ?></b><span><?php echo esc_html( atiyeh_nb( $atiyeh_org['ceo'][1] ) ); ?></span></div>
			<span class="org__line" aria-hidden="true"></span>
			<ul class="org__units" role="list">
				<?php foreach ( $atiyeh_org['units'] as [ $atiyeh_unit, $atiyeh_teams ] ) : ?>
					<li class="org__unit rv">
						<div class="org-node"><b><?php echo esc_html( $atiyeh_unit ); ?></b><span>[ مدیر واحد ]</span></div>
						<ul class="org__teams" role="list">
							<?php foreach ( $atiyeh_teams as $atiyeh_team ) : ?>
								<li><?php echo esc_html( $atiyeh_team ); ?></li>
							<?php endforeach; ?>
						</ul>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<p class="org__note mut">این چارت نمونه است و پس از دریافت ساختار رسمی شرکت به‌روزرسانی می‌شود.</p>
	</section>
	<?php
	get_template_part( 'template-parts/blocks/about-nav' );
	get_template_part( 'template-parts/blocks/cta' );
endwhile;
get_footer();
