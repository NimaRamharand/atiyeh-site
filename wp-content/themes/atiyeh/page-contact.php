<?php
/**
 * «تماس با ما»: contact details (Customizer, placeholders until provided) + contact form.
 *
 * @package Atiyeh
 */

get_header();
while ( have_posts() ) :
	the_post();
	get_template_part( 'template-parts/page-hero', null, array( 'kicker' => 'ارتباط با ما', 'desc' => 'برای مشاوره، درخواست خدمات یا هر پرسشی، از راه‌های زیر با ما در ارتباط باشید.', 'img' => 'hero-1-green-city.webp' ) );
	$atiyeh_form = function_exists( 'atiyeh_core_form_html' ) ? atiyeh_core_form_html( 'contact' ) : '';
	?>
	<section class="container section-pad contact-page">
		<div class="contact-page__info rvr">
			<div class="contact-page__ring spin" aria-hidden="true"></div>
			<h2 class="contact-page__title">اطلاعات تماس</h2>
			<dl class="facts facts--stack facts--icons">
				<div class="facts__item"><span class="facts__icon" aria-hidden="true"><?php echo atiyeh_icon( 'pin', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span><dt>نشانی</dt><dd><?php echo esc_html( atiyeh_nb( atiyeh_contact( 'address' ) ) ); ?></dd></div>
				<div class="facts__item"><span class="facts__icon" aria-hidden="true"><?php echo atiyeh_icon( 'phone', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span><dt>تلفن</dt><dd dir="auto"><?php echo esc_html( atiyeh_nb( atiyeh_contact( 'phone' ) ) ); ?></dd></div>
				<div class="facts__item"><span class="facts__icon" aria-hidden="true"><?php echo atiyeh_icon( 'mail', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span><dt>ایمیل</dt><dd dir="auto"><?php echo esc_html( atiyeh_nb( atiyeh_contact( 'email' ) ) ); ?></dd></div>
				<div class="facts__item"><span class="facts__icon" aria-hidden="true"><?php echo atiyeh_icon( 'clock', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span><dt>ساعات کاری</dt><dd>[ روزها و ساعات کاری ]</dd></div>
			</dl>
			<a class="btn btn--white" href="<?php echo esc_url( atiyeh_page_url( 'request', '/#request' ) ); ?>">درخواست خدمات <span class="ar" aria-hidden="true">←</span></a>
		</div>
		<div class="form-card contact-page__form rvl">
			<h2 class="form-card__title">ارسال پیام</h2>
			<?php echo $atiyeh_form; // phpcs:ignore WordPress.Security.EscapeOutput -- form plugin output. ?>
		</div>
	</section>

	<section class="container map-card rvs" aria-label="موقعیت دفتر">
		<svg class="map-card__svg" viewBox="0 0 1200 380" preserveAspectRatio="xMidYMid slice" aria-hidden="true" focusable="false">
			<g class="map-card__blocks"><rect x="40" y="40" width="220" height="120" rx="14"/><rect x="300" y="40" width="160" height="120" rx="14"/><rect x="500" y="40" width="260" height="120" rx="14"/><rect x="800" y="40" width="160" height="120" rx="14"/><rect x="1000" y="40" width="180" height="120" rx="14"/><rect x="40" y="210" width="160" height="140" rx="14"/><rect x="240" y="210" width="260" height="140" rx="14"/><rect x="540" y="210" width="160" height="140" rx="14"/><rect x="740" y="210" width="220" height="140" rx="14"/><rect x="1000" y="210" width="180" height="140" rx="14"/></g>
			<path class="map-card__river" d="M0 300 C200 250 300 360 520 300 S880 200 1200 260"/>
			<path class="map-card__route" d="M120 185 H480 V185 H720 Q760 185 760 150 V110"/>
		</svg>
		<div class="map-card__pin" aria-hidden="true"><?php echo atiyeh_icon( 'pin', 30 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span class="map-card__pulse"></span></div>
		<div class="map-card__label"><b>دفتر مرکزی</b><span><?php echo esc_html( atiyeh_nb( atiyeh_contact( 'address' ) ) ); ?></span></div>
	</section>

	<section class="container quick" aria-label="دسترسی سریع">
		<a class="quick-card sweep rv" href="<?php echo esc_url( atiyeh_page_url( 'request', '/#request' ) ); ?>"><span class="bd quick-card__icon" aria-hidden="true"><?php echo atiyeh_icon( 'doc', 24 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span><b class="quick-card__title">درخواست خدمات</b><span class="mut quick-card__desc">ثبت درخواست پلاک کدپستی یا آرشیو دیجیتال.</span></a>
		<a class="quick-card sweep rv" href="<?php echo esc_url( atiyeh_page_url( 'careers', '/#careers' ) ); ?>"><span class="bd quick-card__icon" aria-hidden="true"><?php echo atiyeh_icon( 'users', 24 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span><b class="quick-card__title">همکاری با ما</b><span class="mut quick-card__desc">فرصت‌های شغلی و ارسال رزومه.</span></a>
		<a class="quick-card sweep rv" href="<?php echo esc_url( (string) get_post_type_archive_link( 'project' ) ); ?>"><span class="bd quick-card__icon" aria-hidden="true"><?php echo atiyeh_icon( 'layers', 24 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span><b class="quick-card__title">نمونه پروژه‌ها</b><span class="mut quick-card__desc">پروژه‌های اجراشده برای کارفرمایان.</span></a>
	</section>
	<?php
endwhile;
get_footer();
