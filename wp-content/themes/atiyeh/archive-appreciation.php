<?php
/**
 * «حسن انجام کار» gallery (/appreciations/): plaques with client and year; click opens a lightbox.
 *
 * @package Atiyeh
 */

get_header();
get_template_part(
	'template-parts/page-hero',
	null,
	array(
		'title'  => 'لوح‌های سپاس',
		'kicker' => 'حسن انجام کار',
		'desc'   => 'سندی بر تخصص و تعهد؛ تقدیرنامه‌های کارفرمایان پس از اجرای پروژه‌ها.',
	)
);
?>
<section class="container section-pad">
	<?php if ( have_posts() ) : ?>
		<ul class="plaques" role="list">
			<?php
			while ( have_posts() ) :
				the_post();
				$atiyeh_full = get_the_post_thumbnail_url( null, 'large' );
				$atiyeh_meta = array_filter( array( get_post_meta( get_the_ID(), '_atiyeh_client', true ), get_post_meta( get_the_ID(), '_atiyeh_year', true ) ) );
				?>
				<li class="plaque rv">
					<a class="plaque__link lift" href="<?php echo esc_url( $atiyeh_full ? $atiyeh_full : get_permalink() ); ?>" data-lightbox data-caption="<?php echo esc_attr( get_the_title() ); ?>">
						<span class="plaque__img zoom"><?php the_post_thumbnail( 'atiyeh-card', array( 'class' => 'zi pimg', 'alt' => get_the_title() ) ); ?></span>
						<span class="plaque__body">
							<b class="plaque__title"><?php the_title(); ?></b>
							<?php if ( $atiyeh_meta ) : ?>
								<span class="mut plaque__meta"><?php echo esc_html( atiyeh_nb( implode( ' · ', $atiyeh_meta ) ) ); ?></span>
							<?php endif; ?>
						</span>
					</a>
				</li>
			<?php endwhile; ?>
		</ul>
	<?php else : ?>
		<div class="content-card prose"><p>هنوز لوحی ثبت نشده است.</p></div>
	<?php endif; ?>
</section>
<dialog class="lightbox" data-lightbox-dialog aria-label="نمایش تصویر">
	<button type="button" class="icon-btn lightbox__close" aria-label="بستن" data-lightbox-close><?php echo atiyeh_icon( 'close', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></button>
	<img src="" alt="" data-lightbox-img>
	<p class="lightbox__caption" data-lightbox-caption></p>
</dialog>
<?php
get_footer();
