<?php
/**
 * Homepage content taken verbatim from the approved mockup (docs/mockup/export/Main.dc.html).
 * Bracketed values such as «[ ۰۰ ]» are real data the company has not provided yet — never invent them.
 *
 * Sections whose content becomes editable later (projects, clients, news) read from WordPress first
 * and fall back to these arrays.
 *
 * @package Atiyeh
 */

/** URL of a page by slug, or a home anchor when the page is missing. */
function atiyeh_page_url( string $slug, string $fallback = '/' ): string {
	$page = get_page_by_path( $slug );
	return $page ? (string) get_permalink( $page ) : home_url( $fallback );
}

/**
 * Hero slides (3, rotating every 5.5 s).
 *
 * @return array<int, array<string, mixed>>
 */
function atiyeh_hero_slides(): array {
	return array(
		array(
			'kicker' => 'خدمت اصلی',
			'title'  => 'پلاک کدپستی، نشانی دقیق هر ملک',
			'desc'   => 'تولید، نصب و بهنگام‌سازی پلاک کدپستی در سطح شهر، همراه با کنترل کیفیت میدانی.',
			'cta'    => 'خدمات پلاک',
			'bg'     => 'linear-gradient(135deg,#0f3f8a,#1757b8)',
			'c1'     => '#2a6fd6',
			'c2'     => '#7ab8ff',
			'chip'   => 'پلاک کدپستی',
			'img'    => 'project-postal-plaque.webp',
			'docs'   => false,
		),
		array(
			'kicker' => 'آرشیو دیجیتال',
			'title'  => 'اسناد سازمان شما، دیجیتال و در دسترس',
			'desc'   => 'اسکن عمده اسناد، فهرست‌نویسی و بازیابی سریع؛ بدون دغدغه فضا و فرسودگی کاغذ.',
			'cta'    => 'آرشیو دیجیتال',
			'bg'     => 'linear-gradient(135deg,#1f262b,#34404a)',
			'c1'     => '#134aa0',
			'c2'     => '#7ab8ff',
			'chip'   => 'اسکن اسناد',
			'img'    => 'pattern-girih.webp',
			'docs'   => true,
		),
		array(
			'kicker' => 'میدانی',
			'title'  => 'بهنگام‌سازی کدپستی در سراسر شهر',
			'desc'   => 'برداشت میدانی و به‌روزرسانی نشانی و کدپستی ساختمان‌ها با نیروهای آموزش‌دیده.',
			'cta'    => 'بهنگام‌سازی',
			'bg'     => 'linear-gradient(135deg,#134aa0,#2f3b43)',
			'c1'     => '#2563c4',
			'c2'     => '#cfe3ff',
			'chip'   => 'بهنگام‌سازی',
			'img'    => 'project-map-update.webp',
			'docs'   => false,
		),
	);
}

/**
 * Client logos for the marquee. Phase 3 replaces this with the `client` post type.
 *
 * @return array<int, array{src:string, alt:string}>
 */
function atiyeh_home_clients(): array {
	$clients = apply_filters( 'atiyeh_home_clients', null );
	if ( is_array( $clients ) && $clients ) {
		return $clients;
	}
	return array(
		array( 'src' => atiyeh_asset( 'img/logos/logo-esfahan.webp' ), 'alt' => 'شهرداری اصفهان' ),
		array( 'src' => atiyeh_asset( 'img/logos/logo-afus.webp' ), 'alt' => 'شهرداری افوس' ),
		array( 'src' => atiyeh_asset( 'img/logos/logo-najafabad.webp' ), 'alt' => 'شهرداری نجف‌آباد' ),
		array( 'src' => atiyeh_asset( 'img/logos/logo-khomeini.webp' ), 'alt' => 'شهرداری خمینی‌شهر' ),
	);
}

/**
 * The 8 activity areas.
 *
 * @return array<int, array{0:string,1:string,2:string}>
 */
function atiyeh_home_areas(): array {
	return array(
		array( '۰۱', 'تولید پلاک کدپستی', 'طراحی و ساخت پلاک ماندگار با استاندارد کدپستی' ),
		array( '۰۲', 'نصب میدانی پلاک', 'نصب پلاک بر سر در ساختمان‌ها با تیم آموزش‌دیده' ),
		array( '۰۳', 'بهنگام‌سازی کدپستی', 'به‌روزرسانی نشانی و کدپستی واحدها و ساختمان‌ها' ),
		array( '۰۴', 'کنترل کیفیت نصب', 'بازرسی و تأیید صحت نصب و اطلاعات هر پلاک' ),
		array( '۰۵', 'اسکن عمده اسناد', 'اسکن سریع و دقیق حجم بالای اسناد سازمانی' ),
		array( '۰۶', 'نمایه‌سازی و فهرست‌نویسی', 'ثبت مشخصات هر سند برای جستجوی آسان' ),
		array( '۰۷', 'سامانه بازیابی اسناد', 'جستجو و دسترسی آنی به آرشیو دیجیتال' ),
		array( '۰۸', 'ذخیره‌سازی امن و پشتیبان', 'نگهداری ایمن و پشتیبان‌گیری از آرشیو' ),
	);
}

/**
 * Stats circles. Values are editable in the Customizer and stay «[ ۰۰ ]» until the company provides them.
 *
 * @return array<int, array{v:string, l:string}>
 */
function atiyeh_home_stats(): array {
	$labels = array(
		'plaques' => 'پلاک نصب‌شده',
		'docs'    => 'سند اسکن‌شده',
		'clients' => 'کارفرمای فعال',
		'years'   => 'سال تجربه',
	);
	$out = array();
	foreach ( $labels as $key => $label ) {
		$value = trim( (string) get_theme_mod( "atiyeh_stat_{$key}", '' ) );
		$out[] = array(
			'v' => '' === $value ? '[ ۰۰ ]' : $value,
			'l' => $label,
		);
	}
	return $out;
}

/**
 * Service filter chips: slug => label.
 *
 * @return array<string, string>
 */
function atiyeh_service_terms(): array {
	return array(
		'plaque'  => 'پلاک کدپستی',
		'archive' => 'آرشیو دیجیتال',
		'update'  => 'بهنگام‌سازی',
	);
}

/**
 * Featured projects. Phase 3 replaces this with the `project` post type.
 *
 * @return array<int, array{title:string, desc:string, service:string, img:string, url:string}>
 */
function atiyeh_home_projects(): array {
	$projects = apply_filters( 'atiyeh_home_projects', null );
	if ( is_array( $projects ) && $projects ) {
		return $projects;
	}
	return array(
		array( 'title' => 'نصب پلاک کدپستی [ شهر ]', 'desc' => '[ توضیح کوتاه پروژه و کارفرما ]', 'service' => 'plaque', 'img' => atiyeh_asset( 'img/project-postal-plaque.webp' ), 'url' => '#' ),
		array( 'title' => 'آرشیو دیجیتال اسناد [ سازمان ]', 'desc' => '[ توضیح کوتاه پروژه و کارفرما ]', 'service' => 'archive', 'img' => atiyeh_asset( 'img/careers-team.webp' ), 'url' => '#' ),
		array( 'title' => 'بهنگام‌سازی کدپستی [ شهر ]', 'desc' => '[ توضیح کوتاه پروژه و کارفرما ]', 'service' => 'update', 'img' => atiyeh_asset( 'img/project-map-update.webp' ), 'url' => '#' ),
	);
}

/**
 * Latest news: real posts first, mockup placeholders when there are none.
 *
 * @return array<int, array{title:string, date:string, img:string, url:string}>
 */
function atiyeh_home_news(): array {
	$fallback_imgs = array( 'project-postal-plaque.webp', 'careers-team.webp', 'project-map-update.webp' );
	$posts         = get_posts( array( 'numberposts' => 3, 'post_status' => 'publish', 'suppress_filters' => false ) );
	$out           = array();
	foreach ( $posts as $i => $post ) {
		$img   = get_the_post_thumbnail_url( $post, 'atiyeh-thumb' );
		$out[] = array(
			'title' => get_the_title( $post ),
			'date'  => atiyeh_post_date( $post ),
			'img'   => $img ? $img : atiyeh_asset( 'img/' . $fallback_imgs[ $i % 3 ] ),
			'url'   => (string) get_permalink( $post ),
		);
	}
	if ( $out ) {
		return $out;
	}
	$titles = array( 'آغاز پروژه نصب پلاک کدپستی [ شهر ]', 'راه‌اندازی مرکز اسکن اسناد [ سازمان ]', 'پایان بهنگام‌سازی کدپستی [ شهر ]' );
	foreach ( $titles as $i => $title ) {
		$out[] = array( 'title' => $title, 'date' => '[ تاریخ ]', 'img' => atiyeh_asset( 'img/' . $fallback_imgs[ $i ] ), 'url' => '#' );
	}
	return $out;
}

/**
 * Process steps.
 *
 * @return array<int, array{0:string,1:string,2:string}>
 */
function atiyeh_home_steps(): array {
	return array(
		array( '۱', 'ثبت درخواست', 'دریافت نیاز و بررسی اولیه توسط کارشناس' ),
		array( '۲', 'طراحی و برنامه‌ریزی', 'تعیین نیروها، تجهیزات و زمان‌بندی پروژه' ),
		array( '۳', 'اجرا', 'نصب میدانی پلاک یا اسکن عمده اسناد با کنترل کیفیت' ),
		array( '۴', 'گزارش و تحویل', 'تحویل پلاک‌ها و گزارش، یا آرشیو دیجیتال قابل جستجو' ),
	);
}
