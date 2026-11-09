<?php
/**
 * Demo news posts — the three headlines from the mockup. Dates and body text stay placeholders.
 *
 * @package Atiyeh
 */

$atiyeh_news = array(
	array( 'آغاز پروژه نصب پلاک کدپستی [ شهر ]', 'project-postal-plaque.webp' ),
	array( 'راه‌اندازی مرکز اسکن اسناد [ سازمان ]', 'careers-team.webp' ),
	array( 'پایان بهنگام‌سازی کدپستی [ شهر ]', 'project-map-update.webp' ),
);
// Oldest first so the first headline is the newest, as in the mockup.
foreach ( array_reverse( $atiyeh_news ) as $atiyeh_i => [ $atiyeh_title, $atiyeh_img ] ) {
	$atiyeh_id = atiyeh_demo_insert(
		array(
			'post_type'    => 'post',
			'post_title'   => $atiyeh_title,
			'post_content' => '<!-- wp:paragraph --><p>[ متن خبر توسط شرکت تکمیل می‌شود. ]</p><!-- /wp:paragraph -->',
			'post_excerpt' => '[ خلاصه خبر ]',
			'post_date'    => gmdate( 'Y-m-d H:i:s', time() - ( 3 - $atiyeh_i ) * HOUR_IN_SECONDS ),
		),
		array( '_atiyeh_date_placeholder' => 1 )
	);
	$atiyeh_thumb = atiyeh_demo_image( $atiyeh_img );
	if ( $atiyeh_thumb ) {
		set_post_thumbnail( $atiyeh_id, $atiyeh_thumb );
	}
}
WP_CLI::log( '  ۳ خبر نمونه' );
