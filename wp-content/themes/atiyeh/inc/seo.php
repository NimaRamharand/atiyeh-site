<?php
/**
 * Structured data on top of Rank Math: the site's publisher becomes a full Organization +
 * ProfessionalService built from the Customizer contact details, licences and services,
 * so the operator's edits flow into the schema automatically.
 *
 * Rank Math settings (company type, breadcrumbs, no Article schema on pages) are applied by the
 * demo seeder; see scripts/wp/seed.php → atiyeh_demo_seo().
 *
 * @package Atiyeh
 */

/** Persian/Latin phone «۰۳۱-۳۷۷۲۶۲۱۹» → «+98-31-37726219». */
function atiyeh_e164( string $phone ): string {
	$digits = atiyeh_tel( $phone );
	if ( str_starts_with( $digits, '0' ) ) {
		$digits = substr( $digits, 1 );
		return '+98-' . substr( $digits, 0, 2 ) . '-' . substr( $digits, 2 );
	}
	return $digits;
}

/** Latin digits for machine-readable values. */
function atiyeh_latin_digits( string $text ): string {
	return strtr( $text, array( '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4', '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9' ) );
}

/**
 * The organisation entity (schema.org).
 *
 * @param string $id Entity @id used by the rest of Rank Math's graph.
 * @return array<string, mixed>
 */
function atiyeh_org_schema( string $id ): array {
	$logo     = atiyeh_asset( 'img/icon/logo-512.png' );
	$services = array();
	foreach ( atiyeh_service_blocks() as $s ) {
		$services[] = array(
			'@type'       => 'Offer',
			'itemOffered' => array(
				'@type'       => 'Service',
				'name'        => $s['title'],
				'description' => $s['lead'],
				'url'         => atiyeh_page_url( 'services', '/' ) . '#' . $s['id'],
			),
		);
	}
	$credentials = array();
	foreach ( atiyeh_licenses() as [ $title, $meta ] ) {
		$credentials[] = array( '@type' => 'EducationalOccupationalCredential', 'name' => $title, 'credentialCategory' => $meta );
	}
	return array(
		'@type'             => array( 'Organization', 'ProfessionalService' ),
		'@id'               => $id,
		'name'              => get_bloginfo( 'name' ),
		'alternateName'     => array( 'شرکت ' . get_bloginfo( 'name' ), 'A.P.P' ),
		'description'       => 'اجرای طرح‌های آماری، فهرست‌برداری و بهنگام‌سازی کدپستی، ساخت و نصب پلاک کدپستی و آرشیو الکترونیک اسناد پزشکی در اصفهان، از سال ۱۳۸۸.',
		'url'               => home_url( '/' ),
		'logo'              => array( '@type' => 'ImageObject', '@id' => home_url( '/#logo' ), 'url' => $logo, 'width' => 512, 'height' => 512 ),
		'image'             => array( '@id' => home_url( '/#logo' ) ),
		'email'             => atiyeh_contact( 'email' ),
		'telephone'         => atiyeh_e164( atiyeh_contact( 'phone' ) ),
		'faxNumber'         => atiyeh_e164( atiyeh_contact( 'phone' ) ),
		'address'           => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => atiyeh_contact( 'address' ),
			'addressLocality' => 'اصفهان',
			'addressRegion'   => 'استان اصفهان',
			'postalCode'      => atiyeh_latin_digits( atiyeh_contact( 'postal' ) ),
			'addressCountry'  => 'IR',
		),
		'areaServed'        => array( '@type' => 'AdministrativeArea', 'name' => 'استان اصفهان' ),
		'identifier'        => array( '@type' => 'PropertyValue', 'name' => 'شماره ثبت', 'value' => atiyeh_latin_digits( atiyeh_contact( 'regno' ) ) ),
		'hasCredential'     => $credentials,
		'hasOfferCatalog'   => array( '@type' => 'OfferCatalog', 'name' => 'خدمات', 'itemListElement' => $services ),
		'knowsAbout'        => array( 'طرح‌های آماری', 'آمارگیری میدانی', 'بهنگام‌سازی کدپستی', 'پلاک کدپستی', 'آرشیو الکترونیک اسناد پزشکی', 'ژئوکدسازی' ),
		'contactPoint'      => array(
			'@type'             => 'ContactPoint',
			'contactType'       => 'customer service',
			'telephone'         => atiyeh_e164( atiyeh_contact( 'mobile' ) ),
			'email'             => atiyeh_contact( 'email' ),
			'availableLanguage' => 'fa',
		),
	);
}

add_filter(
	'rank_math/json_ld',
	static function ( array $data ): array {
		$id = home_url( '/#organization' );
		foreach ( $data as $key => $entity ) {
			$types = (array) ( $entity['@type'] ?? array() );
			if ( 'publisher' === $key || ( isset( $entity['@id'] ) && str_ends_with( (string) $entity['@id'], '#organization' ) ) || array_intersect( $types, array( 'Organization' ) ) ) {
				$id = (string) ( $entity['@id'] ?? $id );
				unset( $data[ $key ] );
			}
		}
		$data['publisher'] = atiyeh_org_schema( $id );
		return $data;
	},
	99
);

// Share image: Rank Math prints a post's featured image; everywhere else use the branded card.
add_action(
	'wp_head',
	static function (): void {
		if ( is_singular() && has_post_thumbnail() ) {
			return;
		}
		$img = atiyeh_asset( 'img/icon/og-image.png' );
		printf( '<meta property="og:image" content="%1$s" />' . "\n" . '<meta property="og:image:width" content="1200" />' . "\n" . '<meta property="og:image:height" content="630" />' . "\n" . '<meta name="twitter:image" content="%1$s" />' . "\n", esc_url( $img ) );
	},
	99
);

// No «زمان خواندن / نویسنده» labels on shared links of a company site.
add_filter( 'rank_math/opengraph/slack_enhanced_data', '__return_empty_array' );
