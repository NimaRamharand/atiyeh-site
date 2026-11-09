<?php
/**
 * Atiyeh theme bootstrap.
 *
 * Hybrid theme: design tokens + font live in theme.json; templates are PHP so the animated
 * homepage sections (slider, marquee, counters, filters) stay simple and fast.
 *
 * @package Atiyeh
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ATIYEH_VERSION', '0.1.0' );
define( 'ATIYEH_DIR', get_template_directory() );
define( 'ATIYEH_URI', get_template_directory_uri() );

require ATIYEH_DIR . '/inc/helpers.php';
require ATIYEH_DIR . '/inc/setup.php';
require ATIYEH_DIR . '/inc/customizer.php';
