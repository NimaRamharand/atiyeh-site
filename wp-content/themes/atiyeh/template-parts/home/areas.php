<?php
/**
 * Home: 8 activity areas on a soft-blue band with offset rhythm.
 *
 * @package Atiyeh
 */

$atiyeh_services = atiyeh_page_url( 'services', '/#areas' );
?>
<section class="areas" id="areas">
	<?php atiyeh_wave( 'M0 0H1440V40C1200 100 960 10 720 50S240 100 0 30Z', '#f4f6f5', 'areas__wave-top' ); ?>
	<div class="container areas__inner">
		<div class="sec-head rv">
			<div><span class="kicker">خدمات</span><h2 class="section-title">حوزه‌های فعالیت</h2></div>
			<p class="sec-head__desc">دو خدمت تخصصی در یک مجموعه: پلاک کدپستی و آرشیو دیجیتال اسناد.</p>
		</div>
		<ul class="areas__grid" role="list">
			<?php foreach ( atiyeh_home_areas() as [ $n, $title, $desc ] ) : ?>
				<li class="areas__cell rv">
					<a class="sweep area-card" href="<?php echo esc_url( $atiyeh_services ); ?>">
						<span class="bd area-card__n" aria-hidden="true"><?php echo esc_html( $n ); ?></span>
						<b class="area-card__title"><?php echo esc_html( $title ); ?></b>
						<span class="mut area-card__desc"><?php echo esc_html( $desc ); ?></span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php atiyeh_wave( 'M0 90V50C240 0 480 90 760 40S1200 0 1440 50V90Z', '#1f262b', 'areas__wave-bottom' ); ?>
</section>
