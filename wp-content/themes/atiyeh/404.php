<?php
/**
 * Not found.
 *
 * @package Atiyeh
 */

get_header();
get_template_part( 'template-parts/page-hero', null, array( 'title' => 'صفحه پیدا نشد', 'kicker' => 'خطای ' . atiyeh_fa_num( 404 ) ) );
?>
<section class="container content-card prose nf rv">
	<div class="nf__art" aria-hidden="true"><span class="float">۴</span><span class="nf__zero spin">۰</span><span class="float2">۴</span></div>
	<p>صفحه‌ای که دنبالش بودید وجود ندارد یا جابه‌جا شده است. می‌توانید جستجو کنید یا به صفحه اصلی برگردید.</p>
	<?php get_search_form(); ?>
	<p class="nf__btns"><a class="btn btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">بازگشت به صفحه اصلی <span class="ar" aria-hidden="true">←</span></a> <a class="btn btn--outline" href="<?php echo esc_url( atiyeh_page_url( 'contact', '/#contact' ) ); ?>">تماس با ما</a></p>
</section>
<?php
get_footer();
