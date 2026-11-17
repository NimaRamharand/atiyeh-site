<?php
/**
 * Tiny meta-field framework: declare fields per post type once; get REST registration,
 * an admin meta box and sanitised saving for free.
 *
 * @package AtiyehCore
 */

/**
 * Field definitions: post type => [ meta_key => [ label, type, help ] ].
 * type: text | url | bool | textarea | select (options in index 3) | jdate (Jalali date, also stored as Gregorian in `{key}_g`).
 *
 * @return array<string, array<string, array{0:string,1:string,2?:string}>>
 */
function atiyeh_core_fields(): array {
	return array(
		'project'     => array(
			'_atiyeh_client' => array( 'کارفرما', 'text', 'مثلاً: شهرداری اصفهان' ),
			'_atiyeh_city'   => array( 'شهر', 'text' ),
			'_atiyeh_date'   => array( 'تاریخ اجرا', 'text', 'مثلاً: ۱۴۰۳ یا ۱۴۰۲–۱۴۰۳' ),
			'_atiyeh_status' => array( 'وضعیت', 'select', '', array( 'done' => array( 'label' => 'اجراشده' ), 'ongoing' => array( 'label' => 'در حال اجرا' ) ) ),
		),
		'occasion'    => array(
			'_atiyeh_kicker'   => array( 'برچسب بالای عنوان', 'text', 'مثلاً: مناسبت، رویداد، خبر مهم' ),
			'_atiyeh_desc'     => array( 'متن کوتاه', 'textarea', 'یک یا دو جمله؛ زیر عنوان نمایش داده می‌شود.' ),
			'_atiyeh_btn_text' => array( 'متن دکمه', 'text', 'اختیاری؛ مثلاً: اطلاعات بیشتر' ),
			'_atiyeh_btn_url'  => array( 'لینک دکمه', 'url', 'اختیاری؛ اگر خالی باشد دکمه نمایش داده نمی‌شود.' ),
			'_atiyeh_theme'    => array( 'رنگ پس‌زمینه', 'select', '', atiyeh_core_occasion_themes() ),
			'_atiyeh_start'    => array( 'نمایش از تاریخ', 'jdate', 'شمسی، مثلاً ۱۴۰۵/۰۸/۰۱ — خالی یعنی از همین حالا.' ),
			'_atiyeh_end'      => array( 'نمایش تا تاریخ', 'jdate', 'شمسی — خالی یعنی تا وقتی اسلاید جدیدتری منتشر شود.' ),
		),
		'client'      => array(
			'_atiyeh_city' => array( 'شهر', 'text', 'اختیاری' ),
			'_atiyeh_url'  => array( 'لینک وب‌سایت کارفرما', 'url', 'اختیاری' ),
		),
		'appreciation' => array(
			'_atiyeh_client' => array( 'کارفرما', 'text' ),
			'_atiyeh_year'   => array( 'سال', 'text', 'مثلاً: ۱۴۰۲' ),
		),
		'job'         => array(
			'_atiyeh_city'   => array( 'شهر محل کار', 'text' ),
			'_atiyeh_active' => array( 'آگهی فعال است', 'bool', 'فقط آگهی‌های فعال در سایت نمایش داده می‌شوند.' ),
		),
	);
}

/** Reads a field value (string), empty when missing. */
function atiyeh_core_meta( int $post_id, string $key ): string {
	return (string) get_post_meta( $post_id, $key, true );
}

add_action(
	'init',
	static function (): void {
		foreach ( atiyeh_core_fields() as $type => $fields ) {
			foreach ( $fields as $key => $def ) {
				register_post_meta(
					$type,
					$key,
					array(
						'type'              => 'bool' === $def[1] ? 'boolean' : 'string',
						'single'            => true,
						'show_in_rest'      => true,
						'sanitize_callback' => match ( $def[1] ) {
							'url'      => 'esc_url_raw',
							'bool'     => 'rest_sanitize_boolean',
							'textarea' => 'sanitize_textarea_field',
							default    => 'sanitize_text_field',
						},
						'auth_callback'     => static fn(): bool => current_user_can( 'edit_posts' ),
					)
				);
			}
		}
	},
	20
);

add_action(
	'add_meta_boxes',
	static function (): void {
		foreach ( array_keys( atiyeh_core_fields() ) as $type ) {
			add_meta_box( 'atiyeh_fields', 'مشخصات', 'atiyeh_core_render_box', $type, 'side', 'high' );
		}
	}
);

/** Meta box markup. */
function atiyeh_core_render_box( WP_Post $post ): void {
	wp_nonce_field( 'atiyeh_fields', '_atiyeh_fields_nonce' );
	foreach ( atiyeh_core_fields()[ $post->post_type ] ?? array() as $key => $def ) {
		$value = atiyeh_core_meta( $post->ID, $key );
		echo '<p>';
		if ( 'bool' === $def[1] ) {
			printf( '<label><input type="checkbox" name="%1$s" value="1" %2$s> %3$s</label>', esc_attr( $key ), checked( '1', $value, false ), esc_html( $def[0] ) );
		} elseif ( 'textarea' === $def[1] ) {
			printf( '<label for="%1$s"><strong>%2$s</strong></label><br><textarea class="widefat" rows="4" id="%1$s" name="%1$s">%3$s</textarea>', esc_attr( $key ), esc_html( $def[0] ), esc_textarea( $value ) );
		} elseif ( 'select' === $def[1] ) {
			printf( '<label for="%1$s"><strong>%2$s</strong></label><br><select class="widefat" id="%1$s" name="%1$s">', esc_attr( $key ), esc_html( $def[0] ) );
			foreach ( $def[3] as $opt => $opt_def ) {
				printf( '<option value="%1$s"%2$s>%3$s</option>', esc_attr( $opt ), selected( $value, $opt, false ), esc_html( $opt_def['label'] ) );
			}
			echo '</select>';
		} else {
			printf(
				'<label for="%1$s"><strong>%2$s</strong></label><br><input class="widefat" type="%3$s" id="%1$s" name="%1$s" value="%4$s"%5$s>',
				esc_attr( $key ),
				esc_html( $def[0] ),
				'url' === $def[1] ? 'url' : 'text',
				esc_attr( $value ),
				'url' === $def[1] ? ' dir="ltr"' : ( 'jdate' === $def[1] ? ' placeholder="۱۴۰۵/۰۸/۰۱" inputmode="numeric"' : '' )
			);
		}
		if ( ! empty( $def[2] ) ) {
			echo '<br><span class="description">' . esc_html( $def[2] ) . '</span>';
		}
		echo '</p>';
	}
}

add_action(
	'save_post',
	static function ( int $post_id, WP_Post $post ): void {
		$fields = atiyeh_core_fields()[ $post->post_type ] ?? null;
		if ( ! $fields || ! isset( $_POST['_atiyeh_fields_nonce'] ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) {
			return;
		}
		if ( ! wp_verify_nonce( sanitize_key( $_POST['_atiyeh_fields_nonce'] ), 'atiyeh_fields' ) || ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		foreach ( $fields as $key => $def ) {
			$raw = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
			$val = match ( $def[1] ) {
				'url'      => esc_url_raw( (string) $raw ),
				'bool'     => $raw ? '1' : '0',
				'textarea' => sanitize_textarea_field( (string) $raw ),
				'select'   => array_key_exists( (string) $raw, $def[3] ) ? (string) $raw : (string) array_key_first( $def[3] ),
				default    => sanitize_text_field( (string) $raw ),
			};
			update_post_meta( $post_id, $key, $val );
			if ( 'jdate' === $def[1] ) {
				$g = atiyeh_core_parse_jdate( $val );
				update_post_meta( $post_id, $key . '_g', '' === $val ? '' : ( $g ?? 'invalid' ) );
			}
		}
	},
	10,
	2
);

/**
 * Jalali → Gregorian.
 *
 * @return array{0:int,1:int,2:int}
 */
function atiyeh_core_jalali_to_gregorian( int $jy, int $jm, int $jd ): array {
	$jy  += 1595;
	$days = -355668 + ( 365 * $jy ) + ( intdiv( $jy, 33 ) * 8 ) + intdiv( ( $jy % 33 ) + 3, 4 ) + $jd + ( ( $jm < 7 ) ? ( $jm - 1 ) * 31 : ( ( $jm - 7 ) * 30 ) + 186 );
	$gy   = 400 * intdiv( $days, 146097 );
	$days %= 146097;
	if ( $days > 36524 ) {
		$gy  += 100 * intdiv( --$days, 36524 );
		$days %= 36524;
		if ( $days >= 365 ) {
			++$days;
		}
	}
	$gy  += 4 * intdiv( $days, 1461 );
	$days %= 1461;
	if ( $days > 365 ) {
		$gy  += intdiv( $days - 1, 365 );
		$days = ( $days - 1 ) % 365;
	}
	$gd  = $days + 1;
	$leap = ( 0 === $gy % 4 && 0 !== $gy % 100 ) || 0 === $gy % 400;
	foreach ( array( 0, 31, $leap ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 ) as $gm => $len ) {
		if ( 0 === $gm ) {
			continue;
		}
		if ( $gd <= $len ) {
			break;
		}
		$gd -= $len;
	}
	return array( $gy, $gm, $gd );
}

/** Parses «۱۴۰۵/۸/۱» (Persian or Latin digits, / - or . separators) to Y-m-d, or null when invalid. */
function atiyeh_core_parse_jdate( string $text ): ?string {
	$text = strtr( trim( $text ), array( '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4', '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9', '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9' ) );
	if ( ! preg_match( '~^(1[34]\d\d)[/\-.](\d{1,2})[/\-.](\d{1,2})$~', $text, $m ) ) {
		return null;
	}
	[ , $jy, $jm, $jd ] = array_map( 'intval', $m );
	if ( $jm < 1 || $jm > 12 || $jd < 1 || $jd > ( $jm <= 6 ? 31 : 30 ) ) {
		return null;
	}
	[ $gy, $gm, $gd ] = atiyeh_core_jalali_to_gregorian( $jy, $jm, $jd );
	return sprintf( '%04d-%02d-%02d', $gy, $gm, $gd );
}
