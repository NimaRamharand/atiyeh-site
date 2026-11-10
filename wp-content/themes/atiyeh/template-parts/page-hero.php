<?php
/**
 * Inner-page hero: breadcrumb, title (words rise in), optional description and an image in the
 * homepage's blob style, with floating shapes that follow the mouse.
 *
 * @package Atiyeh
 *
 * @var array $args { title?: string, kicker?: string, desc?: string, img?: string (file in assets/img) }
 */

$atiyeh_title  = $args['title'] ?? get_the_title();
$atiyeh_kicker = $args['kicker'] ?? '';
$atiyeh_desc   = $args['desc'] ?? '';
$atiyeh_img    = $args['img'] ?? '';
$atiyeh_crumbs = array( array( 'خانه', home_url( '/' ) ) );
if ( is_page() ) {
	foreach ( array_reverse( get_post_ancestors( get_the_ID() ) ) as $atiyeh_anc ) {
		$atiyeh_crumbs[] = array( get_the_title( $atiyeh_anc ), get_permalink( $atiyeh_anc ) );
	}
} elseif ( is_singular( 'project' ) || is_tax( 'service' ) ) {
	$atiyeh_crumbs[] = array( 'پروژه‌ها', get_post_type_archive_link( 'project' ) );
}
?>
<section class="page-hero<?php echo $atiyeh_img ? ' page-hero--media' : ''; ?>">
	<div class="deco deco--ring spin" aria-hidden="true"></div>
	<div class="deco deco--diamond float" aria-hidden="true"></div>
	<div class="page-hero__dots drift" aria-hidden="true"></div>
	<div class="page-hero__inner container">
		<div class="page-hero__text">
			<nav class="crumbs" aria-label="مسیر صفحه">
				<?php foreach ( $atiyeh_crumbs as [ $atiyeh_label, $atiyeh_url ] ) : ?>
					<a href="<?php echo esc_url( (string) $atiyeh_url ); ?>"><?php echo esc_html( $atiyeh_label ); ?></a><span aria-hidden="true">/</span>
				<?php endforeach; ?>
				<span aria-current="page"><?php echo esc_html( wp_strip_all_tags( $atiyeh_title ) ); ?></span>
			</nav>
			<?php if ( $atiyeh_kicker ) : ?>
				<span class="pill"><i class="live" aria-hidden="true"></i><?php echo esc_html( $atiyeh_kicker ); ?></span>
			<?php endif; ?>
			<h1 class="page-hero__title"><?php echo esc_html( atiyeh_nb( wp_strip_all_tags( $atiyeh_title ) ) ); ?></h1>
			<?php if ( $atiyeh_desc ) : ?>
				<p class="page-hero__desc"><?php echo esc_html( $atiyeh_desc ); ?></p>
			<?php endif; ?>
		</div>
		<?php if ( $atiyeh_img ) : ?>
			<div class="page-hero__visual" aria-hidden="true">
				<div class="page-hero__blob"><img src="<?php echo esc_url( atiyeh_asset( 'img/' . $atiyeh_img ) ); ?>" alt="" class="pimg" width="520" height="520" fetchpriority="high" decoding="async"></div>
				<div class="page-hero__orbit spin"></div>
				<div class="page-hero__shape page-hero__shape--sq float"></div>
				<div class="page-hero__shape page-hero__shape--dot float2"></div>
			</div>
		<?php endif; ?>
	</div>
	<?php atiyeh_wave( 'M0 120V70C180 10 360 0 560 40S980 120 1180 80 1360 20 1440 40V120Z', 'var(--c-page)', 'wave--bottom', 120 ); ?>
</section>
