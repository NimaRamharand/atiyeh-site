<?php
/**
 * Site header: floating glass pill with logo, primary menu, search and CTA.
 *
 * @package Atiyeh
 */

$atiyeh_request = get_page_by_path( 'request' );
$atiyeh_cta_url = $atiyeh_request ? get_permalink( $atiyeh_request ) : home_url( '/#request' );
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main">پرش به محتوای اصلی</a>
<div class="scroll-progress" aria-hidden="true"><i data-scroll-progress></i></div>

<header class="site-header" data-header>
	<div class="site-header__bar in">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<span class="brand__mark">
				<?php
				if ( has_custom_logo() ) {
					echo wp_get_attachment_image( (int) get_theme_mod( 'custom_logo' ), 'thumbnail', false, array( 'alt' => '' ) );
				} else {
					echo atiyeh_icon( 'logo', 24 ); // phpcs:ignore WordPress.Security.EscapeOutput
				}
				?>
			</span>
			<b class="brand__name"><?php bloginfo( 'name' ); ?></b>
		</a>

		<nav class="site-nav" id="site-nav" aria-label="منوی اصلی" data-nav>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'site-nav__list',
					'fallback_cb'    => false,
					'depth'          => 1,
				)
			);
			?>
			<a class="btn btn--accent site-nav__cta-mobile" href="<?php echo esc_url( $atiyeh_cta_url ); ?>">درخواست دمو</a>
		</nav>

		<div class="site-header__actions">
			<button class="icon-btn theme-toggle" type="button" aria-label="حالت شب" aria-pressed="false" data-theme-toggle>
				<?php echo atiyeh_icon( 'moon', 20 ) . atiyeh_icon( 'sun', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</button>
			<button class="icon-btn" type="button" aria-label="جستجو" aria-expanded="false" aria-controls="site-search" data-search-toggle>
				<?php echo atiyeh_icon( 'search', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</button>
			<a class="btn btn--accent site-header__cta" href="<?php echo esc_url( $atiyeh_cta_url ); ?>">درخواست دمو</a>
			<button class="icon-btn site-header__burger" type="button" aria-label="باز کردن منو" aria-expanded="false" aria-controls="site-nav" data-nav-toggle>
				<?php echo atiyeh_icon( 'menu', 22 ) . atiyeh_icon( 'close', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</button>
		</div>
	</div>

	<div class="site-search" id="site-search" hidden data-search>
		<?php get_search_form(); ?>
	</div>
</header>

<main id="main" tabindex="-1">
