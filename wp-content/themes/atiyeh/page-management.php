<?php
/**
 * «تیم مدیریت»: team cards with illustrated avatars. Names, roles and bios stay placeholders.
 *
 * @package Atiyeh
 */

get_header();
while ( have_posts() ) :
	the_post();
	get_template_part( 'template-parts/page-hero', null, array( 'kicker' => 'درباره ما', 'desc' => 'مدیرانی که برنامه‌ریزی، اجرا و کیفیت پروژه‌ها را هدایت می‌کنند.', 'img' => 'careers-team.webp' ) );
	$atiyeh_intro = atiyeh_page_intro();
	$atiyeh_team = array( array( atiyeh_contact( 'ceo' ), 'مدیرعامل', 'هدایت طرح‌های آماری، کدپستی، پلاک و آرشیو الکترونیک شرکت.' ) );
	?>
	<section class="container section-pad team">
		<?php if ( $atiyeh_intro ) : ?>
			<div class="team__intro prose rv"><?php echo $atiyeh_intro; // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
		<?php endif; ?>
		<ul class="team__grid team__grid--solo" role="list">
			<?php foreach ( $atiyeh_team as [ $atiyeh_name, $atiyeh_role, $atiyeh_bio ] ) : ?>
				<li class="team-card rvs">
					<span class="team-card__avatar" aria-hidden="true">
						<svg viewBox="0 0 120 120"><circle cx="60" cy="46" r="22"/><path d="M18 116c4-26 21-40 42-40s38 14 42 40z"/></svg>
						<span class="team-card__ring spin"></span>
					</span>
					<b class="team-card__name"><?php echo esc_html( $atiyeh_name ); ?></b>
					<span class="team-card__role"><?php echo esc_html( $atiyeh_role ); ?></span>
					<span class="mut team-card__bio"><?php echo esc_html( $atiyeh_bio ); ?></span>
					<a class="team-card__tel" href="tel:<?php echo esc_attr( atiyeh_tel( atiyeh_contact( 'mobile' ) ) ); ?>" dir="ltr"><?php echo esc_html( atiyeh_contact( 'mobile' ) ); ?></a>
				</li>
			<?php endforeach; ?>
		</ul>
	</section>
	<?php
	get_template_part( 'template-parts/blocks/about-nav' );
	get_template_part( 'template-parts/blocks/cta' );
endwhile;
get_footer();
