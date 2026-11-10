<?php
/**
 * Links between the «درباره ما» sub-pages (current page highlighted).
 *
 * @package Atiyeh
 */

$atiyeh_current = get_post_field( 'post_name', get_the_ID() );
?>
<section class="container about-nav" aria-labelledby="about-nav-title">
	<h2 class="about-nav__title rv" id="about-nav-title">بیشتر درباره ما</h2>
	<ul class="about-nav__grid" role="list">
		<?php foreach ( atiyeh_about_children() as $atiyeh_slug => [ $atiyeh_label, $atiyeh_icon, $atiyeh_desc ] ) : ?>
			<?php $atiyeh_on = $atiyeh_slug === $atiyeh_current; ?>
			<li class="rv">
				<a class="sweep quick-card<?php echo $atiyeh_on ? ' is-current' : ''; ?>" href="<?php echo esc_url( atiyeh_page_url( "about/{$atiyeh_slug}", '/about/' ) ); ?>"<?php echo $atiyeh_on ? ' aria-current="page"' : ''; ?>>
					<span class="bd quick-card__icon" aria-hidden="true"><?php echo atiyeh_icon( $atiyeh_icon, 24 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<b class="quick-card__title"><?php echo esc_html( $atiyeh_label ); ?></b>
					<span class="mut quick-card__desc"><?php echo esc_html( $atiyeh_desc ); ?></span>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</section>
