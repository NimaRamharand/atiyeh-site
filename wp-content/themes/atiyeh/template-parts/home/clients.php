<?php
/**
 * Home: client logo marquee (logos + dashed placeholders), duplicated for a seamless loop.
 *
 * @package Atiyeh
 */

$atiyeh_clients = atiyeh_home_clients();
$atiyeh_track   = static function ( bool $clone ) use ( $atiyeh_clients ): void {
	echo '<div class="marquee__group"' . ( $clone ? ' aria-hidden="true"' : '' ) . '>';
	foreach ( $atiyeh_clients as $c ) {
		printf(
			'<span class="marquee__item lift"><img src="%1$s" alt="%2$s" width="200" height="92" loading="lazy" decoding="async"></span>',
			esc_url( $c['src'] ),
			esc_attr( $clone ? '' : $c['alt'] )
		);
	}
	for ( $k = 0; $k < 2; $k++ ) {
		echo '<span class="marquee__item marquee__item--empty chip">[ لوگو کارفرما ]</span>';
	}
	echo '</div>';
};
?>
<section class="clients" aria-labelledby="clients-title">
	<h2 class="clients__title" id="clients-title">مورد اعتماد کارفرمایان</h2>
	<div class="marquee" dir="ltr">
		<div class="marquee__track">
			<?php $atiyeh_track( false ); ?>
			<?php $atiyeh_track( true ); ?>
		</div>
	</div>
</section>
