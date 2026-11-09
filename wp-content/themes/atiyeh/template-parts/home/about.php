<?php
/**
 * Home: about — team image with mission overlay + four sweep links to the about sub-pages.
 *
 * @package Atiyeh
 */

$atiyeh_links = array(
	array( 'company', 'معرفی شرکت', 'a' ),
	array( 'management', 'تیم مدیریت', 'b' ),
	array( 'licenses', 'مجوزها و رتبه‌بندی‌ها', 'b' ),
	array( 'org-chart', 'چارت سازمانی', 'a' ),
);
?>
<section class="about container" id="about">
	<div class="about__media rvr">
		<div class="about__img">
			<img src="<?php echo esc_url( atiyeh_asset( 'img/careers-team.webp' ) ); ?>" alt="تیم آتیه آمار پژوهان" class="pimg" width="800" height="450" loading="lazy" decoding="async">
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
		<h2 class="about__title">آتیه آمار پژوهان پارسیان</h2>
		<p class="about__lead">[ متن معرفی شرکت، سابقه فعالیت و تخصص‌ها در اینجا قرار می‌گیرد. ]</p>
		<div class="about__links">
			<?php foreach ( $atiyeh_links as [ $slug, $label, $shape ] ) : ?>
				<a class="sweep about__link about__link--<?php echo esc_attr( $shape ); ?>" href="<?php echo esc_url( atiyeh_page_url( "about/{$slug}", '/#about' ) ); ?>"><?php echo esc_html( $label ); ?> <span aria-hidden="true">←</span></a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
