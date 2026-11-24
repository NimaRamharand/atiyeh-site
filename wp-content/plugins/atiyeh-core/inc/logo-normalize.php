<?php
/**
 * Client logos: whenever a logo is set on a `client`, it is flattened on white, its empty margins are
 * trimmed, and it is placed centred on a white 480×220 canvas at an equal visual weight (same ink
 * area for square and wide logos; wide logos capped at 320px). Same rules as the logos in
 * assets-source/logos, so uploads match them. Logos already exactly 480×220 are left alone. Uses GD.
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
	// Flatten onto white (transparent PNG/GIF/WebP logos).
	$flat = imagecreatetruecolor( $size[0], $size[1] );
	imagefill( $flat, 0, 0, imagecolorallocate( $flat, 255, 255, 255 ) );
	imagealphablending( $flat, true );
	imagecopy( $flat, $src, 0, 0, 0, 0, $size[0], $size[1] );
	imagedestroy( $src );

	// Trim near-white margins (sampled every few pixels on big files).
	$step = max( 1, (int) floor( max( $size[0], $size[1] ) / 600 ) );
	$x0   = $size[0];
	$y0   = $size[1];
	$x1   = -1;
	$y1   = -1;
	for ( $y = 0; $y < $size[1]; $y += $step ) {
		for ( $x = 0; $x < $size[0]; $x += $step ) {
			$rgb = imagecolorat( $flat, $x, $y );
			if ( min( ( $rgb >> 16 ) & 255, ( $rgb >> 8 ) & 255, $rgb & 255 ) < 227 ) {
				$x0 = min( $x0, $x );
				$y0 = min( $y0, $y );
				$x1 = max( $x1, $x );
				$y1 = max( $y1, $y );
			}
		}
	}
	if ( $x1 < 0 ) { // Blank image: keep it whole.
		$x0 = 0;
		$y0 = 0;
		$x1 = $size[0] - 1;
		$y1 = $size[1] - 1;
	}
	$cw = $x1 - $x0 + 1;
	$ch = $y1 - $y0 + 1;

	// Equal visual weight inside a 428×168 box; wide logos at most 320px.
	$box_w  = ATIYEH_LOGO_W - 52;
	$box_h  = ATIYEH_LOGO_H - 52;
	$scale  = min( $box_w / $cw, $box_h / $ch, sqrt( ( $box_h * $box_h * 1.25 ) / ( $cw * $ch ) ) );
	$scale  = $cw * $scale > 320 && $cw / $ch > 3 ? 320 / $cw : $scale;
	$w      = max( 1, (int) round( $cw * $scale ) );
	$h      = max( 1, (int) round( $ch * $scale ) );
	$canvas = imagecreatetruecolor( ATIYEH_LOGO_W, ATIYEH_LOGO_H );
	imagefill( $canvas, 0, 0, imagecolorallocate( $canvas, 255, 255, 255 ) );
	imagecopyresampled( $canvas, $flat, (int) ( ( ATIYEH_LOGO_W - $w ) / 2 ), (int) ( ( ATIYEH_LOGO_H - $h ) / 2 ), $x0, $y0, $w, $h, $cw, $ch );
	imagedestroy( $flat );

	// Save next to the original as WebP (or PNG when GD lacks WebP), then point the attachment at it.
	$webp   = function_exists( 'imagewebp' );
	$target = preg_replace( '/\.[^.]+$/', '', $file ) . '-logo.' . ( $webp ? 'webp' : 'png' );
	$ok     = $webp ? imagewebp( $canvas, $target, 90 ) : imagepng( $canvas, $target );
	imagedestroy( $canvas );
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
