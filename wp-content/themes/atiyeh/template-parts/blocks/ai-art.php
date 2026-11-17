<?php
/**
 * Decorative AI illustration: a neural network whose links pulse, and a code window being "typed".
 * Pure SVG + CSS (no images, no external assets). Hidden from assistive technology.
 *
 * @package Atiyeh
 */

$atiyeh_nodes = array( array( 60, 70 ), array( 60, 170 ), array( 60, 270 ), array( 190, 40 ), array( 190, 140 ), array( 190, 240 ), array( 190, 330 ), array( 320, 110 ), array( 320, 220 ) );
$atiyeh_links = array( array( 0, 3 ), array( 0, 4 ), array( 1, 4 ), array( 1, 5 ), array( 2, 5 ), array( 2, 6 ), array( 0, 5 ), array( 1, 3 ), array( 2, 4 ), array( 3, 7 ), array( 4, 7 ), array( 4, 8 ), array( 5, 8 ), array( 6, 8 ), array( 5, 7 ) );
?>
<div class="ai-art" aria-hidden="true">
	<svg class="ai-art__net" viewBox="0 0 380 370">
		<?php foreach ( $atiyeh_links as $k => [ $a, $b ] ) : ?>
			<line x1="<?php echo (int) $atiyeh_nodes[ $a ][0]; ?>" y1="<?php echo (int) $atiyeh_nodes[ $a ][1]; ?>" x2="<?php echo (int) $atiyeh_nodes[ $b ][0]; ?>" y2="<?php echo (int) $atiyeh_nodes[ $b ][1]; ?>" style="--k:<?php echo (int) $k; ?>"/>
		<?php endforeach; ?>
		<?php foreach ( $atiyeh_nodes as $k => [ $x, $y ] ) : ?>
			<circle cx="<?php echo (int) $x; ?>" cy="<?php echo (int) $y; ?>" r="<?php echo $k > 6 ? 15 : 10; ?>" style="--k:<?php echo (int) $k; ?>"/>
		<?php endforeach; ?>
	</svg>
	<div class="ai-art__win">
		<div class="ai-art__bar"><i></i><i></i><i></i><span>AI</span></div>
		<div class="ai-art__code" dir="ltr">
			<p><b>model</b>.analyze(<em>documents</em>)</p>
			<p><b>extract</b>(<em>"postal_code"</em>, <em>"address"</em>)</p>
			<p><b>dashboard</b>.update()</p>
			<p class="ai-art__ok">✓ done</p>
		</div>
	</div>
	<div class="ai-art__chip"><b>AI</b></div>
</div>
