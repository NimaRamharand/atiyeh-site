<?php
/**
 * Post types: project, client, appreciation, job — and the `service` taxonomy for projects.
 *
 * @package AtiyehCore
 */

/** Service terms used by the project filter: slug => name. Only the company's two services. */
function atiyeh_core_service_terms(): array {
	return array(
		'plaque'  => 'پلاک کدپستی',
		'archive' => 'آرشیو دیجیتال',
		'update'  => 'بهنگام‌سازی',
	);
}

/** Registers all content types (also called on activation). */
function atiyeh_core_register_types(): void {
	$labels = static fn( string $one, string $many, string $add ): array => array(
		'name'               => $many,
		'singular_name'      => $one,
		'menu_name'          => $many,
		'add_new'            => $add,
		'add_new_item'       => $add,
		'edit_item'          => "ویرایش {$one}",
		'new_item'           => $add,
		'view_item'          => "مشاهده {$one}",
		'search_items'       => "جستجوی {$many}",
		'not_found'          => "{$one} یافت نشد",
		'not_found_in_trash' => "{$one} در زباله‌دان یافت نشد",
		'all_items'          => "همه {$many}",
		'featured_image'     => 'تصویر',
		'set_featured_image' => 'انتخاب تصویر',
	);

	register_post_type(
		'project',
		array(
			'labels'       => $labels( 'پروژه', 'پروژه‌ها', 'افزودن پروژه' ),
			'public'       => true,
			'show_in_rest' => true,
			'has_archive'  => 'projects',
			'rewrite'      => array( 'slug' => 'projects', 'with_front' => false ),
			'menu_icon'    => 'dashicons-location-alt',
			'menu_position' => 21,
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
		)
	);

	register_post_type(
		'client',
		array(
			'labels'             => $labels( 'کارفرما', 'کارفرمایان', 'افزودن کارفرما' ) + array( 'featured_image' => 'لوگو', 'set_featured_image' => 'انتخاب لوگو' ),
			'public'             => false,
			'show_ui'            => true,
			'show_in_rest'       => true,
			'publicly_queryable' => false,
			'menu_icon'          => 'dashicons-building',
			'menu_position'      => 22,
			'supports'           => array( 'title', 'thumbnail', 'page-attributes' ),
		)
	);

	register_post_type(
		'appreciation',
		array(
			'labels'        => $labels( 'لوح سپاس', 'لوح‌های سپاس', 'افزودن لوح سپاس' ),
			'public'        => true,
			'show_in_rest'  => true,
			'has_archive'   => 'appreciations',
			'rewrite'       => array( 'slug' => 'appreciations', 'with_front' => false ),
			'menu_icon'     => 'dashicons-awards',
			'menu_position' => 23,
			'supports'      => array( 'title', 'thumbnail', 'page-attributes' ),
		)
	);

	register_post_type(
		'job',
		array(
			'labels'             => $labels( 'فرصت شغلی', 'فرصت‌های شغلی', 'افزودن فرصت شغلی' ),
			'public'             => false,
			'show_ui'            => true,
			'show_in_rest'       => true,
			'publicly_queryable' => false,
			'menu_icon'          => 'dashicons-groups',
			'menu_position'      => 24,
			'supports'           => array( 'title', 'editor', 'page-attributes' ),
		)
	);

	register_taxonomy(
		'service',
		array( 'project' ),
		array(
			'labels'            => array(
				'name'          => 'نوع خدمت',
				'singular_name' => 'نوع خدمت',
				'menu_name'     => 'نوع خدمت',
				'all_items'     => 'همه',
				'edit_item'     => 'ویرایش نوع خدمت',
				'add_new_item'  => 'افزودن نوع خدمت',
			),
			'hierarchical'      => true,
			'public'            => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'service' ),
		)
	);
}
add_action( 'init', 'atiyeh_core_register_types' );

/** Ensures the service terms exist (called by the demo seeder). */
function atiyeh_core_ensure_service_terms(): void {
	foreach ( atiyeh_core_service_terms() as $slug => $name ) {
		if ( ! term_exists( $slug, 'service' ) ) {
			wp_insert_term( $name, 'service', array( 'slug' => $slug ) );
		}
	}
}

// Archives list everything in admin order (menu_order), no paging for these small sets.
add_action(
	'pre_get_posts',
	static function ( WP_Query $q ): void {
		if ( is_admin() || ! $q->is_main_query() ) {
			return;
		}
		if ( $q->is_post_type_archive( array( 'project', 'appreciation' ) ) || $q->is_tax( 'service' ) ) {
			$q->set( 'posts_per_page', 60 );
			$q->set( 'orderby', array( 'menu_order' => 'ASC', 'date' => 'DESC' ) );
		}
	}
);

// Admin list columns.
add_filter(
	'manage_job_posts_columns',
	static fn( array $c ): array => array_slice( $c, 0, 2, true ) + array( 'atiyeh_active' => 'وضعیت', 'atiyeh_city' => 'شهر' ) + $c
);
add_action(
	'manage_job_posts_custom_column',
	static function ( string $col, int $id ): void {
		if ( 'atiyeh_active' === $col ) {
			echo atiyeh_core_meta( $id, '_atiyeh_active' ) ? '✅ فعال' : '⏸ غیرفعال';
		} elseif ( 'atiyeh_city' === $col ) {
			echo esc_html( atiyeh_core_meta( $id, '_atiyeh_city' ) );
		}
	},
	10,
	2
);
add_filter(
	'manage_client_posts_columns',
	static fn( array $c ): array => array( 'cb' => $c['cb'], 'atiyeh_logo' => 'لوگو' ) + $c
);
add_action(
	'manage_client_posts_custom_column',
	static function ( string $col, int $id ): void {
		if ( 'atiyeh_logo' === $col ) {
			echo get_the_post_thumbnail( $id, array( 120, 55 ), array( 'style' => 'border:1px solid #ddd;border-radius:6px;background:#fff' ) );
		}
	},
	10,
	2
);
