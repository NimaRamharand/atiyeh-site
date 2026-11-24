<?php
/**
 * Home: about — team image with mission overlay + four sweep links to the about sub-pages.
 *
 * @package Atiyeh
 */

$atiyeh_links = array(
	array( 'company', 'معرفی شرکت', 'a' ),
	array( 'licenses', 'مجوزها و رتبه‌بندی‌ها', 'b' ),
);
?>
<section class="about container" id="about">
	<div class="about__media rvr">
		<div class="about__img rvc">
			<img src="<?php echo esc_url( atiyeh_asset( 'img/careers-team.webp' ) ); ?>" alt="تیم آمار پژوهان پارسیان" class="pimg" width="800" height="450" loading="lazy" decoding="async">
		</div>
		<div class="about__ball float" aria-hidden="true"></div>
		<div class="about__frame spin" aria-hidden="true"></div>
		<div class="about__mission">
			<div class="about__mission-k">رسالت ما</div>
			<div class="about__mission-t">نشانی دقیق و اسناد ماندگار، برای شهری منظم‌تر</div>
		</div>
	</div>
	<div class="about__text rvl">
		<span class="kicker">درباره شرکت</span>
		<h2 class="about__title">آمار پژوهان پارسیان</h2>
		<p class="about__lead">شرکت آمار پژوهان پارسیان در اصفهان، از سال ۱۳۸۸ در اجرای <strong>طرح‌های آماری و سرشماری</strong>، <strong>اسکن و آرشیو الکترونیک اسناد</strong>، <strong>بهنگام‌سازی کدپستی</strong> و <strong>ساخت و نصب پلاک‌های کدپستی هوشمند</strong> فعالیت می‌کند. گواهینامه صلاحیت شرکت‌های آماری از مرکز آمار ایران و گواهینامه صلاحیت انفورماتیک از سازمان برنامه و بودجه، در کنار اجرای بخش قابل توجهی از طرح‌های آماری و پروژه‌های کلان کشور، آمار پژوهان پارسیان را به شریکی قابل اعتماد برای سازمان‌های دولتی و خصوصی تبدیل کرده است.</p>
		<div class="about__links">
			<?php foreach ( $atiyeh_links as [ $slug, $label, $shape ] ) : ?>
				<a class="sweep about__link about__link--<?php echo esc_attr( $shape ); ?>" href="<?php echo esc_url( atiyeh_page_url( "about/{$slug}", '/#about' ) ); ?>"><?php echo esc_html( $label ); ?> <span aria-hidden="true">←</span></a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
