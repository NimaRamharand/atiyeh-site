<?php
/**
 * Call-to-action band shown at the end of inner pages.
 *
 * @package Atiyeh
 *
 * @var array $args { title?: string, desc?: string }
 */

?>
<section class="container cta-wrap">
	<div class="cta rvs">
		<div class="cta__ring spin" aria-hidden="true"></div>
		<div class="cta__bubble float" aria-hidden="true"></div>
		<div class="cta__text">
			<span class="kicker kicker--pale">شروع همکاری</span>
			<h2 class="cta__title split-me"><?php echo esc_html( $args['title'] ?? 'پروژه‌ای در نظر دارید؟' ); ?></h2>
			<p class="cta__desc"><?php echo esc_html( $args['desc'] ?? 'درباره پلاک کدپستی یا آرشیو دیجیتال اسناد با کارشناسان ما صحبت کنید.' ); ?></p>
		</div>
		<div class="cta__btns">
			<a class="btn btn--white" href="<?php echo esc_url( atiyeh_page_url( 'request', '/#request' ) ); ?>">درخواست خدمات <span class="ar" aria-hidden="true">←</span></a>
			<a class="btn btn--ghost" href="<?php echo esc_url( atiyeh_page_url( 'contact', '/#contact' ) ); ?>">تماس با ما</a>
		</div>
	</div>
</section>
