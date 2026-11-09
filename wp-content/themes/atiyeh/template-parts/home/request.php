<?php
/**
 * Home: service request — intro panel + form.
 * Phase 4 swaps the static form for the Fluent Forms «درخواست خدمات» form (saved to the database).
 *
 * @package Atiyeh
 */

$atiyeh_form = apply_filters( 'atiyeh_request_form_html', '' );
?>
<section class="request container" id="request">
	<div class="request__card rvs">
		<div class="request__intro">
			<div class="request__frame spin" aria-hidden="true"></div>
			<span class="kicker kicker--pale">درخواست خدمات</span>
			<h2 class="request__title">پروژه‌تان را برای ما بنویسید</h2>
			<p class="request__desc">فرم را تکمیل کنید تا کارشناسان ما برای بررسی و ارائه پیشنهاد با شما تماس بگیرند. پرداخت هزینه خدمات نیز آنلاین انجام می‌شود.</p>
			<div class="request__btns">
				<a class="btn btn--white" href="<?php echo esc_url( atiyeh_page_url( 'payment', '/#request' ) ); ?>">پرداخت آنلاین (درگاه پرداخت) <span class="ar" aria-hidden="true">←</span></a>
				<a class="btn btn--ghost" href="#careers">همکاری با ما <span class="ar" aria-hidden="true">←</span></a>
			</div>
		</div>
		<div class="request__form">
			<?php if ( $atiyeh_form ) : ?>
				<?php echo $atiyeh_form; // phpcs:ignore WordPress.Security.EscapeOutput -- form plugin output. ?>
			<?php else : ?>
				<form class="req-form" action="#request" method="post" data-demo-form>
					<label>نام و نام خانوادگی<input type="text" name="name" autocomplete="name" required></label>
					<label>سازمان / شهرداری<input type="text" name="org" autocomplete="organization"></label>
					<label>شماره تماس<input type="tel" name="phone" dir="ltr" autocomplete="tel" required></label>
					<label>نوع خدمت<select name="service"><option>پلاک کدپستی</option><option>بهنگام‌سازی کدپستی</option><option>آرشیو دیجیتال اسناد</option><option>سایر</option></select></label>
					<label class="req-form__wide">شرح درخواست<textarea name="message" rows="4"></textarea></label>
					<button class="btn btn--primary req-form__submit" type="submit">ارسال درخواست <span class="ar" aria-hidden="true">←</span></button>
				</form>
			<?php endif; ?>
		</div>
	</div>
</section>
