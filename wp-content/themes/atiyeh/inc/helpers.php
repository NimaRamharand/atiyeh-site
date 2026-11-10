<?php
/**
 * Small template helpers: Persian digits, Jalali dates, inline icons, asset URLs.
 *
 * @package Atiyeh
 */

/**
 * Converts Latin digits to Persian digits.
 *
 * @param string|int $text Text containing digits.
 */
function atiyeh_fa_num( string|int $text ): string {
	return strtr( (string) $text, array_combine( range( 0, 9 ), array( '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹' ) ) );
}

/**
 * Gregorian → Jalali conversion (no dependency on WP-Parsidate).
 *
 * @return array{0:int,1:int,2:int} [year, month, day]
 */
function atiyeh_to_jalali( int $gy, int $gm, int $gd ): array {
	$g_d_m = array( 0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334 );
	$gy2   = ( $gm > 2 ) ? ( $gy + 1 ) : $gy;
	$days  = 355666 + ( 365 * $gy ) + intdiv( $gy2 + 3, 4 ) - intdiv( $gy2 + 99, 100 ) + intdiv( $gy2 + 399, 400 ) + $gd + $g_d_m[ $gm - 1 ];
	$jy    = -1595 + ( 33 * intdiv( $days, 12053 ) );
	$days %= 12053;
	$jy   += 4 * intdiv( $days, 1461 );
	$days %= 1461;
	if ( $days > 365 ) {
		$jy  += intdiv( $days - 1, 365 );
		$days = ( $days - 1 ) % 365;
	}
	$jm = ( $days < 186 ) ? 1 + intdiv( $days, 31 ) : 7 + intdiv( $days - 186, 30 );
	$jd = 1 + ( ( $days < 186 ) ? ( $days % 31 ) : ( ( $days - 186 ) % 30 ) );
	return array( $jy, $jm, $jd );
}

/**
 * Formats a timestamp as a Persian Jalali date, e.g. «۱۸ آبان ۱۴۰۵».
 *
 * @param int|null $timestamp Unix timestamp (site timezone applied); now when null.
 */
function atiyeh_jdate( ?int $timestamp = null ): string {
	$dt     = ( new DateTimeImmutable( '@' . ( $timestamp ?? time() ) ) )->setTimezone( wp_timezone() );
	$months = array( 'فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند' );
	[ $y, $m, $d ] = atiyeh_to_jalali( (int) $dt->format( 'Y' ), (int) $dt->format( 'n' ), (int) $dt->format( 'j' ) );
	return atiyeh_fa_num( $d . ' ' . $months[ $m - 1 ] . ' ' . $y );
}

/**
 * Display date of a post. Demo posts flagged `_atiyeh_date_placeholder` show «[ تاریخ ]»
 * because their real publish dates are not known yet.
 *
 * @param WP_Post|int|null $post Post.
 */
function atiyeh_post_date( WP_Post|int|null $post = null ): string {
	$post = get_post( $post );
	if ( ! $post ) {
		return '';
	}
	if ( get_post_meta( $post->ID, '_atiyeh_date_placeholder', true ) ) {
		return '[ تاریخ ]';
	}
	return atiyeh_jdate( (int) get_post_time( 'U', true, $post ) );
}

/** Current Jalali year in Persian digits. */
function atiyeh_jyear(): string {
	$dt = new DateTimeImmutable( 'now', wp_timezone() );
	return atiyeh_fa_num( atiyeh_to_jalali( (int) $dt->format( 'Y' ), (int) $dt->format( 'n' ), (int) $dt->format( 'j' ) )[0] );
}

/**
 * Keeps «[ placeholder ]» brackets on one line with their text (no orphan «]» when wrapping).
 *
 * @param string $text Text.
 */
function atiyeh_nb( string $text ): string {
	return str_replace( array( '[ ', ' ]' ), array( "[\u{00A0}", "\u{00A0}]" ), $text );
}

if ( ! is_admin() ) {
	add_filter( 'the_title', 'atiyeh_nb' );
}

/** URL of a file inside the theme's assets/ folder. */
function atiyeh_asset( string $path ): string {
	return ATIYEH_URI . '/assets/' . ltrim( $path, '/' );
}

/**
 * Returns an inline SVG icon (decorative, aria-hidden).
 *
 * @param string $name Icon name.
 * @param int    $size Pixel size.
 */
function atiyeh_icon( string $name, int $size = 24 ): string {
	$paths = array(
		'logo'   => '<path d="M4 20V10M10 20V4M16 20v-7M22 20H2"/>',
		'search' => '<circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/>',
		'menu'   => '<path d="M4 7h16M4 12h16M4 17h10"/>',
		'close'  => '<path d="M6 6l12 12M18 6L6 18"/>',
		'sun'    => '<circle cx="12" cy="12" r="4.2"/><path d="M12 2.5v2.2M12 19.3v2.2M4.6 4.6l1.6 1.6M17.8 17.8l1.6 1.6M2.5 12h2.2M19.3 12h2.2M4.6 19.4l1.6-1.6M17.8 6.2l1.6-1.6"/>',
		'moon'   => '<path d="M20.5 14.2A8.5 8.5 0 0 1 9.8 3.5a8.5 8.5 0 1 0 10.7 10.7z"/>',
		'up'     => '<path d="M12 19V5M5.5 11.5L12 5l6.5 6.5"/>',
		'check'  => '<path d="M5 12.5l4.2 4.2L19 7"/>',
		'target' => '<circle cx="12" cy="12" r="8.5"/><circle cx="12" cy="12" r="4.5"/><circle cx="12" cy="12" r=".8"/>',
		'shield' => '<path d="M12 3l7.5 3v5.5c0 4.6-3.2 8.3-7.5 9.5-4.3-1.2-7.5-4.9-7.5-9.5V6z"/><path d="M8.8 12.2l2.2 2.2 4.3-4.4"/>',
		'users'  => '<circle cx="9" cy="8.5" r="3.3"/><path d="M3 19.5c.6-3.4 3-5.3 6-5.3s5.4 1.9 6 5.3"/><path d="M15.5 5.6a3.2 3.2 0 0 1 0 6M17.8 14.6c1.7.7 2.9 2.4 3.2 4.9"/>',
		'clock'  => '<circle cx="12" cy="12" r="8.5"/><path d="M12 7.5V12l3 2"/>',
		'pin'    => '<path d="M12 21s-6.5-6-6.5-11a6.5 6.5 0 1 1 13 0c0 5-6.5 11-6.5 11z"/><circle cx="12" cy="10" r="2.4"/>',
		'phone'  => '<path d="M5 3.5h3.6l1.7 4.3-2.2 1.5a11 11 0 0 0 6.6 6.6l1.5-2.2 4.3 1.7V19a2 2 0 0 1-2 2A17 17 0 0 1 3 5.5a2 2 0 0 1 2-2z"/>',
		'mail'   => '<rect x="3" y="5" width="18" height="14" rx="3"/><path d="M4 7l8 6 8-6"/>',
		'doc'    => '<path d="M6 2.8h8.2L19 7.6V20a1.2 1.2 0 0 1-1.2 1.2H6A1.2 1.2 0 0 1 4.8 20V4A1.2 1.2 0 0 1 6 2.8z"/><path d="M14 3v5h5M8.5 12.5h7M8.5 16h5"/>',
		'plaque' => '<rect x="3" y="6" width="18" height="12" rx="3"/><path d="M7 10.5h6M7 13.5h10"/><circle cx="17" cy="10.5" r=".6"/>',
		'award'  => '<circle cx="12" cy="9" r="5.5"/><path d="M8.6 13.4L7 21l5-2.6 5 2.6-1.6-7.6"/>',
		'layers' => '<path d="M12 3l9 5-9 5-9-5z"/><path d="M3 13l9 5 9-5"/>',
		'chart'  => '<path d="M4 20V10M10 20V4M16 20v-7M22 20H2"/>',
		'spark'  => '<path d="M12 3v4M12 17v4M3 12h4M17 12h4M6 6l2.5 2.5M15.5 15.5L18 18M6 18l2.5-2.5M15.5 8.5L18 6"/>',
		'growth' => '<path d="M3 17l6-6 4 4 8-8"/><path d="M15 7h6v6"/>',
	);
	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}
	return sprintf(
		'<svg class="icon icon-%1$s" width="%2$d" height="%2$d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%3$s</svg>',
		esc_attr( $name ),
		$size,
		$paths[ $name ]
	);
}

/**
 * Prints a decorative SVG wave used between sections.
 *
 * @param string $path  SVG path data (viewBox 0 0 1440 H).
 * @param string $fill  Fill color.
 * @param string $class Extra class (position modifiers).
 * @param int    $h     ViewBox height.
 */
function atiyeh_wave( string $path, string $fill, string $class = '', int $h = 90 ): void {
	printf(
		'<svg class="wave %1$s" viewBox="0 0 1440 %2$d" preserveAspectRatio="none" aria-hidden="true" focusable="false"><path d="%3$s" style="fill:%4$s"/></svg>',
		esc_attr( $class ),
		$h,
		esc_attr( $path ),
		esc_attr( $fill )
	);
}
