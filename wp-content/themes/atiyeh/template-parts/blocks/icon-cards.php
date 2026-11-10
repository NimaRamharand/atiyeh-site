<?php
/**
 * Grid of icon cards: [icon, title, text] rows.
 *
 * @package Atiyeh
 *
 * @var array $args { items: array, kicker?: string, title?: string, desc?: string, class?: string, cols?: int }
 */

$atiyeh_items = $args['items'] ?? array();
?>
<section class="container icon-cards <?php echo esc_attr( $args['class'] ?? '' ); ?>">
	<?php if ( ! empty( $args['title'] ) ) : ?>
		<div class="sec-head rv">
			<div>
				<?php if ( ! empty( $args['kicker'] ) ) : ?><span class="kicker"><?php echo esc_html( $args['kicker'] ); ?></span><?php endif; ?>
				<h2 class="section-title"><?php echo esc_html( $args['title'] ); ?></h2>
			</div>
			<?php if ( ! empty( $args['desc'] ) ) : ?><p class="sec-head__desc"><?php echo esc_html( $args['desc'] ); ?></p><?php endif; ?>
		</div>
	<?php endif; ?>
	<ul class="icon-cards__grid icon-cards__grid--<?php echo (int) ( $args['cols'] ?? 4 ); ?>" role="list">
		<?php foreach ( $atiyeh_items as [ $atiyeh_icon, $atiyeh_t, $atiyeh_d ] ) : ?>
			<li class="value-card sweep rv">
				<span class="bd value-card__icon" aria-hidden="true"><?php echo atiyeh_icon( $atiyeh_icon, 26 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				<b class="value-card__title"><?php echo esc_html( $atiyeh_t ); ?></b>
				<span class="mut value-card__desc"><?php echo esc_html( $atiyeh_d ); ?></span>
			</li>
		<?php endforeach; ?>
	</ul>
</section>
