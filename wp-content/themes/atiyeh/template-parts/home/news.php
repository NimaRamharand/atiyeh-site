<?php
/**
 * Home: latest news list + «گزیده اخبار روز» side card.
 *
 * @package Atiyeh
 */

?>
<section class="home-news container" id="news">
	<div class="home-news__main">
		<h2 class="home-news__title rv">آنچه در شرکت رخ داده است</h2>
		<ul class="home-news__list" role="list">
			<?php foreach ( atiyeh_home_news() as $n ) : ?>
				<li class="rv">
					<a class="sweep news-row" href="<?php echo esc_url( $n['url'] ); ?>">
						<span class="zoom news-row__img"><img class="zi pimg" src="<?php echo esc_url( $n['img'] ); ?>" alt="" width="110" height="84" loading="lazy" decoding="async"></span>
						<span>
							<b class="news-row__title"><?php echo esc_html( atiyeh_nb( $n['title'] ) ); ?></b>
							<span class="mut news-row__meta"><?php echo esc_html( $n['date'] ); ?> · ادامه مطلب <span aria-hidden="true">←</span></span>
						</span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
	<aside class="home-news__side rvl" aria-labelledby="digest-title">
		<h3 class="home-news__side-title" id="digest-title">گزیده اخبار روز</h3>
		<ul class="digest" role="list">
			<li>[ تیتر خبر ۱ ]</li>
			<li>[ تیتر خبر ۲ ]</li>
			<li>[ تیتر خبر ۳ ]</li>
		</ul>
	</aside>
</section>
