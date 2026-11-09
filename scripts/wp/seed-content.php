<?php
/**
 * Demo content for the custom post types (needs the atiyeh-core plugin).
 * Only what the mockup shows; unknown facts stay as [ ... ] placeholders.
 *
 * @package Atiyeh
 */

if ( ! post_type_exists( 'project' ) ) {
	WP_CLI::warning( 'افزونه atiyeh-core فعال نیست؛ محتوای پروژه‌ها ساخته نشد.' );
	return;
}

atiyeh_core_ensure_service_terms();

// Projects (the three mockup cards).
$atiyeh_projects = array(
	array( 'نصب پلاک کدپستی [ شهر ]', 'plaque', 'project-postal-plaque.webp' ),
	array( 'آرشیو دیجیتال اسناد [ سازمان ]', 'archive', 'careers-team.webp' ),
	array( 'بهنگام‌سازی کدپستی [ شهر ]', 'update', 'project-map-update.webp' ),
);
foreach ( $atiyeh_projects as $atiyeh_i => [ $atiyeh_title, $atiyeh_service, $atiyeh_img ] ) {
	$atiyeh_id = atiyeh_demo_insert(
		array(
			'post_type'    => 'project',
			'post_title'   => $atiyeh_title,
			'post_excerpt' => '[ توضیح کوتاه پروژه و کارفرما ]',
			'post_content' => '<!-- wp:paragraph --><p>[ شرح پروژه، حجم کار و نتیجه توسط شرکت تکمیل می‌شود. ]</p><!-- /wp:paragraph -->',
			'menu_order'   => $atiyeh_i,
		),
		array( '_atiyeh_client' => '[ کارفرما ]', '_atiyeh_city' => '[ شهر ]', '_atiyeh_date' => '[ تاریخ ]' )
	);
	wp_set_object_terms( $atiyeh_id, $atiyeh_service, 'service' );
	$atiyeh_thumb = atiyeh_demo_image( $atiyeh_img );
	if ( $atiyeh_thumb ) {
		set_post_thumbnail( $atiyeh_id, $atiyeh_thumb );
	}
}

// Clients (logos from assets-source/logos; already 480×220, so the normaliser leaves them).
$atiyeh_clients = array(
	array( 'شهرداری اصفهان', 'logo-esfahan.webp' ),
	array( 'شهرداری افوس', 'logo-afus.webp' ),
	array( 'شهرداری نجف‌آباد', 'logo-najafabad.webp' ),
	array( 'شهرداری خمینی‌شهر', 'logo-khomeini.webp' ),
);
foreach ( $atiyeh_clients as $atiyeh_i => [ $atiyeh_title, $atiyeh_logo ] ) {
	$atiyeh_id   = atiyeh_demo_insert( array( 'post_type' => 'client', 'post_title' => $atiyeh_title, 'menu_order' => $atiyeh_i ) );
	$atiyeh_logo = atiyeh_demo_image( 'logos/' . $atiyeh_logo, 'لوگوی ' . $atiyeh_title );
	if ( $atiyeh_logo ) {
		set_post_thumbnail( $atiyeh_id, $atiyeh_logo );
	}
}

// One appreciation plaque (the only plaque image we have).
$atiyeh_id    = atiyeh_demo_insert(
	array( 'post_type' => 'appreciation', 'post_title' => 'لوح سپاس [ کارفرما ]' ),
	array( '_atiyeh_client' => '[ کارفرما ]', '_atiyeh_year' => '[ سال ]' )
);
$atiyeh_thumb = atiyeh_demo_image( 'trust-plaques.webp', 'لوح‌های سپاس کارفرمایان' );
if ( $atiyeh_thumb ) {
	set_post_thumbnail( $atiyeh_id, $atiyeh_thumb );
}

// Jobs: the five role chips from the mockup careers banner.
foreach ( array( 'اپراتور اسکن', 'بازرس میدانی', 'نصاب پلاک کدپستی', 'کارمند نمایه‌سازی', 'بازاریاب و ویزیتور' ) as $atiyeh_i => $atiyeh_title ) {
	atiyeh_demo_insert(
		array(
			'post_type'    => 'job',
			'post_title'   => $atiyeh_title,
			'post_content' => '<!-- wp:paragraph --><p>[ شرح وظایف، شرایط و مزایا توسط شرکت تکمیل می‌شود. ]</p><!-- /wp:paragraph -->',
			'menu_order'   => $atiyeh_i,
		),
		array( '_atiyeh_city' => '[ شهر ]', '_atiyeh_active' => '1' )
	);
}

// Forms (created once, updated in place on every reset so entries are kept).
$atiyeh_forms = atiyeh_core_ensure_forms();
WP_CLI::log( '  ۳ پروژه، ۴ کارفرما، ۱ لوح سپاس، ۵ فرصت شغلی' . ( $atiyeh_forms ? '، فرم‌ها: ' . implode( '، ', array_map( static fn( $k, $v ) => "{$k}=#{$v}", array_keys( $atiyeh_forms ), $atiyeh_forms ) ) : '' ) );
