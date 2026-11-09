<?php
/**
 * Client logos: whenever a logo is set on a `client`, place it (contained, centred, 14px padding)
 * on a white 480×220 canvas so every logo in the marquee has the same footprint.
 * Logos already exactly 480×220 are left alone. Uses GD (bundled with PHP).
 *
 * @package AtiyehCore
 */

const ATIYEH_LOGO_W = 480;
const ATIYEH_LOGO_H = 220;

/**
 * Normalises an attachment in place (keeps the attachment ID). Returns true when the file was changed.
 *
 * @param int $attachment_id Attachment ID.
 */
function atiyeh_core_normalize_logo( int $attachment_id ): bool {
	$file = get_attached_file( $attachment_id );
	if ( ! $file || ! is_readable( $file ) || ! function_exists( 'imagecreatetruecolor' ) ) {
		return false;
	}
	$size = @getimagesize( $file ); // phpcs:ignore WordPress.PHP.NoSilencedErrors
	if ( ! $size || ( ATIYEH_LOGO_W === $size[0] && ATIYEH_LOGO_H === $size[1] ) ) {
		return false;
	}
	$src = match ( $size['mime'] ) {
		'image/png'  => @imagecreatefrompng( $file ), // phpcs:ignore
		'image/jpeg' => @imagecreatefromjpeg( $file ), // phpcs:ignore
		'image/webp' => function_exists( 'imagecreatefromwebp' ) ? @imagecreatefromwebp( $file ) : false, // phpcs:ignore
		'image/gif'  => @imagecreatefromgif( $file ), // phpcs:ignore
		default      => false,
	};
	if ( ! $src ) {
		return false;
	}
	$pad    = 14;
	$scale  = min( ( ATIYEH_LOGO_W - 2 * $pad ) / $size[0], ( ATIYEH_LOGO_H - 2 * $pad ) / $size[1] );
	$w      = max( 1, (int) round( $size[0] * $scale ) );
	$h      = max( 1, (int) round( $size[1] * $scale ) );
	$canvas = imagecreatetruecolor( ATIYEH_LOGO_W, ATIYEH_LOGO_H );
	imagefill( $canvas, 0, 0, imagecolorallocate( $canvas, 255, 255, 255 ) );
	imagealphablending( $canvas, true );
	imagecopyresampled( $canvas, $src, (int) ( ( ATIYEH_LOGO_W - $w ) / 2 ), (int) ( ( ATIYEH_LOGO_H - $h ) / 2 ), 0, 0, $w, $h, $size[0], $size[1] );

	// Save next to the original as WebP (or PNG when GD lacks WebP), then point the attachment at it.
	$webp   = function_exists( 'imagewebp' );
	$target = preg_replace( '/\.[^.]+$/', '', $file ) . '-logo.' . ( $webp ? 'webp' : 'png' );
	$ok     = $webp ? imagewebp( $canvas, $target, 90 ) : imagepng( $canvas, $target );
	imagedestroy( $canvas );
	imagedestroy( $src );
	if ( ! $ok ) {
		return false;
	}
	update_attached_file( $attachment_id, $target );
	wp_update_post( array( 'ID' => $attachment_id, 'post_mime_type' => $webp ? 'image/webp' : 'image/png' ) );
	require_once ABSPATH . 'wp-admin/includes/image.php';
	wp_update_attachment_metadata( $attachment_id, wp_generate_attachment_metadata( $attachment_id, $target ) );
	update_post_meta( $attachment_id, '_atiyeh_logo_normalized', 1 );
	return true;
}

// Run when a logo (featured image) is set or changed on a client.
$atiyeh_core_on_thumb = static function ( $meta_id, int $post_id, string $key, $value ): void {
	if ( '_thumbnail_id' === $key && 'client' === get_post_type( $post_id ) && (int) $value > 0 ) {
		atiyeh_core_normalize_logo( (int) $value );
	}
};
add_action( 'added_post_meta', $atiyeh_core_on_thumb, 10, 4 );
add_action( 'updated_post_meta', $atiyeh_core_on_thumb, 10, 4 );
