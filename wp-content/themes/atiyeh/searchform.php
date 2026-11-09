<?php
/**
 * Search form.
 *
 * @package Atiyeh
 */

$atiyeh_sid = wp_unique_id( 'search-' );
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $atiyeh_sid ); ?>">جستجو در سایت</label>
	<input type="search" id="<?php echo esc_attr( $atiyeh_sid ); ?>" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="جستجو در سایت…">
	<button class="btn btn--primary" type="submit">جستجو</button>
</form>
