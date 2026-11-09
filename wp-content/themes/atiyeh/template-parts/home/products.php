<?php
/**
 * Home: dark bento of the two core services (featured services).
 *
 * @package Atiyeh
 */

$atiyeh_services = atiyeh_page_url( 'services', '/#areas' );
$atiyeh_request  = atiyeh_page_url( 'request', '/#request' );
?>
<section class="products" id="products">
	<div class="products__ring spin" aria-hidden="true"></div>
	<div class="container products__inner">
		<div class="sec-head rv">
			<div><span class="kicker kicker--light">خدمات شاخص</span><h2 class="section-title">دو خدمت، یک تعهد به دقت</h2></div>
			<a class="btn btn--outline-dark" href="<?php echo esc_url( $atiyeh_services ); ?>">همه خدمات <span class="ar" aria-hidden="true">←</span></a>
		</div>
		<div class="bento">
			<article class="bento__card bento__card--main rvs dk">
				<img src="<?php echo esc_url( atiyeh_asset( 'img/project-postal-plaque.webp' ) ); ?>" alt="" class="pimg bento__bg" width="600" height="750" loading="lazy" decoding="async">
				<div class="bento__shade" aria-hidden="true"></div>
				<div class="bento__bubble float" aria-hidden="true"></div>
				<div class="bento__frame spin" aria-hidden="true"></div>
				<span class="bento__badge">خدمت اصلی</span>
				<div class="bento__body">
					<h3 class="bento__title">پلاک کدپستی</h3>
					<p class="bento__desc">تولید و نصب پلاک کدپستی و بهنگام‌سازی نشانی ساختمان‌ها، از برداشت میدانی تا تأیید نهایی.</p>
					<a class="btn btn--white" href="<?php echo esc_url( $atiyeh_services ); ?>">اطلاعات بیشتر <span class="ar" aria-hidden="true">←</span></a>
				</div>
			</article>
			<article class="bento__card bento__card--archive rvs dk">
				<span class="bento__icon" aria-hidden="true">◎</span>
				<div>
					<h3 class="bento__sub">آرشیو دیجیتال اسناد</h3>
					<a class="bento__link" href="<?php echo esc_url( $atiyeh_request ); ?>">درخواست مشاوره <span aria-hidden="true">←</span></a>
				</div>
			</article>
			<article class="bento__card bento__card--s1 rvs dk">
				<h3 class="bento__sub">نصب و بهنگام‌سازی</h3>
				<a class="bento__link" href="<?php echo esc_url( $atiyeh_services ); ?>">مشاهده <span class="screen-reader-text">نصب و بهنگام‌سازی</span> <span aria-hidden="true">←</span></a>
			</article>
			<article class="bento__card bento__card--s2 rvs dk">
				<h3 class="bento__sub">اسکن عمده اسناد</h3>
				<a class="bento__link" href="<?php echo esc_url( $atiyeh_services ); ?>">مشاهده <span class="screen-reader-text">اسکن عمده اسناد</span> <span aria-hidden="true">←</span></a>
			</article>
			<article class="bento__card bento__card--s3 rvs dk">
				<h3 class="bento__sub">نمایه‌سازی و بازیابی</h3>
				<a class="bento__link" href="<?php echo esc_url( $atiyeh_services ); ?>">مشاهده <span class="screen-reader-text">نمایه‌سازی و بازیابی</span> <span aria-hidden="true">←</span></a>
			</article>
		</div>
	</div>
	<?php atiyeh_wave( 'M0 100V60C300 110 520 10 800 50S1250 90 1440 30V100Z', '#f4f6f5', 'products__wave', 100 ); ?>
</section>
