<?php
/**
 * «درخواست خدمات»: the homepage request card on its own page.
 *
 * @package Atiyeh
 */

get_header();
while ( have_posts() ) :
	the_post();
	get_template_part( 'template-parts/page-hero', null, array( 'kicker' => 'پلاک کدپستی · آرشیو دیجیتال اسناد', 'desc' => 'فرم را تکمیل کنید؛ کارشناسان ما برای بررسی نیاز و ارائه پیشنهاد با شما تماس می‌گیرند.', 'img' => 'project-postal-plaque.webp' ) );
	?>
	<section class="container next-steps" data-seen aria-labelledby="next-title">
		<h2 class="screen-reader-text" id="next-title">پس از ارسال درخواست</h2>
		<ol class="next-steps__list">
			<?php foreach ( atiyeh_request_steps() as $atiyeh_k => [ $atiyeh_icon, $atiyeh_t, $atiyeh_d ] ) : ?>
				<li class="next-step rv">
					<span class="next-step__icon" aria-hidden="true"><?php echo atiyeh_icon( $atiyeh_icon, 24 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><i><?php echo esc_html( atiyeh_fa_num( $atiyeh_k + 1 ) ); ?></i></span>
					<span><b><?php echo esc_html( $atiyeh_t ); ?></b><span class="mut"><?php echo esc_html( $atiyeh_d ); ?></span></span>
				</li>
			<?php endforeach; ?>
		</ol>
	</section>
	<?php
	echo '<div class="section-pad request-page">';
	get_template_part( 'template-parts/home/request' );
	echo '</div>';
endwhile;
get_footer();
