<?php
/**
 * Inner-page hero banner (blue, decorative geometry, bottom wave).
 *
 * @package Atiyeh
 *
 * @var array $args { title: string, kicker?: string, desc?: string }
 */

$atiyeh_title  = $args['title'] ?? get_the_title();
$atiyeh_kicker = $args['kicker'] ?? '';
$atiyeh_desc   = $args['desc'] ?? '';
?>
<section class="page-hero">
	<div class="deco deco--ring spin" aria-hidden="true"></div>
	<div class="deco deco--diamond float" aria-hidden="true"></div>
	<div class="page-hero__inner container">
		<?php if ( $atiyeh_kicker ) : ?>
			<span class="pill"><i class="live" aria-hidden="true"></i><?php echo esc_html( $atiyeh_kicker ); ?></span>
		<?php endif; ?>
		<h1 class="page-hero__title"><?php echo esc_html( $atiyeh_title ); ?></h1>
		<?php if ( $atiyeh_desc ) : ?>
			<p class="page-hero__desc"><?php echo esc_html( $atiyeh_desc ); ?></p>
		<?php endif; ?>
	</div>
	<?php atiyeh_wave( 'M0 120V70C180 10 360 0 560 40S980 120 1180 80 1360 20 1440 40V120Z', '#f4f6f5', 'wave--bottom', 120 ); ?>
</section>
