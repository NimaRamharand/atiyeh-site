<?php
/**
 * Home: four-step process.
 *
 * @package Atiyeh
 */

?>
<section class="process container" aria-labelledby="process-title">
	<div class="process__head rv"><span class="kicker">روش کار</span><h2 class="section-title" id="process-title">از درخواست تا تحویل، در چهار گام</h2></div>
	<ol class="process__grid">
		<?php foreach ( atiyeh_home_steps() as [ $n, $title, $desc ] ) : ?>
			<li class="step sweep rv">
				<span class="bd step__n" aria-hidden="true"><?php echo esc_html( $n ); ?></span>
				<b class="step__title"><?php echo esc_html( $title ); ?></b>
				<span class="mut step__desc"><?php echo esc_html( $desc ); ?></span>
			</li>
		<?php endforeach; ?>
	</ol>
</section>
