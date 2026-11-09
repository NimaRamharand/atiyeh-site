<?php
/**
 * Project arch card (homepage + project archive).
 *
 * @package Atiyeh
 *
 * @var array $args { title, desc, service, services?, img, url }
 */

$atiyeh_terms    = atiyeh_service_terms();
$atiyeh_services = $args['services'] ?? array( $args['service'] );
?>
<li class="projects__cell" data-filter-item="<?php echo esc_attr( implode( ' ', $atiyeh_services ) ); ?>">
	<a class="project-card lift rv" href="<?php echo esc_url( $args['url'] ); ?>">
		<span class="project-card__media zoom">
			<span class="zi project-card__img">
				<img src="<?php echo esc_url( $args['img'] ); ?>" alt="" class="pimg" width="400" height="300" loading="lazy" decoding="async">
				<?php if ( ! empty( $atiyeh_terms[ $args['service'] ] ) ) : ?>
					<span class="project-card__tag"><?php echo esc_html( $atiyeh_terms[ $args['service'] ] ); ?></span>
				<?php endif; ?>
			</span>
		</span>
		<span class="project-card__body">
			<b class="project-card__title"><?php echo esc_html( atiyeh_nb( $args['title'] ) ); ?></b>
			<span class="project-card__desc mut"><?php echo esc_html( atiyeh_nb( $args['desc'] ) ); ?></span>
		</span>
	</a>
</li>
