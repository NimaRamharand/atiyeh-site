<?php
/**
 * «تماس با ما»: contact details (Customizer, placeholders until provided) + contact form.
 *
 * @package Atiyeh
 */

get_header();
while ( have_posts() ) :
	the_post();
	get_template_part( 'template-parts/page-hero', null, array( 'kicker' => 'ارتباط با ما' ) );
	$atiyeh_form = function_exists( 'atiyeh_core_form_html' ) ? atiyeh_core_form_html( 'contact' ) : '';
	?>
	<section class="container section-pad contact-page">
		<div class="contact-page__info">
			<h2 class="contact-page__title">اطلاعات تماس</h2>
			<dl class="facts facts--stack">
				<div class="facts__item"><dt>نشانی</dt><dd><?php echo esc_html( atiyeh_nb( atiyeh_contact( 'address' ) ) ); ?></dd></div>
				<div class="facts__item"><dt>تلفن</dt><dd dir="auto"><?php echo esc_html( atiyeh_nb( atiyeh_contact( 'phone' ) ) ); ?></dd></div>
				<div class="facts__item"><dt>ایمیل</dt><dd dir="auto"><?php echo esc_html( atiyeh_nb( atiyeh_contact( 'email' ) ) ); ?></dd></div>
			</dl>
			<a class="btn btn--white" href="<?php echo esc_url( atiyeh_page_url( 'request', '/#request' ) ); ?>">درخواست خدمات <span class="ar" aria-hidden="true">←</span></a>
		</div>
		<div class="form-card contact-page__form">
			<h2 class="form-card__title">ارسال پیام</h2>
			<?php echo $atiyeh_form; // phpcs:ignore WordPress.Security.EscapeOutput -- form plugin output. ?>
		</div>
	</section>
	<?php
endwhile;
get_footer();
