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

/** Current Jalali year in Persian digits. */
function atiyeh_jyear(): string {
	$dt = new DateTimeImmutable( 'now', wp_timezone() );
	return atiyeh_fa_num( atiyeh_to_jalali( (int) $dt->format( 'Y' ), (int) $dt->format( 'n' ), (int) $dt->format( 'j' ) )[0] );
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
		'<svg class="wave %1$s" viewBox="0 0 1440 %2$d" preserveAspectRatio="none" aria-hidden="true" focusable="false"><path d="%3$s" fill="%4$s"/></svg>',
		esc_attr( $class ),
		$h,
		esc_attr( $path ),
		esc_attr( $fill )
	);
}
