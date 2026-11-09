<?php
/**
 * «همکاری با ما»: active job openings (accordion) + application form with résumé upload.
 *
 * @package Atiyeh
 */

get_header();
while ( have_posts() ) :
	the_post();
	get_template_part( 'template-parts/page-hero', null, array( 'kicker' => 'فرصت‌های شغلی', 'desc' => 'نیروهای میدانی، اپراتور اسکن، نمایه‌سازی و کنترل کیفیت؛ رزومه خود را برای ما ارسال کنید.' ) );
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
	<section class="container section-pad careers-page">
		<?php if ( trim( wp_strip_all_tags( get_the_content() ) ) ) : ?>
			<div class="content-card prose careers-page__intro"><?php the_content(); ?></div>
		<?php endif; ?>

		<div class="sec-head"><div><span class="kicker">موقعیت‌های فعال</span><h2 class="section-title">فرصت‌های شغلی</h2></div></div>
		<?php if ( $atiyeh_jobs ) : ?>
			<div class="jobs">
				<?php foreach ( $atiyeh_jobs as $atiyeh_job ) : ?>
					<?php $atiyeh_city = get_post_meta( $atiyeh_job->ID, '_atiyeh_city', true ); ?>
					<details class="job">
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

		<div class="form-card" id="apply">
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
