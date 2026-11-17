<?php
/**
 * Home: informatics & AI services — dark band with a live neural-network illustration.
 *
 * @package Atiyeh
 */

$atiyeh_it = array(
	array( 'layers', 'سامانه‌ها و نرم‌افزارهای تحت وب', 'طراحی و پیاده‌سازی سامانه‌های اختصاصی، متناسب با فرایندهای سازمان شما.' ),
	array( 'spark', 'خودکارسازی با هوش مصنوعی', 'سپردن کارهای تکراری مثل دسته‌بندی، پاسخ‌گویی و گزارش‌گیری به دستیارهای هوشمند.' ),
	array( 'doc', 'استخراج هوشمند اطلاعات از اسناد', 'تبدیل اسناد اسکن‌شده به متن قابل جستجو و بیرون کشیدن اطلاعات کلیدی، به کمک هوش مصنوعی.' ),
	array( 'chart', 'تحلیل داده و داشبورد مدیریتی', 'نمایش شاخص‌های کلیدی در داشبوردهای گویا، برای تصمیم‌گیری سریع‌تر و دقیق‌تر.' ),
);
?>
<section class="informatics" id="informatics" aria-labelledby="informatics-title">
	<?php atiyeh_wave( 'M0 0H1440V40C1180 100 900 0 640 50S200 90 0 40Z', 'var(--c-page)', 'informatics__wave' ); ?>
	<div class="informatics__grid-bg" aria-hidden="true"></div>
	<div class="container informatics__inner">
		<div class="informatics__text rvr">
			<span class="kicker kicker--light">خدمات انفورماتیک</span>
			<h2 class="section-title" id="informatics-title">از داده تا تصمیم، با هوش مصنوعی</h2>
			<p class="informatics__lead">در کنار پلاک کدپستی و آرشیو اسناد، راهکارهای نرم‌افزاری و هوشمند برای سازمان‌ها ارائه می‌کنیم. با بهره‌گیری از هوش مصنوعی، کارهای زمان‌بر را خودکار می‌کنیم و داده‌های پراکنده را به اطلاعاتی قابل اتکا برای مدیریت تبدیل می‌کنیم.</p>
			<ul class="informatics__cards" role="list">
				<?php foreach ( $atiyeh_it as [ $atiyeh_icon, $atiyeh_t, $atiyeh_d ] ) : ?>
					<li class="it-card rv">
						<span class="it-card__icon" aria-hidden="true"><?php echo atiyeh_icon( $atiyeh_icon, 22 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<b class="it-card__title"><?php echo esc_html( $atiyeh_t ); ?></b>
						<span class="it-card__desc"><?php echo esc_html( $atiyeh_d ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
			<a class="btn btn--accent" href="<?php echo esc_url( atiyeh_page_url( 'request', '/#request' ) ); ?>">درخواست مشاوره <span class="ar" aria-hidden="true">←</span></a>
		</div>
		<div class="informatics__art rvl"><?php get_template_part( 'template-parts/blocks/ai-art' ); ?></div>
	</div>
	<?php atiyeh_wave( 'M0 90V50C240 0 480 90 760 40S1200 0 1440 50V90Z', 'var(--c-primary)', 'informatics__wave-bottom' ); ?>
</section>
