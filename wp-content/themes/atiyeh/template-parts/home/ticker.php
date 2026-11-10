<?php
/**
 * Home: two crossing text bands (decorative, hidden from screen readers) between the services and stats.
 *
 * @package Atiyeh
 */

$atiyeh_words = array( 'پلاک کدپستی', 'آرشیو دیجیتال اسناد', 'بهنگام‌سازی نشانی', 'اسکن و نمایه‌سازی', 'کنترل کیفیت میدانی', 'بازیابی سریع اسناد' );
$atiyeh_group = static function () use ( $atiyeh_words ): void {
	echo '<div class="ticker__group">';
	foreach ( $atiyeh_words as $w ) {
		echo '<span>' . esc_html( $w ) . '</span><i>✦</i>';
	}
	echo '</div>';
};
?>
<div class="ticker-wrap" aria-hidden="true">
	<div class="ticker" dir="ltr"><div class="ticker__track"><?php $atiyeh_group(); $atiyeh_group(); $atiyeh_group(); $atiyeh_group(); ?></div></div>
	<div class="ticker ticker--alt" dir="ltr"><div class="ticker__track"><?php $atiyeh_group(); $atiyeh_group(); $atiyeh_group(); $atiyeh_group(); ?></div></div>
</div>
