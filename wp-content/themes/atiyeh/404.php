<?php
/**
 * Not found.
 *
 * @package Atiyeh
 */

get_header();
get_template_part( 'template-parts/page-hero', null, array( 'title' => 'صفحه پیدا نشد', 'kicker' => atiyeh_fa_num( 404 ) ) );
?>
<section class="container content-card prose">
	<p>صفحه‌ای که دنبالش بودید وجود ندارد یا جابه‌جا شده است.</p>
	<?php get_search_form(); ?>
	<p><a class="btn btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">بازگشت به صفحه اصلی <span class="ar" aria-hidden="true">←</span></a></p>
</section>
<?php
get_footer();
