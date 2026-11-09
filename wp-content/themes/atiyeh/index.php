<?php
/**
 * Fallback template: news list, archives, search results.
 *
 * @package Atiyeh
 */

get_header();

if ( is_search() ) {
	$atiyeh_title = sprintf( 'نتایج جستجو برای «%s»', get_search_query() );
} elseif ( is_home() ) {
	$atiyeh_title = get_the_title( (int) get_option( 'page_for_posts' ) ) ?: 'اخبار';
} elseif ( is_archive() ) {
	$atiyeh_title = wp_strip_all_tags( get_the_archive_title() );
} else {
	$atiyeh_title = get_bloginfo( 'name' );
}
get_template_part( 'template-parts/page-hero', null, array( 'title' => $atiyeh_title ) );
?>
<section class="container section-pad">
	<?php if ( have_posts() ) : ?>
		<div class="post-list">
			<?php
			$atiyeh_i = 0;
			while ( have_posts() ) :
				the_post();
				?>
				<a class="sweep news-item <?php echo esc_attr( $atiyeh_i++ % 2 ? 'news-item--alt' : '' ); ?>" href="<?php the_permalink(); ?>">
					<?php if ( has_post_thumbnail() ) : ?>
						<span class="news-item__img zoom"><?php the_post_thumbnail( 'atiyeh-thumb', array( 'class' => 'zi pimg', 'alt' => '' ) ); ?></span>
					<?php endif; ?>
					<span>
						<b class="news-item__title"><?php the_title(); ?></b>
						<span class="mut news-item__meta"><?php echo esc_html( atiyeh_jdate( get_post_timestamp() ) ); ?> · ادامه مطلب ←</span>
					</span>
				</a>
			<?php endwhile; ?>
		</div>
		<div class="pagination"><?php the_posts_pagination( array( 'prev_text' => '→ قبلی', 'next_text' => 'بعدی ←' ) ); ?></div>
	<?php else : ?>
		<div class="content-card prose"><p>موردی یافت نشد.</p><?php get_search_form(); ?></div>
	<?php endif; ?>
</section>
<?php
get_footer();
