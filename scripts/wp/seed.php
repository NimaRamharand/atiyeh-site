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
	$ids = get_posts(
		array(
			'post_type'      => 'any',
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
	// WordPress' own sample content.
	foreach ( array( 'hello-world', 'sample-page', 'privacy-policy' ) as $slug ) {
		$p = get_page_by_path( $slug, OBJECT, array( 'post', 'page' ) );
		if ( $p ) {
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

/** Creates the site pages and returns slug => ID. */
function atiyeh_demo_pages(): array {
	$pages = array(
		'home'     => array( 'خانه', '' ),
		'about'    => array( 'درباره ما', ATIYEH_PLACEHOLDER ),
		'services' => array( 'خدمات', ATIYEH_PLACEHOLDER ),
		'news'     => array( 'اخبار', '' ),
		'careers'  => array( 'همکاری با ما', ATIYEH_PLACEHOLDER ),
		'request'  => array( 'درخواست خدمات', ATIYEH_PLACEHOLDER ),
		'contact'  => array( 'تماس با ما', ATIYEH_PLACEHOLDER ),
		'payment'  => array( 'درگاه پرداخت', ATIYEH_PLACEHOLDER ),
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
	$children = array(
		'company'    => 'معرفی شرکت',
		'management' => 'تیم مدیریت',
		'licenses'   => 'مجوزها و رتبه‌بندی‌ها',
		'org-chart'  => 'چارت سازمانی',
	);
	$order = 0;
	foreach ( $children as $slug => $title ) {
		$ids[ $slug ] = atiyeh_demo_insert(
			array(
				'post_title'   => $title,
				'post_name'    => $slug,
				'post_content' => ATIYEH_PLACEHOLDER,
				'post_parent'  => $ids['about'],
				'menu_order'   => $order++,
			)
		);
	}
	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $ids['home'] );
	update_option( 'page_for_posts', $ids['news'] );
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
		array( 'اخبار', $atiyeh_pages['news'] ),
		array( 'تماس', $atiyeh_pages['contact'] ),
		array( 'درگاه پرداخت', $atiyeh_pages['payment'] ),
	)
);
atiyeh_demo_menu(
	'منوی فوتر',
	'footer',
	array(
		array( 'درباره ما', $atiyeh_pages['about'] ),
		array( 'پروژه‌ها', '/projects/' ),
		array( 'اخبار', $atiyeh_pages['news'] ),
		array( 'درخواست خدمات', $atiyeh_pages['request'] ),
		array( 'همکاری با ما', $atiyeh_pages['careers'] ),
	)
);

// Further phases append their seeders in seed-*.php files.
foreach ( glob( __DIR__ . '/seed-*.php' ) as $atiyeh_seed_file ) {
	require $atiyeh_seed_file;
}

WP_CLI::success( 'محتوای دمو آماده است.' );
