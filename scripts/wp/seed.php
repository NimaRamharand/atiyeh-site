<?php
/**
 * Demo content seeder. Run with: wp eval-file scripts/wp/seed.php
 *
 * Everything created here is tagged with the `_atiyeh_demo` meta so `npm run reset-demo`
 * can delete and rebuild it without touching content the company adds by hand.
 * Real company data we do not have yet stays as "[ ... ]" placeholders.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const ATIYEH_DEMO_META = '_atiyeh_demo';
const ATIYEH_PLACEHOLDER = '<!-- wp:paragraph --><p>[ محتوای این صفحه توسط شرکت تکمیل می‌شود. ]</p><!-- /wp:paragraph -->';

/** Deletes every demo-tagged post (any type, any status) and its attachments. */
function atiyeh_demo_purge(): void {
	// Explicit type list: 'any' skips non-public types such as client and job.
	$ids = get_posts(
		array(
			'post_type'      => array_values( array_diff( get_post_types(), array( 'attachment' ) ) ),
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'meta_key'       => ATIYEH_DEMO_META,
		)
	);
	$attachments = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'meta_key'       => ATIYEH_DEMO_META,
		)
	);
	foreach ( array_merge( $ids, $attachments ) as $id ) {
		wp_delete_post( $id, true );
	}
	// WordPress' own sample content (English or Persian install; slugs differ, titles are fixed).
	$defaults = array( 'Hello world!', 'Sample Page', 'Privacy Policy', 'سلام دنیا!', 'برگه نمونه', 'سیاست حفظ حریم خصوصی' );
	foreach ( array( 1, 2, 3 ) as $default_id ) {
		$p = get_post( $default_id );
		if ( $p && ! get_post_meta( $p->ID, ATIYEH_DEMO_META, true ) && in_array( $p->post_title, $defaults, true ) ) {
			wp_delete_post( $p->ID, true );
		}
	}
	foreach ( array( 'منوی اصلی', 'منوی فوتر' ) as $menu ) {
		if ( wp_get_nav_menu_object( $menu ) ) {
			wp_delete_nav_menu( $menu );
		}
	}
	WP_CLI::log( sprintf( '  حذف %d مورد دمو', count( $ids ) + count( $attachments ) ) );
}

/** Inserts a demo-tagged post and returns its ID. */
function atiyeh_demo_insert( array $args, array $meta = array() ): int {
	$args = wp_parse_args( $args, array( 'post_status' => 'publish', 'post_type' => 'page' ) );
	$id   = wp_insert_post( wp_slash( $args ), true );
	if ( is_wp_error( $id ) ) {
		WP_CLI::error( $id->get_error_message() );
	}
	update_post_meta( $id, ATIYEH_DEMO_META, 1 );
	foreach ( $meta as $k => $v ) {
		update_post_meta( $id, $k, $v );
	}
	return (int) $id;
}

/**
 * Imports an image from assets-source/images into the media library (once per run) and returns its ID.
 *
 * @param string $file File name, e.g. «project-postal-plaque.webp».
 * @param string $alt  Alt text.
 */
function atiyeh_demo_image( string $file, string $alt = '' ): int {
	static $cache = array();
	if ( isset( $cache[ $file ] ) ) {
		return $cache[ $file ];
	}
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';
	$dir = getenv( 'ASSETS_DIR' ) ? getenv( 'ASSETS_DIR' ) : dirname( __DIR__, 2 ) . '/assets-source';
	$src = rtrim( $dir, '/' ) . '/' . ( str_contains( $file, '/' ) ? $file : 'images/' . $file );
	if ( ! is_readable( $src ) ) {
		WP_CLI::warning( "تصویر پیدا نشد: {$src}" );
		return 0;
	}
	$tmp = wp_tempnam( basename( $file ) );
	copy( $src, $tmp );
	$id = media_handle_sideload( array( 'name' => basename( $file ), 'tmp_name' => $tmp ), 0 );
	if ( is_wp_error( $id ) ) {
		WP_CLI::warning( $id->get_error_message() );
		return 0;
	}
	update_post_meta( $id, ATIYEH_DEMO_META, 1 );
	if ( $alt ) {
		update_post_meta( $id, '_wp_attachment_image_alt', $alt );
	}
	return $cache[ $file ] = (int) $id;
}

/** Wraps plain paragraphs as block-editor paragraphs. */
function atiyeh_demo_paras( string ...$paras ): string {
	return implode( "\n\n", array_map( static fn( string $p ): string => "<!-- wp:paragraph --><p>{$p}</p><!-- /wp:paragraph -->", $paras ) );
}

/** Creates the site pages and returns slug => ID. Intros are sample copy; company facts stay «[ … ]». */
function atiyeh_demo_pages(): array {
	$pages = array(
		'home'     => array( 'خانه', '' ),
		'about'    => array(
			'درباره ما',
			atiyeh_demo_paras(
				'شرکت آمار پژوهان پارسیان (شماره ثبت ۵۰۴۱۱) از سال ۱۳۸۸ در اجرای کامل طرح‌های آماری فعالیت می‌کند: عملیات میدانی و آمارگیری، بازبینی، استخراج، اسکن و داده‌آمایی. اجرای بیش از ۸۰ درصد طرح‌های آماری و سرشماری مرکز آمار ایران در سال‌های ۱۳۸۸ تا ۱۳۹۳ بخشی از این سابقه است.',
				'از سال ۱۳۹۳ طرح‌های فهرست‌برداری و بهنگام‌سازی کدپستی ده‌رقمی اداره کل پست استان اصفهان را اجرا می‌کنیم، برای شهرداری‌ها پلاک کدپستی می‌سازیم و نصب می‌کنیم و برای بیمارستان‌ها آرشیو الکترونیک اسناد پزشکی تهیه می‌کنیم.'
			),
		),
		'services' => array( 'خدمات', '' ),
		'careers'  => array( 'همکاری با ما', atiyeh_demo_paras( 'اگر به کار میدانی، دقت در جزئیات و کار با اسناد علاقه دارید، خوشحال می‌شویم رزومه شما را ببینیم. موقعیت‌های فعال در ادامه آمده‌اند و برای فرصت‌های آینده نیز می‌توانید رزومه بفرستید.' ) ),
		'request'  => array( 'درخواست خدمات', '' ),
		'clients'  => array( 'کارفرمایان', '' ),
		'contact'  => array( 'تماس با ما', '' ),
	);
	$about_children = array(
		'company'    => array(
			'معرفی شرکت',
			atiyeh_demo_paras(
				'آمار پژوهان پارسیان از سال ۱۳۸۸ با اجرای طرح‌های آماری مرکز آمار ایران کار خود را آغاز کرد و امروز دارای گواهی صلاحیت خدمات مشاوره آماری (پایه ۲) از مرکز آمار ایران و گواهینامه رتبه‌بندی شرکت‌های انفورماتیکی (پایه ۴) است.',
				'برای شرکت ملی پست، طرح‌های فهرست‌برداری، بهنگام‌سازی، ورود اطلاعات و ژئوکدسازی کدپستی ده‌رقمی استان اصفهان را اجرا کرده‌ایم و برای شهرداری‌هایی چون اصفهان، نطنز، مبارکه، بهارستان، افوس و اشترجان پلاک کدپستی ساخته و نصب کرده‌ایم.',
				'در حوزه آرشیو الکترونیک نیز اسناد پزشکی بیمارستان‌هایی چون الزهرا (س)، امام حسین (ع)، امین، شهید بهشتی و عیسی بن مریم (ع) را اسکن و در نرم‌افزارهای بایگانی ثبت کرده‌ایم.'
			),
		),
		'management' => array( 'تیم مدیریت', atiyeh_demo_paras( 'مدیران مجموعه، برنامه‌ریزی پروژه‌ها، هماهنگی تیم‌های اجرایی و نظارت بر کیفیت خدمات را بر عهده دارند. معرفی اعضای تیم مدیریت پس از دریافت اطلاعات تکمیل می‌شود.' ) ),
		'licenses'   => array( 'مجوزها و رتبه‌بندی‌ها', atiyeh_demo_paras( 'مجوزها، گواهینامه‌ها و رتبه‌بندی‌های شرکت پشتوانه کیفیت خدمات ما هستند. تصاویر و مشخصات هر مجوز پس از دریافت از شرکت در این صفحه قرار می‌گیرد.' ) ),
		'org-chart'  => array( 'چارت سازمانی', atiyeh_demo_paras( 'ساختار سازمانی ما بر پایه دو واحد تخصصی پلاک کدپستی و آرشیو دیجیتال، با پشتیبانی واحدهای کنترل کیفیت و اداری شکل گرفته است.' ) ),
	);
	$ids   = array();
	$order = 0;
	foreach ( $pages as $slug => [ $title, $content ] ) {
		$ids[ $slug ] = atiyeh_demo_insert(
			array(
				'post_title'   => $title,
				'post_name'    => $slug,
				'post_content' => $content,
				'menu_order'   => $order++,
			)
		);
	}
	$order = 0;
	foreach ( $about_children as $slug => [ $title, $content ] ) {
		$ids[ $slug ] = atiyeh_demo_insert(
			array(
				'post_title'   => $title,
				'post_name'    => $slug,
				'post_content' => $content,
				'post_parent'  => $ids['about'],
				'menu_order'   => $order++,
			)
		);
	}
	// Meta descriptions (Rank Math reads `rank_math_description`): one clear sentence per page, ~150 characters.
	$descriptions = array(
		'home'       => 'آمار پژوهان پارسیان، اصفهان: طرح‌های آماری، بهنگام‌سازی کدپستی، ساخت و نصب پلاک کدپستی، آرشیو الکترونیک و اسکن مدارک پزشکی بیمارستان.',
		'about'      => 'آشنایی با آمار پژوهان پارسیان؛ فعال از ۱۳۸۸ در طرح‌های آماری، کدپستی، پلاک و آرشیو الکترونیک اسناد، با گواهی صلاحیت از مرکز آمار ایران.',
		'services'   => 'خدمات آمار پژوهان پارسیان: ساخت و نصب پلاک کدپستی، آرشیو الکترونیک اسناد پزشکی، آمارگیری و داده‌آمایی، مشاوره خرید اسکنر و خدمات انفورماتیک.',
		'careers'    => 'فرصت‌های شغلی آمار پژوهان پارسیان در اصفهان: اپراتور اسکن، همگام‌ساز و نصاب پلاک کدپستی. رزومه خود را آنلاین ارسال کنید.',
		'request'    => 'درخواست خدمات پلاک کدپستی، اسکن و آرشیو الکترونیک اسناد، طرح‌های آماری یا مشاوره خرید اسکنر را ثبت کنید تا کارشناسان ما تماس بگیرند.',
		'clients'    => 'کارفرمایان آمار پژوهان پارسیان: شرکت ملی پست، مرکز آمار ایران، شهرداری‌ها و بیمارستان‌های اصفهان، با فیلتر بر اساس نوع خدمت.',
		'contact'    => 'نشانی، تلفن و ایمیل آمار پژوهان پارسیان در اصفهان، چهارراه جهاد، ابتدای بلوار آتشگاه. فرم تماس آنلاین.',
		'company'    => 'معرفی شرکت آمار پژوهان پارسیان (شماره ثبت ۵۰۴۱۱)؛ سابقه از ۱۳۸۸ در طرح‌های آماری، کدپستی، پلاک و آرشیو الکترونیک.',
		'management' => 'تیم مدیریت آمار پژوهان پارسیان و راه ارتباط با مدیرعامل شرکت.',
		'licenses'   => 'مجوزها و گواهینامه‌های آمار پژوهان پارسیان: صلاحیت مشاوره آماری مرکز آمار ایران، رتبه‌بندی انفورماتیکی، نظام صنفی رایانه‌ای و مجوزهای اداره کار.',
		'org-chart'  => 'چارت سازمانی آمار پژوهان پارسیان و واحدهای پلاک کدپستی، آرشیو الکترونیک، کنترل کیفیت و اداری.',
	);
	foreach ( $descriptions as $slug => $desc ) {
		if ( isset( $ids[ $slug ] ) ) {
			update_post_meta( $ids[ $slug ], 'rank_math_description', $desc );
		}
	}
	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $ids['home'] );
	update_option( 'page_for_posts', 0 );
	WP_CLI::log( sprintf( '  %d صفحه ساخته شد', count( $ids ) ) );
	return $ids;
}

/** Builds a nav menu from [title, url|page_id] rows and assigns it to a theme location. */
function atiyeh_demo_menu( string $name, string $location, array $items ): void {
	$menu_id = wp_create_nav_menu( $name );
	foreach ( $items as $i => [ $title, $target ] ) {
		$args = array(
			'menu-item-title'    => $title,
			'menu-item-status'   => 'publish',
			'menu-item-position' => $i + 1,
		);
		if ( is_int( $target ) ) {
			$args += array(
				'menu-item-object-id' => $target,
				'menu-item-object'    => 'page',
				'menu-item-type'      => 'post_type',
			);
		} else {
			$args += array(
				'menu-item-url'  => home_url( $target ),
				'menu-item-type' => 'custom',
			);
		}
		wp_update_nav_menu_item( $menu_id, 0, $args );
	}
	$locations              = get_theme_mod( 'nav_menu_locations', array() );
	$locations[ $location ] = $menu_id;
	set_theme_mod( 'nav_menu_locations', $locations );
}

atiyeh_demo_purge();
update_option( 'blogname', 'آمار پژوهان پارسیان' );
$atiyeh_pages = atiyeh_demo_pages();

atiyeh_demo_menu(
	'منوی اصلی',
	'primary',
	array(
		array( 'درباره ما', $atiyeh_pages['about'] ),
		array( 'خدمات', $atiyeh_pages['services'] ),
		array( 'خدمات اصلی', '/#products' ),
		array( 'پروژه‌ها', '/projects/' ),
		array( 'حسن انجام کار', '/appreciations/' ),
		array( 'همکاری با ما', $atiyeh_pages['careers'] ),
		array( 'تماس', $atiyeh_pages['contact'] ),
	)
);
atiyeh_demo_menu(
	'منوی فوتر',
	'footer',
	array(
		array( 'درباره ما', $atiyeh_pages['about'] ),
		array( 'پروژه‌ها', '/projects/' ),
		array( 'کارفرمایان', $atiyeh_pages['clients'] ),
		array( 'حسن انجام کار', '/appreciations/' ),
		array( 'درخواست خدمات', $atiyeh_pages['request'] ),
		array( 'همکاری با ما', $atiyeh_pages['careers'] ),
	)
);

// Further phases append their seeders in seed-*.php files.
foreach ( glob( __DIR__ . '/seed-*.php' ) as $atiyeh_seed_file ) {
	require $atiyeh_seed_file;
}

WP_CLI::success( 'محتوای دمو آماده است.' );
