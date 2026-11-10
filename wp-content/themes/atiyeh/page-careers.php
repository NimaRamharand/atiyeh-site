<?php
/**
 * «همکاری با ما»: active job openings (accordion) + application form with résumé upload.
 *
 * @package Atiyeh
 */

get_header();
while ( have_posts() ) :
	the_post();
	get_template_part( 'template-parts/page-hero', null, array( 'kicker' => 'فرصت‌های شغلی', 'desc' => 'نیروهای میدانی، اپراتور اسکن، نمایه‌سازی و کنترل کیفیت؛ رزومه خود را برای ما ارسال کنید.', 'img' => 'careers-team.webp' ) );
	$atiyeh_jobs = post_type_exists( 'job' ) ? get_posts(
		array(
			'post_type'   => 'job',
			'numberposts' => -1,
			'orderby'     => 'menu_order',
			'order'       => 'ASC',
			'meta_key'    => '_atiyeh_active', // phpcs:ignore WordPress.DB.SlowDBQuery
			'meta_value'  => '1', // phpcs:ignore WordPress.DB.SlowDBQuery
		)
	) : array();
	?>
	<?php
	$atiyeh_intro = atiyeh_page_intro();
	if ( $atiyeh_intro ) {
		echo '<div class="container"><div class="team__intro team__intro--top prose rv">' . $atiyeh_intro . '</div></div>'; // phpcs:ignore WordPress.Security.EscapeOutput
	}
	get_template_part( 'template-parts/blocks/icon-cards', null, array( 'items' => atiyeh_perks(), 'kicker' => 'چرا با ما', 'title' => 'کار در آمار پژوهان پارسیان', 'class' => 'perks' ) );
	?>
	<section class="container section-pad careers-page">
		<div class="sec-head rv"><div><span class="kicker">موقعیت‌های فعال</span><h2 class="section-title">فرصت‌های شغلی</h2></div></div>
		<?php if ( $atiyeh_jobs ) : ?>
			<div class="jobs">
				<?php foreach ( $atiyeh_jobs as $atiyeh_job ) : ?>
					<?php $atiyeh_city = get_post_meta( $atiyeh_job->ID, '_atiyeh_city', true ); ?>
					<details class="job rv">
						<summary class="job__head">
							<b class="job__title"><?php echo esc_html( get_the_title( $atiyeh_job ) ); ?></b>
							<?php if ( $atiyeh_city ) : ?><span class="job__city mut"><?php echo esc_html( atiyeh_nb( $atiyeh_city ) ); ?></span><?php endif; ?>
							<span class="job__toggle" aria-hidden="true"></span>
						</summary>
						<div class="job__body prose"><?php echo wp_kses_post( apply_filters( 'the_content', $atiyeh_job->post_content ) ); ?>
							<a class="btn btn--primary job__apply" href="#apply">ارسال رزومه <span class="ar" aria-hidden="true">←</span></a>
						</div>
					</details>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<p class="mut">در حال حاضر موقعیت فعالی نداریم؛ رزومه شما برای فرصت‌های آینده ثبت می‌شود.</p>
		<?php endif; ?>

		<div class="hire-steps" data-seen>
			<h2 class="hire-steps__title rv">مسیر استخدام</h2>
			<ol class="hire-steps__list">
				<?php foreach ( atiyeh_hiring_steps() as [ $atiyeh_n, $atiyeh_t, $atiyeh_d ] ) : ?>
					<li class="hire-step rv"><span class="hire-step__n"><?php echo esc_html( $atiyeh_n ); ?></span><b><?php echo esc_html( $atiyeh_t ); ?></b><span class="mut"><?php echo esc_html( $atiyeh_d ); ?></span></li>
				<?php endforeach; ?>
			</ol>
		</div>

		<div class="form-card rvs" id="apply">
			<h2 class="form-card__title">ارسال رزومه</h2>
			<?php
			if ( function_exists( 'atiyeh_core_application_form' ) ) {
				echo atiyeh_core_application_form(); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside.
			}
			?>
		</div>
	</section>
	<?php
endwhile;
get_footer();
