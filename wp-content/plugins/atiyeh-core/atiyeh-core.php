<?php
/**
 * Plugin Name: Atiyeh Core
 * Description: محتوای اختصاصی سایت آمار پژوهان پارسیان: پروژه‌ها، کارفرمایان، لوح‌های سپاس، فرصت‌های شغلی، درخواست‌های همکاری (با رزومه) و خبرنامه.
 * Version: 0.3.0
 * Requires PHP: 8.1
 * Author: Atiyeh
 * Text Domain: atiyeh-core
 *
 * Content types live in a plugin (not the theme) so the data survives a theme change.
 *
 * @package AtiyehCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ATIYEH_CORE_DIR', __DIR__ );

require ATIYEH_CORE_DIR . '/inc/fields.php';
require ATIYEH_CORE_DIR . '/inc/post-types.php';
require ATIYEH_CORE_DIR . '/inc/logo-normalize.php';
require ATIYEH_CORE_DIR . '/inc/applications.php';
require ATIYEH_CORE_DIR . '/inc/newsletter.php';
require ATIYEH_CORE_DIR . '/inc/forms.php';

register_activation_hook(
	__FILE__,
	static function (): void {
		atiyeh_core_register_types();
		flush_rewrite_rules();
	}
);
