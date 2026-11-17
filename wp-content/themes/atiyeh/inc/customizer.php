<?php
/**
 * Customizer: company contact details (footer, contact page, schema), homepage stats, company profile PDF.
 * Defaults come from the company profile (رزومه شرکت); the operator can change them in Appearance → Customize.
 *
 * @package Atiyeh
 */

/** Contact fields: key => [label, default]. */
function atiyeh_contact_fields(): array {
	return array(
		'address' => array( 'آدرس', 'اصفهان، چهارراه جهاد، ابتدای بلوار آتشگاه، نبش کوچه میدان میوه و تره‌بار شهرداری، ساختمان فردوسی ۱، طبقه سوم، واحد ۲' ),
		'postal'  => array( 'کد پستی', '۸۱۸۵۷۷۷۳۶۲' ),
		'phone'   => array( 'تلفن و فکس', '۰۳۱-۳۷۷۲۶۲۱۹' ),
		'mobile'  => array( 'تلفن همراه (مدیرعامل)', '۰۹۱۳۲۳۳۳۷۲۰' ),
		'email'   => array( 'ایمیل', 'amar.parsian1@gmail.com' ),
		'ceo'     => array( 'مدیرعامل', 'اعظم عباسیان' ),
		'regno'   => array( 'شماره ثبت', '۵۰۴۱۱' ),
	);
}

/** Digits for tel: links («۰۹۱۳…» → «0913…»). */
function atiyeh_tel( string $text ): string {
	return preg_replace( '/[^\d+]/', '', strtr( $text, array( '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4', '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9' ) ) );
}

/** URL of the downloadable company profile (Customizer → «رزومه شرکت»), or ''. */
function atiyeh_profile_url(): string {
	$id = (int) get_theme_mod( 'atiyeh_profile_pdf', 0 );
	return $id ? (string) wp_get_attachment_url( $id ) : '';
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

		// Company profile PDF (download buttons on the company and licences pages, footer link).
		$wp_customize->add_setting( 'atiyeh_profile_pdf', array( 'default' => 0, 'sanitize_callback' => 'absint' ) );
		$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'atiyeh_profile_pdf', array( 'label' => 'فایل رزومه شرکت (PDF)', 'section' => 'atiyeh_contact', 'mime_type' => 'application/pdf' ) ) );

		// Homepage stats: empty = «[ ۰۰ ]» placeholder. A number animates as a counter.
		$wp_customize->add_section( 'atiyeh_stats', array( 'title' => 'آمار صفحه اصلی', 'priority' => 31, 'description' => 'خالی بماند تا «[ ۰۰ ]» نمایش داده شود. فقط عدد واقعی وارد کنید.' ) );
		foreach ( atiyeh_stat_labels() as $key => $label ) {
			$wp_customize->add_setting( "atiyeh_stat_{$key}", array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
			$wp_customize->add_control( "atiyeh_stat_{$key}", array( 'label' => $label, 'section' => 'atiyeh_stats', 'type' => 'text' ) );
		}
	}
);

/** Homepage stat circles: key => label. Values: Customizer → «آمار صفحه اصلی». */
function atiyeh_stat_labels(): array {
	return array(
		'years'   => 'سال تجربه',
		'stats'   => 'طرح آماری اجراشده',
		'archive' => 'پروژه آرشیو الکترونیک',
		'plaques' => 'پروژه ساخت و نصب پلاک',
	);
}
