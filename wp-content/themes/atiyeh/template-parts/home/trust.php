<?php
/**
 * Home: «حسن انجام کار» — appreciation plaques teaser.
 *
 * @package Atiyeh
 */

?>
<section class="trust" id="trust">
	<?php atiyeh_wave( 'M0 0H1440V50C1180 100 900 0 640 50S200 90 0 40Z', '#f4f6f5', 'trust__wave' ); ?>
	<div class="trust__frame spin" aria-hidden="true"></div>
	<div class="container trust__inner">
		<div class="trust__text rvr">
			<span class="kicker kicker--pale">حسن انجام کار</span>
			<h2 class="trust__title">سندی بر تخصص و تعهد</h2>
			<p class="trust__desc">ده‌ها پلاک نصب‌شده، هرکدام با لوح سپاس کارفرمایان. نمونه‌ای از تقدیرنامه‌ها را ببینید.</p>
			<a class="btn btn--white" href="<?php echo esc_url( home_url( '/appreciations/' ) ); ?>">مشاهده لوح‌های سپاس <span class="ar" aria-hidden="true">←</span></a>
		</div>
		<div class="trust__media rvl">
			<div class="trust__img lift">
				<img src="<?php echo esc_url( atiyeh_asset( 'img/trust-plaques.webp' ) ); ?>" alt="لوح‌های سپاس کارفرمایان" class="pimg" width="700" height="525" loading="lazy" decoding="async">
			</div>
			<div class="trust__ball float" aria-hidden="true"></div>
		</div>
	</div>
</section>
