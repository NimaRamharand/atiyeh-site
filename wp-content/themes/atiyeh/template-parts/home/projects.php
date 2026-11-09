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
			<li class="projects__cell" data-filter-item="<?php echo esc_attr( $p['service'] ); ?>">
				<a class="project-card lift rv" href="<?php echo esc_url( $p['url'] ); ?>">
					<span class="project-card__media zoom">
						<span class="zi project-card__img">
							<img src="<?php echo esc_url( $p['img'] ); ?>" alt="" class="pimg" width="400" height="300" loading="lazy" decoding="async">
							<span class="project-card__tag"><?php echo esc_html( $atiyeh_terms[ $p['service'] ] ?? '' ); ?></span>
						</span>
					</span>
					<span class="project-card__body">
						<b class="project-card__title"><?php echo esc_html( atiyeh_nb( $p['title'] ) ); ?></b>
						<span class="project-card__desc mut"><?php echo esc_html( $p['desc'] ); ?></span>
					</span>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
	<p class="projects__empty" hidden data-filter-empty>پروژه‌ای در این دسته ثبت نشده است.</p>
</section>
