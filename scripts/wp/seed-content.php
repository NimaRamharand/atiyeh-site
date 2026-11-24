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

// Projects from the company profile (رزومه شرکت) and the contracts in progress (Aban 1405).
// [ title, services, image, client, city, date, status, excerpt ]
$atiyeh_projects = array(
	array( 'ساخت و نصب پلاک کدپستی مناطق ۲ و ۶ اصفهان', array( 'plaque' ), 'smart-qr-plaque-install.webp', 'سازمان فاوا شهرداری اصفهان', 'اصفهان', '۱۴۰۳', 'ongoing', 'ساخت و نصب پلاک‌های کدپستی (پلاک هوشمند) در مناطق ۲ و ۶ شهر اصفهان؛ در مراحل پایانی.' ),
	array( 'آرشیو الکترونیک اسناد پزشکی بیمارستان الزهرا (س)', array( 'archive' ), 'scan-medical-records-operator.webp', 'بیمارستان الزهرا (س)', 'اصفهان', '', 'ongoing', 'اسکن و تهیه آرشیو الکترونیک پرونده‌های پزشکی؛ مرحله نخست ۱۳۹۹ تا ۱۴۰۱ با نرم‌افزار HIS و HIT.' ),
	array( 'طرح‌های آماری مرکز آمار ایران', array( 'stats' ), 'project-map-update.webp', 'مرکز آمار ایران · سازمان مدیریت و برنامه‌ریزی استان اصفهان', 'استان اصفهان', '۱۳۸۸–۱۳۹۶', 'done', 'استخراج و ورود اطلاعات بیش از ۴۰ طرح آماری، از قیمت تولیدکننده و هزینه و درآمد خانوار تا شاخص‌های ICT، با نرم‌افزار amar org.' ),
	array( 'آرشیو الکترونیک اسناد پزشکی بیمارستان شهید بهشتی', array( 'archive' ), 'scan-medical-records-operator.webp', 'بیمارستان شهید بهشتی اصفهان', 'اصفهان', '۱۴۰۴–۱۴۰۵', 'ongoing', 'اسکن مدارک پزشکی و تهیه آرشیو الکترونیک با نرم‌افزار سبارا.' ),
	array( 'آرشیو الکترونیک اسناد پزشکی بیمارستان عیسی بن مریم (ع)', array( 'archive' ), 'scan-medical-records-operator.webp', 'بیمارستان عیسی بن مریم (ع)', 'اصفهان', '۱۴۰۵', 'ongoing', 'اسکن مدارک پزشکی و تهیه آرشیو الکترونیک با نرم‌افزار سبارا.' ),
	array( 'ساخت و نصب پلاک کدپستی شهر دیزیچه', array( 'plaque' ), 'plaque-install-officials.webp', 'شهرداری دیزیچه', 'دیزیچه', '', 'ongoing', 'ساخت و نصب پلاک کدپستی ساختمان‌های شهر دیزیچه.' ),
	array( 'ساخت و نصب پلاک کدپستی شهر فلاورجان', array( 'plaque' ), 'plaque-site-inspection.webp', 'شهرداری فلاورجان', 'فلاورجان', '', 'ongoing', 'ساخت و نصب پلاک کدپستی ساختمان‌های شهر فلاورجان.' ),
	array( 'ساخت و نصب پلاک‌های منطقه ۱۴ اصفهان', array( 'plaque' ), 'plaque-install-gate.webp', 'سازمان فاوا شهرداری اصفهان', 'اصفهان', '۱۴۰۲', 'done', 'ساخت و نصب پلاک‌های بارکددار کدپستی ده‌رقمی منطقه ۱۴ و ثبت اطلاعات در نرم‌افزار Postcod.' ),
	array( 'ساخت و نصب پلاک شهر افوس', array( 'plaque' ), 'smart-qr-plaque-install.webp', 'شهرداری افوس', 'افوس', '۱۴۰۲–۱۴۰۳', 'done', 'ساخت و نصب پلاک کدپستی ساختمان‌های شهر افوس.' ),
	array( 'ساخت و نصب پلاک شهر اشترجان', array( 'plaque' ), 'plaque-install-officials.webp', 'شهرداری اشترجان', 'اشترجان', '۱۴۰۲–۱۴۰۳', 'done', 'ساخت و نصب پلاک کدپستی ساختمان‌های شهر اشترجان.' ),
	array( 'بهنگام‌سازی، ساخت و نصب پلاک کدپستی شهر بهارستان', array( 'plaque', 'update' ), 'project-map-update.webp', 'شهرداری بهارستان', 'بهارستان', '۱۳۹۹', 'done', 'بهنگام‌سازی اطلاعات کدپستی ده‌رقمی و ساخت و نصب پلاک ساختمان‌های شهر بهارستان.' ),
	array( 'بهنگام‌سازی کدپستی ده‌رقمی کلان‌شهر اصفهان', array( 'update' ), 'project-map-update.webp', 'شرکت ملی پست جمهوری اسلامی ایران', 'اصفهان', '۱۳۹۴', 'done', 'برداشت میدانی و بهنگام‌سازی اطلاعات کدپستی ده‌رقمی کلان‌شهر اصفهان.' ),
	array( 'ژئوکدسازی اطلاعات کدپستی استان اصفهان', array( 'stats', 'update' ), 'project-map-update.webp', 'شرکت ملی پست جمهوری اسلامی ایران', 'استان اصفهان', '۱۳۹۹–۱۴۰۰', 'done', 'ژئوکدسازی اطلاعات کدپستی ده‌رقمی استان اصفهان در نرم‌افزار Postcod.' ),
	array( 'آمارگیری وضعیت کارگاه‌های صنعتی استان اصفهان', array( 'stats' ), 'project-map-update.webp', 'شرکت شهرک‌های صنعتی استان اصفهان', 'استان اصفهان', '۱۴۰۱–۱۴۰۲', 'done', 'طرح آمارگیری وضعیت فیزیکی کارگاه‌های صنعتی و کارگاه‌های زیر ظرفیت اسمی استان.' ),
	array( 'آرشیو الکترونیک اسناد پزشکی بیمارستان امام حسین (ع)', array( 'archive' ), 'scan-medical-records-operator.webp', 'بیمارستان امام حسین (ع)', 'اصفهان', '۱۴۰۳–۱۴۰۴', 'done', 'اسکن مدارک پزشکی و تهیه آرشیو الکترونیک با نرم‌افزار سبارا.' ),
	array( 'آرشیو الکترونیک اسناد پزشکی بیمارستان امین', array( 'archive' ), 'scan-medical-records-operator.webp', 'بیمارستان امین', 'اصفهان', '۱۴۰۳–۱۴۰۴', 'done', 'اسکن مدارک پزشکی و تهیه آرشیو الکترونیک با نرم‌افزار سبارا.' ),
);
// Alt text for the company's own photos (Aban 1405).
$atiyeh_alts = array(
	'smart-qr-plaque-install.webp'       => 'نصب پلاک کدپستی هوشمند با کد QR توسط نیروی آمار پژوهان پارسیان',
	'plaque-install-gate.webp'           => 'نصب پلاک کدپستی بر سر در ساختمان',
	'plaque-install-officials.webp'      => 'نصب پلاک کدپستی با حضور ناظران پروژه',
	'plaque-site-inspection.webp'        => 'بازدید میدانی از پروژه نصب پلاک کدپستی',
	'scan-medical-records-operator.webp' => 'اپراتور در حال اسکن پرونده‌های پزشکی بیمارستان',
	'project-map-update.webp'            => 'برداشت میدانی اطلاعات کدپستی',
);
foreach ( $atiyeh_projects as $atiyeh_i => [ $atiyeh_title, $atiyeh_services, $atiyeh_img, $atiyeh_client, $atiyeh_city, $atiyeh_date, $atiyeh_status, $atiyeh_excerpt ] ) {
	$atiyeh_id = atiyeh_demo_insert(
		array(
			'post_type'    => 'project',
			'post_title'   => $atiyeh_title,
			'post_excerpt' => $atiyeh_excerpt,
			'post_content' => atiyeh_demo_paras( $atiyeh_excerpt, 'کارفرما: ' . $atiyeh_client . ( 'ongoing' === $atiyeh_status ? '. این پروژه در حال اجراست.' : '.' ) ),
			'menu_order'   => $atiyeh_i,
		),
		array( '_atiyeh_client' => $atiyeh_client, '_atiyeh_city' => $atiyeh_city, '_atiyeh_date' => $atiyeh_date, '_atiyeh_status' => $atiyeh_status )
	);
	wp_set_object_terms( $atiyeh_id, $atiyeh_services, 'service' );
	$atiyeh_thumb = atiyeh_demo_image( $atiyeh_img, $atiyeh_alts[ $atiyeh_img ] ?? '' );
	if ( $atiyeh_thumb ) {
		set_post_thumbnail( $atiyeh_id, $atiyeh_thumb );
	}
}

// Clients from the company profile. [ name, logo file or '', services, city ].
$atiyeh_clients = array(
	array( 'شهرداری اصفهان (سازمان فاوا)', 'logo-esfahan.webp', array( 'plaque' ), 'اصفهان' ),
	array( 'شهرداری افوس', 'logo-afus.webp', array( 'plaque' ), 'افوس' ),
	array( 'شرکت ملی پست جمهوری اسلامی ایران', '', array( 'update', 'stats' ), 'استان اصفهان' ),
	array( 'مرکز آمار ایران', '', array( 'stats' ), '' ),
	array( 'بیمارستان الزهرا (س)', '', array( 'archive' ), 'اصفهان' ),
	array( 'بیمارستان شهید بهشتی', '', array( 'archive' ), 'اصفهان' ),
	array( 'بیمارستان عیسی بن مریم (ع)', '', array( 'archive' ), 'اصفهان' ),
	array( 'سازمان مدیریت و برنامه‌ریزی استان اصفهان', '', array( 'stats' ), 'اصفهان' ),
	array( 'شرکت شهرک‌های صنعتی استان اصفهان', '', array( 'stats' ), 'اصفهان' ),
	array( 'بیمارستان امام حسین (ع)', '', array( 'archive' ), 'اصفهان' ),
	array( 'بیمارستان امین', '', array( 'archive' ), 'اصفهان' ),
	array( 'بیمارستان سیدالشهدا (مرکز امید)', '', array( 'archive' ), 'اصفهان' ),
	array( 'مرکز بیمارستان خورشید', '', array( 'archive' ), 'اصفهان' ),
	array( 'معاونت درمان دانشگاه علوم پزشکی اصفهان', '', array( 'archive' ), 'اصفهان' ),
	array( 'کلینیک چشم‌پزشکی آبان', '', array( 'archive' ), 'اصفهان' ),
	array( 'شهرداری نطنز', '', array( 'plaque' ), 'نطنز' ),
	array( 'شهرداری مبارکه', '', array( 'plaque' ), 'مبارکه' ),
	array( 'شهرداری بهارستان', '', array( 'plaque' ), 'بهارستان' ),
	array( 'شهرداری اشترجان', '', array( 'plaque' ), 'اشترجان' ),
	array( 'شهرداری دیزیچه', '', array( 'plaque' ), 'دیزیچه' ),
	array( 'شهرداری فلاورجان', '', array( 'plaque' ), 'فلاورجان' ),
);
foreach ( $atiyeh_clients as $atiyeh_i => [ $atiyeh_title, $atiyeh_logo, $atiyeh_services, $atiyeh_city ] ) {
	$atiyeh_id = atiyeh_demo_insert( array( 'post_type' => 'client', 'post_title' => $atiyeh_title, 'menu_order' => $atiyeh_i ), array( '_atiyeh_city' => $atiyeh_city, '_atiyeh_url' => '' ) );
	if ( $atiyeh_services ) {
		wp_set_object_terms( $atiyeh_id, $atiyeh_services, 'service' );
	}
	if ( $atiyeh_logo ) {
		$atiyeh_logo_id = atiyeh_demo_image( 'logos/' . $atiyeh_logo, 'لوگوی ' . $atiyeh_title );
		if ( $atiyeh_logo_id ) {
			set_post_thumbnail( $atiyeh_id, $atiyeh_logo_id );
		}
	}
}

// Homepage stats (counted from the company profile + contracts in progress).
foreach ( array( 'years' => '۱۷', 'stats' => '۴۲', 'archive' => '۹', 'plaques' => '۱۱' ) as $atiyeh_k => $atiyeh_v ) {
	set_theme_mod( "atiyeh_stat_{$atiyeh_k}", $atiyeh_v );
}

// Company profile PDF (phone numbers of hospital staff removed). Only replaces a demo file, never the operator's own.
$atiyeh_pdf_old = (int) get_theme_mod( 'atiyeh_profile_pdf', 0 );
if ( ! $atiyeh_pdf_old || ! get_post( $atiyeh_pdf_old ) || get_post_meta( $atiyeh_pdf_old, ATIYEH_DEMO_META, true ) ) {
	$atiyeh_pdf = atiyeh_demo_image( 'files/amar-parsian-profile.pdf', 'رزومه شرکت آمار پژوهان پارسیان' );
	if ( $atiyeh_pdf ) {
		wp_update_post( array( 'ID' => $atiyeh_pdf, 'post_title' => 'رزومه شرکت آمار پژوهان پارسیان' ) );
		set_theme_mod( 'atiyeh_profile_pdf', $atiyeh_pdf );
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

// Jobs: the role chips of the careers banner (list revised after the demo meeting).
foreach ( array( 'اپراتور اسکن', 'همگام‌ساز', 'نصاب پلاک کدپستی' ) as $atiyeh_i => $atiyeh_title ) {
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

// Occasion slides: one current sample (first hero slide) and one past sample that shows as «آرشیو».
if ( post_type_exists( 'occasion' ) ) {
	$atiyeh_occasions = array(
		array( 'نمونه اسلاید آرشیوشده', 'رویداد', 'این اسلاید نمونه‌ای از موارد قبلی است که پس از پایان تاریخ نمایش در آرشیو می‌ماند.', '۱۴۰۵/۰۷/۰۱', '۱۴۰۵/۰۷/۰۷', 'slate', '-30 days' ),
		array( 'روز آمار و برنامه‌ریزی گرامی باد', 'مناسبت', 'روز آمار و برنامه‌ریزی را به همه فعالان این حوزه و همکاران سازمان‌های همراه تبریک می‌گوییم.', '', '', 'blue', 'now' ),
	);
	foreach ( $atiyeh_occasions as [ $atiyeh_t, $atiyeh_k, $atiyeh_d, $atiyeh_s, $atiyeh_e, $atiyeh_theme, $atiyeh_when ] ) {
		$atiyeh_id = atiyeh_demo_insert(
			array( 'post_type' => 'occasion', 'post_title' => $atiyeh_t, 'post_date' => ( new DateTimeImmutable( $atiyeh_when, wp_timezone() ) )->format( 'Y-m-d H:i:s' ) ),
			array(
				'_atiyeh_kicker'   => $atiyeh_k,
				'_atiyeh_desc'     => $atiyeh_d,
				'_atiyeh_btn_text' => '',
				'_atiyeh_btn_url'  => '',
				'_atiyeh_theme'    => $atiyeh_theme,
				'_atiyeh_start'    => $atiyeh_s,
				'_atiyeh_start_g'  => '' === $atiyeh_s ? '' : (string) atiyeh_core_parse_jdate( $atiyeh_s ),
				'_atiyeh_end'      => $atiyeh_e,
				'_atiyeh_end_g'    => '' === $atiyeh_e ? '' : (string) atiyeh_core_parse_jdate( $atiyeh_e ),
			)
		);
		$atiyeh_thumb = atiyeh_demo_image( 'hero-1-green-city.webp', '' );
		if ( $atiyeh_thumb ) {
			set_post_thumbnail( $atiyeh_id, $atiyeh_thumb );
		}
	}
}

// Forms (created once, updated in place on every reset so entries are kept).
$atiyeh_forms = atiyeh_core_ensure_forms();
WP_CLI::log( '  ' . count( $atiyeh_projects ) . ' پروژه، ' . count( $atiyeh_clients ) . ' کارفرما، ۱ لوح سپاس، ۳ فرصت شغلی، ۲ اسلاید مناسبتی' . ( $atiyeh_forms ? '، فرم‌ها: ' . implode( '، ', array_map( static fn( $k, $v ) => "{$k}=#{$v}", array_keys( $atiyeh_forms ), $atiyeh_forms ) ) : '' ) );
