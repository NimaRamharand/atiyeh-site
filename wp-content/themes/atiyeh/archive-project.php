<?php
/**
 * Projects archive (/projects/) and service term pages: arch cards + service filter.
 *
 * @package Atiyeh
 */

get_header();
$atiyeh_term   = is_tax( 'service' ) ? get_queried_object() : null;
$atiyeh_active = $atiyeh_term ? $atiyeh_term->slug : 'all';
get_template_part(
	'template-parts/page-hero',
	null,
	array(
		'title'  => $atiyeh_term ? 'پروژه‌های ' . $atiyeh_term->name : 'پروژه‌ها',
		'kicker' => 'نمونه کارها',
		'desc'   => 'نمونه‌ای از پروژه‌های پلاک کدپستی و آرشیو دیجیتال اسناد که برای کارفرمایان اجرا شده است.',
		'img'    => 'project-map-update.webp',
	)
);
?>
<section class="projects projects--archive container" data-filter>
	<div class="chips projects__chips rv" role="group" aria-label="فیلتر بر اساس خدمت">
		<button type="button" class="chip chip--btn<?php echo 'all' === $atiyeh_active ? ' is-active' : ''; ?>" aria-pressed="<?php echo 'all' === $atiyeh_active ? 'true' : 'false'; ?>" data-filter-btn="all">همه</button>
		<?php foreach ( atiyeh_service_terms() as $atiyeh_slug => $atiyeh_label ) : ?>
			<?php $atiyeh_on = $atiyeh_slug === $atiyeh_active; ?>
			<button type="button" class="chip chip--btn<?php echo $atiyeh_on ? ' is-active' : ''; ?>" aria-pressed="<?php echo $atiyeh_on ? 'true' : 'false'; ?>" data-filter-btn="<?php echo esc_attr( $atiyeh_slug ); ?>"><?php echo esc_html( $atiyeh_label ); ?></button>
		<?php endforeach; ?>
	</div>
	<?php if ( have_posts() ) : ?>
		<ul class="projects__grid" role="list">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/card-project', null, atiyeh_project_item( get_post() ) );
			endwhile;
			?>
		</ul>
	<?php endif; ?>
	<p class="projects__empty"<?php echo have_posts() ? ' hidden' : ''; ?> data-filter-empty>پروژه‌ای در این دسته ثبت نشده است.</p>
</section>
<?php
get_template_part( 'template-parts/blocks/cta', null, array( 'title' => 'پروژه بعدی را با هم اجرا کنیم', 'desc' => 'برای پروژه پلاک کدپستی یا آرشیو دیجیتال سازمان خود درخواست ثبت کنید.' ) );
get_footer();
