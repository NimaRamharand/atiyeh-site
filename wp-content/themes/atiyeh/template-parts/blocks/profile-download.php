<?php
/**
 * «دانلود رزومه شرکت» card (file chosen in Customizer → اطلاعات تماس شرکت). Prints nothing without a file.
 *
 * @package Atiyeh
 */

$atiyeh_pdf = atiyeh_profile_url();
if ( ! $atiyeh_pdf ) {
	return;
}
?>
<div class="pdf-card rv">
	<div class="pdf-card__text">
		<span class="pdf-card__ic" aria-hidden="true">PDF</span>
		<span><b>رزومه شرکت آمار پژوهان پارسیان</b><span class="mut">فهرست طرح‌ها و پروژه‌های اجراشده، کارفرمایان و مجوزها</span></span>
	</div>
	<a class="btn btn--primary" href="<?php echo esc_url( $atiyeh_pdf ); ?>" download>دانلود رزومه <span class="ar" aria-hidden="true">↓</span></a>
</div>
