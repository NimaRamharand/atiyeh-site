<?php
/**
 * «اسلاید مناسبتی»: an operator-managed first hero slide (statistics week, occasions, events, important news).
 *
 * Rules:
 * - The slide shown is the newest published one whose date range includes today.
 * - Every other slide stays in the list as «آرشیو» (nothing is deleted) and can be reused by
 *   changing its dates or publishing it again.
 * - With no current slide the hero shows only the regular service slides.
 *
 * @package AtiyehCore
 */

/** Background presets the operator can choose from: key => label + colours. */
function atiyeh_core_occasion_themes(): array {
	return array(
		'blue'  => array( 'label' => 'آبی (پیش‌فرض)', 'bg' => 'linear-gradient(135deg,#0f3f8a,#1757b8)', 'c1' => '#2a6fd6', 'c2' => '#7ab8ff' ),
		'navy'  => array( 'label' => 'سرمه‌ای', 'bg' => 'linear-gradient(135deg,#0a2250,#134aa0)', 'c1' => '#1757b8', 'c2' => '#cfe3ff' ),
		'green' => array( 'label' => 'سبز (اعیاد و مناسبت‌های ملی)', 'bg' => 'linear-gradient(135deg,#0d4a3a,#16785c)', 'c1' => '#1f8f6d', 'c2' => '#9de0c6' ),
		'slate' => array( 'label' => 'طوسی تیره', 'bg' => 'linear-gradient(135deg,#1f262b,#34404a)', 'c1' => '#134aa0', 'c2' => '#7ab8ff' ),
		'black' => array( 'label' => 'مشکی (مناسبت‌های سوگواری)', 'bg' => 'linear-gradient(135deg,#0b0d10,#22272c)', 'c1' => '#3a434b', 'c2' => '#c9d1cd' ),
	);
}

add_action(
	'init',
	static function (): void {
		register_post_type(
			'occasion',
			array(
				'labels'             => array(
					'name'               => 'اسلایدهای مناسبتی',
					'singular_name'      => 'اسلاید مناسبتی',
					'menu_name'          => 'اسلاید مناسبتی',
					'add_new'            => 'افزودن اسلاید',
					'add_new_item'       => 'افزودن اسلاید مناسبتی',
					'edit_item'          => 'ویرایش اسلاید مناسبتی',
					'all_items'          => 'همه اسلایدها (فعال و آرشیو)',
					'search_items'       => 'جستجوی اسلایدها',
					'not_found'          => 'اسلایدی یافت نشد',
					'featured_image'     => 'تصویر اسلاید',
					'set_featured_image' => 'انتخاب تصویر اسلاید',
				),
				'description'        => 'اسلاید اول صفحه اصلی برای مناسبت‌ها، رویدادها و اخبار مهم.',
				'public'             => false,
				'show_ui'            => true,
				'show_in_rest'       => false, // Classic edit screen: title, image and the fields box.
				'publicly_queryable' => false,
				'menu_icon'          => 'dashicons-megaphone',
				'menu_position'      => 20,
				'supports'           => array( 'title', 'thumbnail' ),
			)
		);
	}
);

/** Whether an occasion's date range includes $today (Y-m-d). Invalid dates never show. */
function atiyeh_core_occasion_in_range( int $id, string $today ): bool {
	$start = (string) get_post_meta( $id, '_atiyeh_start_g', true );
	$end   = (string) get_post_meta( $id, '_atiyeh_end_g', true );
	if ( 'invalid' === $start || 'invalid' === $end ) {
		return false;
	}
	return ( '' === $start || $start <= $today ) && ( '' === $end || $end >= $today );
}

/** The occasion slide to show today, or null. */
function atiyeh_core_current_occasion(): ?WP_Post {
	static $cache = false;
	if ( false !== $cache ) {
		return $cache;
	}
	$today = atiyeh_core_today();
	$cache = null;
	foreach ( get_posts( array( 'post_type' => 'occasion', 'post_status' => 'publish', 'numberposts' => 50, 'orderby' => 'date', 'order' => 'DESC' ) ) as $p ) {
		if ( atiyeh_core_occasion_in_range( $p->ID, $today ) ) {
			$cache = $p;
			break;
		}
	}
	return $cache;
}

/**
 * Slide data for the theme's hero slider, or null.
 *
 * @return array<string, mixed>|null
 */
function atiyeh_core_occasion_slide(): ?array {
	$p = atiyeh_core_current_occasion();
	if ( ! $p ) {
		return null;
	}
	$themes = atiyeh_core_occasion_themes();
	$theme  = $themes[ atiyeh_core_meta( $p->ID, '_atiyeh_theme' ) ] ?? $themes['blue'];
	$kicker = atiyeh_core_meta( $p->ID, '_atiyeh_kicker' );
	return array(
		'occasion' => true,
		'kicker'   => '' !== $kicker ? $kicker : 'مناسبت',
		'title'    => get_the_title( $p ),
		'desc'     => atiyeh_core_meta( $p->ID, '_atiyeh_desc' ),
		'cta'      => atiyeh_core_meta( $p->ID, '_atiyeh_btn_text' ),
		'href'     => atiyeh_core_meta( $p->ID, '_atiyeh_btn_url' ),
		'img_url'  => (string) get_the_post_thumbnail_url( $p, 'large' ),
		'bg'       => $theme['bg'],
		'c1'       => $theme['c1'],
		'c2'       => $theme['c2'],
	);
}

// Admin list: status (showing / scheduled / archive), dates, image.
add_filter(
	'manage_occasion_posts_columns',
	static fn( array $c ): array => array(
		'cb'              => $c['cb'],
		'atiyeh_thumb'    => 'تصویر',
		'title'           => $c['title'],
		'atiyeh_status'   => 'وضعیت',
		'atiyeh_range'    => 'بازه نمایش',
		'date'            => $c['date'],
	)
);
add_action(
	'manage_occasion_posts_custom_column',
	static function ( string $col, int $id ): void {
		if ( 'atiyeh_thumb' === $col ) {
			echo get_the_post_thumbnail( $id, array( 90, 60 ), array( 'style' => 'border-radius:8px;object-fit:cover' ) );
			return;
		}
		if ( 'atiyeh_range' === $col ) {
			$s = atiyeh_core_meta( $id, '_atiyeh_start' );
			$e = atiyeh_core_meta( $id, '_atiyeh_end' );
			echo esc_html( ( '' !== $s ? $s : 'از انتشار' ) . ' ← ' . ( '' !== $e ? $e : 'بدون پایان' ) );
			return;
		}
		if ( 'atiyeh_status' !== $col ) {
			return;
		}
		$start = (string) get_post_meta( $id, '_atiyeh_start_g', true );
		$end   = (string) get_post_meta( $id, '_atiyeh_end_g', true );
		$cur   = atiyeh_core_current_occasion();
		if ( 'invalid' === $start || 'invalid' === $end ) {
			echo '<span style="color:#b32d2e;font-weight:700">تاریخ نامعتبر</span>';
		} elseif ( 'publish' !== get_post_status( $id ) ) {
			echo '<span style="color:#646970">پیش‌نویس</span>';
		} elseif ( $cur && $cur->ID === $id ) {
			echo '<strong style="color:#1e7e34">در حال نمایش در سایت</strong>';
		} elseif ( '' !== $start && $start > atiyeh_core_today() ) {
			echo '<span style="color:#996800">زمان‌بندی‌شده</span>';
		} else {
			echo '<span style="color:#646970">آرشیو</span>';
		}
	},
	10,
	2
);

// A short guide above the list.
add_action(
	'admin_notices',
	static function (): void {
		$screen = get_current_screen();
		if ( ! $screen || 'edit-occasion' !== $screen->id ) {
			return;
		}
		echo '<div class="notice notice-info"><p>جدیدترین اسلاید منتشرشده‌ای که امروز در بازه نمایشش باشد، اسلاید اول صفحه اصلی می‌شود. اسلایدهای قبلی پاک نمی‌شوند و «آرشیو» می‌مانند؛ برای نمایش دوباره، تاریخ‌هایشان را عوض کنید.</p></div>';
	}
);

/** Today's Gregorian date (Y-m-d) in the site timezone. Not wp_date(): WP-Parsidate rewrites its output to Jalali. */
function atiyeh_core_today(): string {
	return ( new DateTimeImmutable( 'now', wp_timezone() ) )->format( 'Y-m-d' );
}
