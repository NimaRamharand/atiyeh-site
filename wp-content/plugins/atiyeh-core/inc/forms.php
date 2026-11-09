<?php
/**
 * Fluent Forms: the «درخواست خدمات» and «تماس با ما» forms, created/updated in code so a fresh
 * install (or reset-demo) always has them. Entries are stored by Fluent Forms in the database
 * (پیشخوان ← Fluent Forms ← ورودی‌ها). Existing forms are updated in place, so entries survive.
 *
 * @package AtiyehCore
 */

/**
 * Builds one Fluent Forms field.
 *
 * @param string $element  input_text | input_email | select | textarea.
 * @param string $name     Field name.
 * @param string $label    Label.
 * @param bool   $required Required.
 * @param array  $extra    { type?, options?, wide?, ltr?, rows? }.
 */
function atiyeh_core_ff_field( string $element, string $name, string $label, bool $required = false, array $extra = array() ): array {
	static $n = 0;
	++$n;
	$attrs = array( 'name' => $name, 'value' => '', 'class' => '', 'placeholder' => '' );
	if ( 'input_text' === $element || 'input_email' === $element ) {
		$attrs['type'] = $extra['type'] ?? ( 'input_email' === $element ? 'email' : 'text' );
	}
	if ( 'textarea' === $element ) {
		$attrs['rows'] = $extra['rows'] ?? 4;
		$attrs['cols'] = 2;
	}
	if ( ! empty( $extra['ltr'] ) ) {
		$attrs['dir'] = 'ltr';
	}
	$rules = array(
		'required' => array( 'value' => $required, 'message' => 'این فیلد الزامی است', 'global_message' => 'این فیلد الزامی است', 'global' => true ),
	);
	if ( 'input_email' === $element ) {
		$rules['email'] = array( 'value' => true, 'message' => 'ایمیل معتبر وارد کنید', 'global_message' => 'ایمیل معتبر وارد کنید', 'global' => true );
	}
	$settings = array(
		'container_class'    => ! empty( $extra['wide'] ) ? 'ff-wide' : '',
		'label'              => $label,
		'label_placement'    => '',
		'admin_field_label'  => $label,
		'help_message'       => '',
		'validation_rules'   => $rules,
		'conditional_logics' => array(),
	);
	if ( 'select' === $element ) {
		$settings['placeholder']      = '';
		$settings['advanced_options'] = array_map( static fn( string $o ): array => array( 'label' => $o, 'value' => $o, 'calc_value' => '' ), $extra['options'] ?? array() );
		$settings['enable_select_2']  = 'no';
	}
	return array(
		'index'          => $n,
		'element'        => $element,
		'attributes'     => $attrs,
		'settings'       => $settings,
		'editor_options' => array( 'title' => $label ),
		'uniqElKey'      => 'el_atiyeh_' . $name,
	);
}

/** Form definitions: key => [title, fields, submit text, confirmation]. */
function atiyeh_core_ff_definitions(): array {
	return array(
		'request' => array(
			'درخواست خدمات',
			array(
				atiyeh_core_ff_field( 'input_text', 'full_name', 'نام و نام خانوادگی', true ),
				atiyeh_core_ff_field( 'input_text', 'organization', 'سازمان / شهرداری' ),
				atiyeh_core_ff_field( 'input_text', 'phone', 'شماره تماس', true, array( 'type' => 'tel', 'ltr' => true ) ),
				atiyeh_core_ff_field( 'select', 'service', 'نوع خدمت', true, array( 'options' => array( 'پلاک کدپستی', 'بهنگام‌سازی کدپستی', 'آرشیو دیجیتال اسناد', 'سایر' ) ) ),
				atiyeh_core_ff_field( 'textarea', 'description', 'شرح درخواست', false, array( 'wide' => true ) ),
			),
			'ارسال درخواست',
			'درخواست شما ثبت شد. کارشناسان ما برای بررسی و ارائه پیشنهاد با شما تماس می‌گیرند.',
		),
		'contact' => array(
			'تماس با ما',
			array(
				atiyeh_core_ff_field( 'input_text', 'full_name', 'نام و نام خانوادگی', true ),
				atiyeh_core_ff_field( 'input_text', 'phone', 'شماره تماس', true, array( 'type' => 'tel', 'ltr' => true ) ),
				atiyeh_core_ff_field( 'input_email', 'email', 'ایمیل', false, array( 'ltr' => true, 'wide' => true ) ),
				atiyeh_core_ff_field( 'textarea', 'message', 'پیام', true, array( 'wide' => true, 'rows' => 5 ) ),
			),
			'ارسال پیام',
			'پیام شما ثبت شد. به‌زودی با شما تماس می‌گیریم.',
		),
	);
}

/** Creates or updates the forms; returns key => form ID. */
function atiyeh_core_ensure_forms(): array {
	if ( ! class_exists( '\FluentForm\App\Models\Form' ) ) {
		return array();
	}
	// Fluent Forms adds two English sample forms on activation; drop them while they have no entries.
	foreach ( \FluentForm\App\Models\Form::whereIn( 'title', array( 'Contact Form Demo', 'Subscription Form' ) )->get() as $demo ) {
		if ( ! \FluentForm\App\Models\Submission::where( 'form_id', $demo->id )->count() ) {
			\FluentForm\App\Models\FormMeta::where( 'form_id', $demo->id )->delete();
			\FluentForm\App\Models\Form::where( 'id', $demo->id )->delete();
		}
	}
	$out = array();
	foreach ( atiyeh_core_ff_definitions() as $key => [ $title, $fields, $submit, $confirm ] ) {
		$json = wp_json_encode(
			array(
				'fields'       => $fields,
				'submitButton' => array(
					'uniqElKey'  => 'el_atiyeh_submit_' . $key,
					'element'    => 'button',
					'attributes' => array( 'type' => 'submit', 'class' => 'btn btn--primary' ),
					'settings'   => array(
						'align'            => 'right',
						'button_style'     => '',
						'container_class'  => 'ff-wide',
						'help_message'     => '',
						'background_color' => '',
						'button_size'      => 'md',
						'color'            => '',
						'button_ui'        => array( 'type' => 'default', 'text' => $submit, 'img_url' => '' ),
					),
					'editor_options' => array( 'title' => 'Submit Button' ),
				),
			),
			JSON_UNESCAPED_UNICODE
		);
		$id   = (int) get_option( "atiyeh_form_{$key}" );
		$form = $id ? \FluentForm\App\Models\Form::find( $id ) : null;
		if ( $form ) {
			\FluentForm\App\Models\Form::where( 'id', $id )->update( array( 'title' => $title, 'form_fields' => $json, 'status' => 'published' ) );
		} else {
			$id = (int) \FluentForm\App\Models\Form::insertGetId(
				array(
					'title'       => $title,
					'form_fields' => $json,
					'status'      => 'published',
					'has_payment' => 0,
					'type'        => 'form',
					'created_by'  => get_current_user_id() ? get_current_user_id() : 1,
					'created_at'  => current_time( 'mysql' ),
					'updated_at'  => current_time( 'mysql' ),
				)
			);
			update_option( "atiyeh_form_{$key}", $id );
		}
		$settings = \FluentForm\App\Models\Form::getFormsDefaultSettings();
		$settings['confirmation']['messageToShow']        = $confirm;
		$settings['confirmation']['samePageFormBehavior'] = 'hide_form';
		$settings['layout']['labelPlacement']             = 'top';
		\FluentForm\App\Models\FormMeta::persist( $id, 'formSettings', $settings );
		\FluentForm\App\Models\FormMeta::persist( $id, 'template_name', 'blank_form' );
		$out[ $key ] = $id;
	}
	return $out;
}

/** Rendered form HTML by key (empty when Fluent Forms is inactive or the form is missing). */
function atiyeh_core_form_html( string $key ): string {
	$id = (int) get_option( "atiyeh_form_{$key}" );
	if ( ! $id || ! shortcode_exists( 'fluentform' ) ) {
		return '';
	}
	return do_shortcode( '[fluentform id="' . $id . '"]' );
}

// Theme hooks: the homepage request section asks for its form through this filter.
add_filter( 'atiyeh_request_form_html', static fn(): string => atiyeh_core_form_html( 'request' ) );
