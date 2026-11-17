<?php
/**
 * Home: scanner consulting & supply — an animated scanner illustration beside the offer.
 *
 * @package Atiyeh
 */

$atiyeh_points = array(
	array( 'target', 'مشاوره انتخاب', 'اسکنر متناسب با حجم کار، نوع و اندازه اسناد و بودجه شما.' ),
	array( 'layers', 'تأمین دستگاه', 'خرید اسکنر و تجهیزات جانبی موردنیاز با هماهنگی کامل.' ),
	array( 'spark', 'نصب و راه‌اندازی', 'راه‌اندازی دستگاه و تنظیم نرم‌افزار اسکن در محل شما.' ),
	array( 'users', 'آموزش و پشتیبانی', 'آموزش اپراتورها و همراهی پس از خرید.' ),
);
?>
<section class="scanner container" id="scanner" aria-labelledby="scanner-title">
	<div class="scanner__art rvr" aria-hidden="true">
		<div class="scanner__glow"></div>
		<div class="scanner__device">
			<div class="scanner__tray"><span class="scanner__sheet"><i></i><i></i><i class="w70"></i><i></i></span></div>
			<div class="scanner__body">
				<span class="scanner__slot"></span>
				<span class="scanner__light"></span>
				<span class="scanner__panel"><i></i><i></i><i></i></span>
			</div>
			<div class="scanner__out"><span class="scanner__sheet scanner__sheet--out"><i></i><i></i><i class="w70"></i><i></i></span></div>
		</div>
		<div class="scanner__file float"><b>PDF</b><span>سند اسکن‌شده</span></div>
		<div class="scanner__ring spin"></div>
	</div>
	<div class="scanner__text rvl">
		<span class="kicker">مشاوره و تأمین تجهیزات</span>
		<h2 class="section-title" id="scanner-title">اسکنر مناسب کارتان را با هم انتخاب کنیم</h2>
		<p class="scanner__lead">تجربه اجرای پروژه‌های اسکن عمده کمک می‌کند بهترین دستگاه را متناسب با نیاز سازمان شما پیشنهاد دهیم؛ از مشاوره و خرید تا راه‌اندازی و آموزش.</p>
		<ul class="scanner__points" role="list">
			<?php foreach ( $atiyeh_points as [ $atiyeh_icon, $atiyeh_t, $atiyeh_d ] ) : ?>
				<li class="svc-feature rv">
					<span class="svc-feature__icon" aria-hidden="true"><?php echo atiyeh_icon( $atiyeh_icon, 22 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<span><b class="svc-feature__title"><?php echo esc_html( $atiyeh_t ); ?></b><span class="mut svc-feature__desc"><?php echo esc_html( $atiyeh_d ); ?></span></span>
				</li>
			<?php endforeach; ?>
		</ul>
		<a class="btn btn--primary" href="<?php echo esc_url( atiyeh_page_url( 'request', '/#request' ) ); ?>">درخواست مشاوره خرید <span class="ar" aria-hidden="true">←</span></a>
	</div>
</section>
