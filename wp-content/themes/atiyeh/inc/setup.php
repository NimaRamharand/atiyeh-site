<?php
/**
 * Theme supports, menus, assets, and offline hardening.
 *
 * @package Atiyeh
 */

add_action(
	'after_setup_theme',
	function (): void {
		load_theme_textdomain( 'atiyeh', ATIYEH_DIR . '/languages' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
		add_theme_support( 'custom-logo', array( 'height' => 84, 'width' => 84, 'flex-width' => true ) );
		add_theme_support( 'editor-styles' );
		register_nav_menus(
			array(
				'primary' => 'منوی اصلی (هدر)',
				'footer'  => 'دسترسی سریع (فوتر)',
			)
		);
		add_image_size( 'atiyeh-card', 760, 600, true );
		add_image_size( 'atiyeh-thumb', 240, 180, true );
	}
);

add_action(
	'wp_enqueue_scripts',
	function (): void {
		$css = ATIYEH_DIR . '/assets/css/main.css';
		$js  = ATIYEH_DIR . '/assets/js/main.js';
		wp_enqueue_style( 'atiyeh', atiyeh_asset( 'css/main.css' ), array(), (string) filemtime( $css ) );
		// Section components (homepage sections, project cards, forms) — reused by inner templates.
		$sections = ATIYEH_DIR . '/assets/css/sections.css';
		wp_enqueue_style( 'atiyeh-sections', atiyeh_asset( 'css/sections.css' ), array( 'atiyeh' ), (string) filemtime( $sections ) );
		wp_enqueue_script( 'atiyeh', atiyeh_asset( 'js/main.js' ), array(), (string) filemtime( $js ), array( 'strategy' => 'defer', 'in_footer' => true ) );
	}
);

// Preload the single variable font file used everywhere.
add_action(
	'wp_head',
	function (): void {
		printf( '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n", esc_url( atiyeh_asset( 'fonts/Vazirmatn-Variable.woff2' ) ) );
	},
	1
);

// Offline demo: nothing may be loaded from a CDN (emoji script pulls from s.w.org).
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );
remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
add_filter( 'emoji_svg_url', '__return_false' );
add_filter(
	'wp_resource_hints',
	function ( array $urls, string $type ): array {
		return 'dns-prefetch' === $type ? array() : $urls;
	},
	10,
	2
);

// Header/footer menu links get the underline-sweep class.
add_filter(
	'nav_menu_link_attributes',
	function ( array $atts, $item, $args ): array {
		if ( in_array( $args->theme_location ?? '', array( 'primary', 'footer' ), true ) ) {
			$atts['class'] = trim( ( $atts['class'] ?? '' ) . ' nav-a' );
		}
		return $atts;
	},
	10,
	3
);

add_filter(
	'body_class',
	function ( array $classes ): array {
		$classes[] = is_front_page() ? 'is-home' : 'is-inner';
		return $classes;
	}
);

add_filter( 'excerpt_length', fn(): int => 26 );
add_filter( 'excerpt_more', fn(): string => '…' );

// Anchor links (e.g. /#products) must never be marked as the current page.
add_filter(
	'nav_menu_css_class',
	function ( array $classes, $item ): array {
		if ( str_contains( (string) $item->url, '#' ) ) {
			$classes = array_diff( $classes, array( 'current-menu-item', 'current_page_item' ) );
		}
		return $classes;
	},
	10,
	2
);
