<?php
/**
 * Newsletter sign-ups from the footer form, stored as private `subscriber` entries.
 *
 * @package AtiyehCore
 */

add_action(
	'init',
	static function (): void {
		register_post_type(
			'subscriber',
			array(
				'labels'       => array(
					'name'          => 'اعضای خبرنامه',
					'singular_name' => 'عضو خبرنامه',
					'menu_name'     => 'خبرنامه',
					'all_items'     => 'اعضای خبرنامه',
					'not_found'     => 'هنوز کسی عضو نشده است',
				),
				'public'       => false,
				'show_ui'      => true,
				'menu_icon'    => 'dashicons-email-alt',
				'menu_position' => 26,
				'supports'     => array( 'title' ),
				'capabilities' => array( 'create_posts' => 'do_not_allow' ),
				'map_meta_cap' => true,
			)
		);
	}
);

/** Handles the footer newsletter form. */
function atiyeh_core_handle_newsletter(): void {
	$back = wp_get_referer() ? wp_get_referer() : home_url( '/' );
	$back = remove_query_arg( 'newsletter', strtok( $back, '#' ) );
	$go   = static function ( string $status ) use ( $back ): void {
		wp_safe_redirect( add_query_arg( 'newsletter', $status, $back ) . '#newsletter' );
		exit;
	};
	if ( ! isset( $_POST['_atiyeh_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['_atiyeh_nonce'] ), 'atiyeh_newsletter' ) ) {
		$go( 'expired' );
	}
	$email = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	if ( ! is_email( $email ) ) {
		$go( 'invalid' );
	}
	$email  = strtolower( $email );
	$exists = get_posts( array( 'post_type' => 'subscriber', 'title' => $email, 'post_status' => 'any', 'numberposts' => 1, 'fields' => 'ids' ) );
	if ( ! $exists ) {
		wp_insert_post( array( 'post_type' => 'subscriber', 'post_status' => 'publish', 'post_title' => $email ) );
	}
	$go( 'ok' );
}
add_action( 'admin_post_atiyeh_newsletter', 'atiyeh_core_handle_newsletter' );
add_action( 'admin_post_nopriv_atiyeh_newsletter', 'atiyeh_core_handle_newsletter' );
