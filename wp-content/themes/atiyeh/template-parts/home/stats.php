<?php
/**
 * Home: four overlapping stat circles. Numeric values count up; placeholders stay as-is.
 *
 * @package Atiyeh
 */

?>
<section class="stats container" aria-label="آمار عملکرد">
	<ul class="stats__list" role="list">
		<?php foreach ( atiyeh_home_stats() as $s ) : ?>
			<?php $atiyeh_num = preg_match( '/^[\d۰-۹,٬]+\+?$/u', $s['v'] ) ? ' data-count' : ''; ?>
			<li class="stat rvs lift">
				<span class="stat__v"<?php echo $atiyeh_num; // phpcs:ignore WordPress.Security.EscapeOutput ?>><?php echo esc_html( $s['v'] ); ?></span>
				<span class="stat__l"><?php echo esc_html( $s['l'] ); ?></span>
			</li>
		<?php endforeach; ?>
	</ul>
</section>
