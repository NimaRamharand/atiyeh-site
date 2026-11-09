<?php
/**
 * Job applications with résumé upload (PDF/DOCX, ≤ 5 MB), stored in the database.
 *
 * Built here because file upload is a Pro-only field in Fluent Forms.
 * Résumés are saved under uploads/atiyeh-private/resumes/ with random file names (plus deny rules
 * for Apache) and are downloadable only by logged-in editors through admin-post.php.
 *
 * @package AtiyehCore
 */

const ATIYEH_RESUME_MAX = 5 * MB_IN_BYTES;

/** Allowed résumé types: extension => accepted MIME types (as detected by finfo). */
function atiyeh_core_resume_types(): array {
	return array(
		'pdf'  => array( 'application/pdf' ),
		'docx' => array( 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip', 'application/octet-stream' ),
	);
}

add_action(
	'init',
	static function (): void {
		register_post_type(
			'application',
			array(
				'labels'       => array(
					'name'          => 'درخواست‌های همکاری',
					'singular_name' => 'درخواست همکاری',
					'menu_name'     => 'درخواست‌های همکاری',
					'all_items'     => 'درخواست‌های همکاری',
					'edit_item'     => 'جزئیات درخواست همکاری',
					'not_found'     => 'هنوز درخواستی ثبت نشده است',
				),
				'public'       => false,
				'show_ui'      => true,
				'show_in_menu' => 'edit.php?post_type=job',
				'show_in_rest' => false,
				'supports'     => array( 'title' ),
				'capabilities' => array( 'create_posts' => 'do_not_allow' ),
				'map_meta_cap' => true,
			)
		);
	}
);

/** Absolute path of the private résumé folder (created on demand with deny rules). */
function atiyeh_core_resume_dir(): string {
	$dir = trailingslashit( wp_upload_dir()['basedir'] ) . 'atiyeh-private/resumes';
	if ( ! is_dir( $dir ) ) {
		wp_mkdir_p( $dir );
		$root = dirname( $dir );
		file_put_contents( $root . '/.htaccess', "Require all denied\nDeny from all\n" ); // phpcs:ignore
		file_put_contents( $root . '/index.php', "<?php\n// Silence.\n" ); // phpcs:ignore
		file_put_contents( $dir . '/index.php', "<?php\n// Silence.\n" ); // phpcs:ignore
	}
	return $dir;
}

/**
 * Active job titles for the «position» select.
 *
 * @return string[]
 */
function atiyeh_core_active_job_titles(): array {
	$jobs = get_posts(
		array(
			'post_type'   => 'job',
			'numberposts' => -1,
			'orderby'     => 'menu_order',
			'order'       => 'ASC',
			'meta_key'    => '_atiyeh_active', // phpcs:ignore WordPress.DB.SlowDBQuery
			'meta_value'  => '1', // phpcs:ignore WordPress.DB.SlowDBQuery
		)
	);
	return array_map( static fn( WP_Post $p ): string => $p->post_title, $jobs );
}

/** Front-end application form (markup styled by the theme). */
function atiyeh_core_application_form(): string {
	$status = isset( $_GET['apply'] ) ? sanitize_key( $_GET['apply'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	$errors = array(
		'missing' => 'لطفاً نام، شماره تماس و فایل رزومه را وارد کنید.',
		'type'    => 'فقط فایل PDF یا DOCX پذیرفته می‌شود.',
		'size'    => 'حجم فایل رزومه باید حداکثر ۵ مگابایت باشد.',
		'upload'  => 'بارگذاری فایل انجام نشد. دوباره تلاش کنید.',
		'expired' => 'زمان فرم به پایان رسیده است. صفحه را تازه کنید و دوباره ارسال کنید.',
	);
	ob_start();
	if ( 'ok' === $status ) {
		echo '<div class="form-msg form-msg--ok" role="status" tabindex="-1" data-focus>درخواست همکاری شما ثبت شد. پس از بررسی رزومه با شما تماس گرفته می‌شود.</div>';
	} elseif ( isset( $errors[ $status ] ) ) {
		echo '<div class="form-msg form-msg--err" role="alert" tabindex="-1" data-focus>' . esc_html( $errors[ $status ] ) . '</div>';
	}
	?>
	<form class="req-form apply-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" enctype="multipart/form-data" data-resume-form>
		<input type="hidden" name="action" value="atiyeh_apply">
		<?php wp_nonce_field( 'atiyeh_apply', '_atiyeh_nonce' ); ?>
		<div class="hp-field" aria-hidden="true"><label><span class="lbl">وب‌سایت</span><input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
		<label><span class="lbl">نام و نام خانوادگی <span class="req" aria-hidden="true">*</span></span><input type="text" name="name" autocomplete="name" required></label>
		<label><span class="lbl">شماره تماس <span class="req" aria-hidden="true">*</span></span><input type="tel" name="phone" dir="ltr" autocomplete="tel" required></label>
		<label><span class="lbl">ایمیل</span><input type="email" name="email" dir="ltr" autocomplete="email"></label>
		<label><span class="lbl">موقعیت شغلی</span>
			<select name="position">
				<?php foreach ( atiyeh_core_active_job_titles() as $title ) : ?>
					<option><?php echo esc_html( $title ); ?></option>
				<?php endforeach; ?>
				<option>سایر</option>
			</select>
		</label>
		<label class="req-form__wide"><span class="lbl">فایل رزومه (PDF یا DOCX، حداکثر ۵ مگابایت) <span class="req" aria-hidden="true">*</span></span>
			<span class="file-field">
				<input type="file" name="resume" accept=".pdf,.docx,application/pdf,application/vnd.openxmlformats-officedocument.wordprocessingml.document" required data-max="<?php echo (int) ATIYEH_RESUME_MAX; ?>">
				<span class="file-field__btn" aria-hidden="true">انتخاب فایل</span>
				<span class="file-field__name" aria-hidden="true" data-file-name>فایلی انتخاب نشده است</span>
			</span>
		</label>
		<label class="req-form__wide"><span class="lbl">توضیحات</span><textarea name="message" rows="4"></textarea></label>
		<button class="btn btn--primary req-form__submit" type="submit">ارسال رزومه <span class="ar" aria-hidden="true">←</span></button>
	</form>
	<?php
	return (string) ob_get_clean();
}

/** Redirects back to the form with a status code. */
function atiyeh_core_apply_redirect( string $status ): void {
	$back = wp_get_referer() ? wp_get_referer() : home_url( '/careers/' );
	$back = remove_query_arg( 'apply', strtok( $back, '#' ) );
	wp_safe_redirect( add_query_arg( 'apply', $status, $back ) . '#apply' );
	exit;
}

/** Handles a submitted application. */
function atiyeh_core_handle_apply(): void {
	if ( ! isset( $_POST['_atiyeh_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['_atiyeh_nonce'] ), 'atiyeh_apply' ) ) {
		atiyeh_core_apply_redirect( 'expired' );
	}
	if ( ! empty( $_POST['website'] ) ) { // Honeypot: bots fill every field.
		atiyeh_core_apply_redirect( 'ok' );
	}
	$name     = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
	$phone    = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
	$email    = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$position = sanitize_text_field( wp_unslash( $_POST['position'] ?? '' ) );
	$message  = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );
	$file     = $_FILES['resume'] ?? null; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput

	if ( '' === $name || '' === $phone || ! $file || UPLOAD_ERR_NO_FILE === $file['error'] ) {
		atiyeh_core_apply_redirect( 'missing' );
	}
	if ( in_array( $file['error'], array( UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE ), true ) || $file['size'] > ATIYEH_RESUME_MAX ) {
		atiyeh_core_apply_redirect( 'size' );
	}
	if ( UPLOAD_ERR_OK !== $file['error'] || ! is_uploaded_file( $file['tmp_name'] ) ) {
		atiyeh_core_apply_redirect( 'upload' );
	}
	$ext   = strtolower( pathinfo( (string) $file['name'], PATHINFO_EXTENSION ) );
	$types = atiyeh_core_resume_types();
	$mime  = function_exists( 'finfo_open' ) ? (string) finfo_file( finfo_open( FILEINFO_MIME_TYPE ), $file['tmp_name'] ) : '';
	if ( ! isset( $types[ $ext ] ) || ( $mime && ! in_array( $mime, $types[ $ext ], true ) ) ) {
		atiyeh_core_apply_redirect( 'type' );
	}
	// A DOCX is a ZIP; a PDF starts with %PDF.
	$head = (string) file_get_contents( $file['tmp_name'], false, null, 0, 4 ); // phpcs:ignore
	if ( ( 'pdf' === $ext && '%PDF' !== $head ) || ( 'docx' === $ext && "PK\x03\x04" !== $head ) ) {
		atiyeh_core_apply_redirect( 'type' );
	}

	$stored = gmdate( 'Ymd-His' ) . '-' . wp_generate_password( 16, false ) . '.' . $ext;
	if ( ! move_uploaded_file( $file['tmp_name'], atiyeh_core_resume_dir() . '/' . $stored ) ) {
		atiyeh_core_apply_redirect( 'upload' );
	}

	$id = wp_insert_post(
		array(
			'post_type'   => 'application',
			'post_status' => 'publish',
			'post_title'  => $name . ( $position ? ' — ' . $position : '' ),
		),
		true
	);
	if ( is_wp_error( $id ) ) {
		atiyeh_core_apply_redirect( 'upload' );
	}
	$meta = array(
		'_atiyeh_name'        => $name,
		'_atiyeh_phone'       => $phone,
		'_atiyeh_email'       => $email,
		'_atiyeh_position'    => $position,
		'_atiyeh_message'     => $message,
		'_atiyeh_resume'      => $stored,
		'_atiyeh_resume_name' => sanitize_file_name( (string) $file['name'] ),
		'_atiyeh_resume_size' => (int) $file['size'],
	);
	foreach ( $meta as $k => $v ) {
		update_post_meta( $id, $k, $v );
	}
	atiyeh_core_apply_redirect( 'ok' );
}
add_action( 'admin_post_atiyeh_apply', 'atiyeh_core_handle_apply' );
add_action( 'admin_post_nopriv_atiyeh_apply', 'atiyeh_core_handle_apply' );

/** Download URL for a résumé (editors only). */
function atiyeh_core_resume_url( int $id ): string {
	return wp_nonce_url( admin_url( 'admin-post.php?action=atiyeh_resume&id=' . $id ), 'atiyeh_resume_' . $id );
}

add_action(
	'admin_post_atiyeh_resume',
	static function (): void {
		$id = isset( $_GET['id'] ) ? absint( $_GET['id'] ) : 0;
		if ( ! $id || ! current_user_can( 'edit_post', $id ) || ! wp_verify_nonce( sanitize_key( $_GET['_wpnonce'] ?? '' ), 'atiyeh_resume_' . $id ) ) {
			wp_die( 'دسترسی مجاز نیست.', '', array( 'response' => 403 ) );
		}
		$stored = basename( (string) get_post_meta( $id, '_atiyeh_resume', true ) );
		$path   = atiyeh_core_resume_dir() . '/' . $stored;
		if ( ! $stored || ! is_readable( $path ) ) {
			wp_die( 'فایل پیدا نشد.', '', array( 'response' => 404 ) );
		}
		$ext  = pathinfo( $stored, PATHINFO_EXTENSION );
		$name = (string) get_post_meta( $id, '_atiyeh_resume_name', true );
		nocache_headers();
		header( 'Content-Type: ' . ( 'pdf' === $ext ? 'application/pdf' : 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' ) );
		header( 'Content-Disposition: attachment; filename="resume-' . $id . '.' . $ext . '"; filename*=UTF-8\'\'' . rawurlencode( $name ? $name : "resume.{$ext}" ) );
		header( 'Content-Length: ' . filesize( $path ) );
		readfile( $path ); // phpcs:ignore
		exit;
	}
);

// Remove the stored file when an application is permanently deleted.
add_action(
	'before_delete_post',
	static function ( int $id ): void {
		if ( 'application' === get_post_type( $id ) ) {
			$stored = basename( (string) get_post_meta( $id, '_atiyeh_resume', true ) );
			if ( $stored && is_file( atiyeh_core_resume_dir() . '/' . $stored ) ) {
				wp_delete_file( atiyeh_core_resume_dir() . '/' . $stored );
			}
		}
	}
);

// Admin: list columns + read-only details box.
add_filter(
	'manage_application_posts_columns',
	static fn( array $c ): array => array(
		'cb'               => $c['cb'],
		'title'            => 'متقاضی',
		'atiyeh_phone'     => 'شماره تماس',
		'atiyeh_position'  => 'موقعیت شغلی',
		'atiyeh_resume'    => 'رزومه',
		'date'             => $c['date'],
	)
);
add_action(
	'manage_application_posts_custom_column',
	static function ( string $col, int $id ): void {
		match ( $col ) {
			'atiyeh_phone'    => print( '<span dir="ltr">' . esc_html( atiyeh_core_meta( $id, '_atiyeh_phone' ) ) . '</span>' ),
			'atiyeh_position' => print( esc_html( atiyeh_core_meta( $id, '_atiyeh_position' ) ) ),
			'atiyeh_resume'   => print( '<a href="' . esc_url( atiyeh_core_resume_url( $id ) ) . '">دانلود</a>' ),
			default           => null,
		};
	},
	10,
	2
);
add_action(
	'add_meta_boxes_application',
	static function (): void {
		add_meta_box(
			'atiyeh_application',
			'اطلاعات متقاضی',
			static function ( WP_Post $post ): void {
				$rows = array(
					'نام'          => atiyeh_core_meta( $post->ID, '_atiyeh_name' ),
					'شماره تماس'   => atiyeh_core_meta( $post->ID, '_atiyeh_phone' ),
					'ایمیل'        => atiyeh_core_meta( $post->ID, '_atiyeh_email' ),
					'موقعیت شغلی' => atiyeh_core_meta( $post->ID, '_atiyeh_position' ),
					'توضیحات'      => atiyeh_core_meta( $post->ID, '_atiyeh_message' ),
				);
				echo '<table class="form-table"><tbody>';
				foreach ( $rows as $label => $value ) {
					printf( '<tr><th>%s</th><td>%s</td></tr>', esc_html( $label ), nl2br( esc_html( $value ) ) );
				}
				printf(
					'<tr><th>رزومه</th><td><a class="button" href="%s">دانلود %s</a> (%s)</td></tr>',
					esc_url( atiyeh_core_resume_url( $post->ID ) ),
					esc_html( atiyeh_core_meta( $post->ID, '_atiyeh_resume_name' ) ),
					esc_html( size_format( (int) atiyeh_core_meta( $post->ID, '_atiyeh_resume_size' ) ) )
				);
				echo '</tbody></table>';
			},
			'application',
			'normal',
			'high'
		);
	}
);
