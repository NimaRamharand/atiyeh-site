<?php
/**
 * Customizer: company contact details shown in the footer and contact page.
 * Defaults are placeholders until the company provides real data.
 *
 * @package Atiyeh
 */

/** Contact fields: key => [label, placeholder default]. */
function atiyeh_contact_fields(): array {
	return array(
		'address' => array( 'آدرس', '[ آدرس ]' ),
		'phone'   => array( 'تلفن', '[ تلفن ]' ),
		'email'   => array( 'ایمیل', '[ ایمیل ]' ),
	);
}

/** Returns a contact value (or its placeholder). */
function atiyeh_contact( string $key ): string {
	$fields = atiyeh_contact_fields();
	return (string) get_theme_mod( 'atiyeh_' . $key, $fields[ $key ][1] ?? '' );
}

add_action(
	'customize_register',
	function ( WP_Customize_Manager $wp_customize ): void {
		$wp_customize->add_section( 'atiyeh_contact', array( 'title' => 'اطلاعات تماس شرکت', 'priority' => 30 ) );
		foreach ( atiyeh_contact_fields() as $key => [ $label, $default ] ) {
			$wp_customize->add_setting( 'atiyeh_' . $key, array( 'default' => $default, 'sanitize_callback' => 'sanitize_text_field' ) );
			$wp_customize->add_control( 'atiyeh_' . $key, array( 'label' => $label, 'section' => 'atiyeh_contact', 'type' => 'text' ) );
		}
	}
);
