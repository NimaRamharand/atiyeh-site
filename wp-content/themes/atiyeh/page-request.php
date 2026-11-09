<?php
/**
 * «درخواست خدمات»: the homepage request card on its own page.
 *
 * @package Atiyeh
 */

get_header();
while ( have_posts() ) :
	the_post();
	get_template_part( 'template-parts/page-hero', null, array( 'kicker' => 'پلاک کدپستی · آرشیو دیجیتال اسناد' ) );
	echo '<div class="section-pad request-page">';
	get_template_part( 'template-parts/home/request' );
	echo '</div>';
endwhile;
get_footer();
