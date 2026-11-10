<?php
/**
 * Homepage. Sections are template parts in template-parts/home/, in mockup order.
 *
 * @package Atiyeh
 */

get_header();
$atiyeh_sections = array( 'hero', 'clients', 'about', 'areas', 'products', 'ticker', 'stats', 'projects', 'trust', 'process', 'request', 'careers' );
foreach ( $atiyeh_sections as $atiyeh_section ) {
	if ( locate_template( "template-parts/home/{$atiyeh_section}.php" ) ) {
		get_template_part( "template-parts/home/{$atiyeh_section}" );
	}
}
get_footer();
