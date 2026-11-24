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
				'شرکت آمار پژوهان پارسیان با تکیه بر سال‌ها تجربه در اجرای پروژه‌های گوناگون و پشتوانه محکم قانونی، در ارائه خدمات تخصصی <strong>اسکن و آرشیو الکترونیک اسناد</strong>، <strong>بهنگام‌سازی کدپستی و نصب پلاک‌های کدپستی هوشمند</strong> و <strong>آمار و داده</strong> فعالیت می‌کند. این مجموعه دارای گواهینامه صلاحیت شرکت‌های آماری از مرکز آمار ایران، گواهینامه صلاحیت انفورماتیک از سازمان برنامه و بودجه، پروانه فعالیت از سازمان نظام صنفی رایانه‌ای استان اصفهان، گواهینامه صلاحیت ایمنی امور پیمانکاری و گواهینامه رتبه‌بندی خدمات، پشتیبانی و فنی‌مهندسی از اداره کل تعاون، کار و رفاه اجتماعی است. اجرای بخش قابل توجهی از طرح‌های آماری، سرشماری‌ها و پروژه‌های کلان کشور، آمار پژوهان پارسیان را به شریکی قابل اعتماد برای سازمان‌های دولتی و خصوصی تبدیل کرده است.',
				'در حوزه مدیریت اطلاعات، خدمات جامع <strong>اسکن و دیجیتال‌سازی پرونده‌ها</strong> را ارائه می‌کنیم؛ از اسکن و آرشیو الکترونیک پرونده‌های پزشکی در بیمارستان‌ها و مراکز درمانی و دانشگاهی تا مدیریت اسناد حساس سازمانی. همه مراحل با استانداردهای دقیق و به‌روز انجام می‌شود تا اسناد کاغذی به آرشیوی دیجیتال، امن و پایدار تبدیل شوند و دسترسی به اطلاعات سریع‌تر شود.',
				'در پروژه‌های زیرساختی و هوشمندسازی شهری نیز تخصص ویژه‌ای در <strong>پلاک‌گذاری و مدیریت کدپستی</strong> داریم: از طراحی و تولید پلاک‌های کدپستی هوشمند و نصب آن‌ها در مناطق شهری تا بهنگام‌سازی کدپستی، ورود دقیق داده‌ها، ژئوکدسازی و به‌روزرسانی فهرست‌های پستی؛ تا زیرساخت‌های مکانی شهرداری‌ها و شرکت ملی پست دقیق و به‌روز مدیریت شوند.'
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
		'company'    => 'معرفی آمار پژوهان پارسیان: اسکن و آرشیو الکترونیک اسناد، بهنگام‌سازی کدپستی، پلاک کدپستی هوشمند و طرح‌های آماری؛ با گواهینامه مرکز آمار ایران.',
		'management' => 'تیم مدیریت آمار پژوهان پارسیان و راه ارتباط با مدیرعامل شرکت.',
		'licenses'   => 'گواهینامه‌های آمار پژوهان پارسیان: صلاحیت شرکت‌های آماری (مرکز آمار ایران)، صلاحیت انفورماتیک (سازمان برنامه و بودجه)، نظام صنفی رایانه‌ای و اداره کار.',
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

/**
 * Rank Math settings for an offline company site: no account connection, company knowledge graph,
 * breadcrumbs schema, no Article schema on pages, sitemap without empty types. Idempotent.
 */
function atiyeh_demo_seo( array $pages ): void {
	if ( ! defined( 'RANK_MATH_VERSION' ) ) {
		return;
	}
	update_option( 'rank_math_registration_skip', 1 );
	update_option( 'rank_math_wizard_completed', 1 );
	$name   = get_option( 'blogname' );
	$assets = get_template_directory_uri() . '/assets/img/icon/';

	$titles = (array) get_option( 'rank-math-options-titles', array() );
	$titles = array_merge(
		$titles,
		array(
			'knowledgegraph_type'     => 'company',
			'knowledgegraph_name'     => $name,
			'knowledgegraph_logo'     => $assets . 'logo-512.png',
			'website_name'            => $name,
			'website_alternate_name'  => 'A.P.P',
			'title_separator'         => '|',
			'open_graph_image'        => $assets . 'og-image.png',
			'disable_author_archives' => 'on',
		)
	);
	foreach ( array( 'page', 'project', 'appreciation' ) as $type ) {
		$titles[ "pt_{$type}_title" ]                = '%title% %sep% %sitename%';
		$titles[ "pt_{$type}_default_rich_snippet" ] = 'off';
	}
	update_option( 'rank-math-options-titles', $titles );

	$general                = (array) get_option( 'rank-math-options-general', array() );
	$general['breadcrumbs'] = 'on';
	update_option( 'rank-math-options-general', $general );

	$sitemap = (array) get_option( 'rank-math-options-sitemap', array() );
	foreach ( array( 'pt_page_sitemap' => 'on', 'pt_project_sitemap' => 'on', 'pt_appreciation_sitemap' => 'on', 'pt_post_sitemap' => 'off', 'pt_attachment_sitemap' => 'off', 'authors_sitemap' => 'off', 'tax_category_sitemap' => 'off', 'tax_post_tag_sitemap' => 'off', 'tax_service_sitemap' => 'off' ) as $k => $v ) {
		$sitemap[ $k ] = $v;
	}
	update_option( 'rank-math-options-sitemap', $sitemap );

	// Home page title (static front page): brand first, then the main services.
	if ( isset( $pages['home'] ) ) {
		update_post_meta( $pages['home'], 'rank_math_title', '%sitename% %sep% طرح‌های آماری، پلاک کدپستی و آرشیو الکترونیک اسناد' );
	}
	WP_CLI::log( '  تنظیمات Rank Math اعمال شد' );
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
atiyeh_demo_seo( $atiyeh_pages );

atiyeh_demo_menu(
	'منوی اصلی',
	'primary',
	array(
		array( 'درباره ما', $atiyeh_pages['about'] ),
		array( 'خدمات', $atiyeh_pages['services'] ),
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
