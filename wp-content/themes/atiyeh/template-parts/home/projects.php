<?php
/**
 * Home: featured projects as arch cards with a client-side service filter.
 *
 * @package Atiyeh
 */

$atiyeh_terms = atiyeh_service_terms();
?>
<section class="projects container" id="projects" data-filter>
	<div class="sec-head rv">
		<div><span class="kicker">نمونه کارها</span><h2 class="section-title">پروژه‌های شاخص</h2></div>
		<div class="chips" role="group" aria-label="فیلتر بر اساس خدمت">
			<button type="button" class="chip chip--btn is-active" aria-pressed="true" data-filter-btn="all">همه</button>
			<?php foreach ( $atiyeh_terms as $slug => $label ) : ?>
				<button type="button" class="chip chip--btn" aria-pressed="false" data-filter-btn="<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $label ); ?></button>
			<?php endforeach; ?>
		</div>
	</div>
	<ul class="projects__grid" role="list">
		<?php foreach ( atiyeh_home_projects() as $p ) : ?>
			<?php get_template_part( 'template-parts/card-project', null, $p ); ?>
		<?php endforeach; ?>
	</ul>
	<p class="projects__empty" hidden data-filter-empty>پروژه‌ای در این دسته ثبت نشده است.</p>
</section>
