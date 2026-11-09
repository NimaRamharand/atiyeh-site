<?php
/**
 * Tiny meta-field framework: declare fields per post type once; get REST registration,
 * an admin meta box and sanitised saving for free.
 *
 * @package AtiyehCore
 */

/**
 * Field definitions: post type => [ meta_key => [ label, type, help ] ].
 * type: text | url | bool.
 *
 * @return array<string, array<string, array{0:string,1:string,2?:string}>>
 */
function atiyeh_core_fields(): array {
	return array(
		'project'     => array(
			'_atiyeh_client' => array( 'کارفرما', 'text', 'مثلاً: شهرداری اصفهان' ),
			'_atiyeh_city'   => array( 'شهر', 'text' ),
			'_atiyeh_date'   => array( 'تاریخ اجرا', 'text', 'مثلاً: ۱۴۰۳ یا مهر ۱۴۰۳' ),
		),
		'client'      => array(
			'_atiyeh_url' => array( 'لینک وب‌سایت کارفرما', 'url', 'اختیاری' ),
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
						'sanitize_callback' => 'url' === $def[1] ? 'esc_url_raw' : ( 'bool' === $def[1] ? 'rest_sanitize_boolean' : 'sanitize_text_field' ),
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
		} else {
			printf(
				'<label for="%1$s"><strong>%2$s</strong></label><br><input class="widefat" type="%3$s" id="%1$s" name="%1$s" value="%4$s"%5$s>',
				esc_attr( $key ),
				esc_html( $def[0] ),
				'url' === $def[1] ? 'url' : 'text',
				esc_attr( $value ),
				'url' === $def[1] ? ' dir="ltr"' : ''
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
				'url'   => esc_url_raw( (string) $raw ),
				'bool'  => $raw ? '1' : '0',
				default => sanitize_text_field( (string) $raw ),
			};
			update_post_meta( $post_id, $key, $val );
		}
	},
	10,
	2
);
