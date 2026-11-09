<?php
/**
 * Home: careers banner with role chips.
 *
 * @package Atiyeh
 */

$atiyeh_roles = array( 'اپراتور اسکن', 'بازرس میدانی', 'نصاب پلاک کدپستی', 'کارمند نمایه‌سازی', 'بازاریاب و ویزیتور' );
?>
<section class="careers container" id="careers">
	<div class="careers__card rvs">
		<div class="careers__girih" aria-hidden="true"></div>
		<div class="careers__bubble float" aria-hidden="true"></div>
		<div class="careers__frame spin" aria-hidden="true"></div>
		<div class="careers__text">
			<h2 class="careers__title">به تیم ما بپیوندید</h2>
			<p class="careers__desc">نیروهای میدانی، اپراتور اسکن، نمایه‌سازی و کنترل کیفیت؛ رزومه خود را برای ما ارسال کنید.</p>
			<ul class="careers__roles" role="list">
				<?php foreach ( $atiyeh_roles as $role ) : ?>
					<li class="chip"><?php echo esc_html( $role ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<a class="btn btn--accent careers__cta" href="<?php echo esc_url( atiyeh_page_url( 'careers', '/#careers' ) ); ?>">دعوت به همکاری <span class="ar" aria-hidden="true">←</span></a>
	</div>
</section>
