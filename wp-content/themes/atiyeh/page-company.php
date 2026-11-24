<?php
/**
 * «معرفی شرکت»: editable intro beside an image, a mission quote, why-us cards.
 *
 * @package Atiyeh
 */

get_header();
while ( have_posts() ) :
	the_post();
	get_template_part( 'template-parts/page-hero', null, array( 'kicker' => 'درباره ما', 'desc' => 'از ۱۳۸۸ در طرح‌های آماری، اسکن و آرشیو الکترونیک اسناد، بهنگام‌سازی کدپستی و پلاک‌های کدپستی هوشمند؛ با پشتوانه گواهینامه‌های معتبر.', 'img' => 'hero-1-green-city.webp' ) );
	$atiyeh_intro = atiyeh_page_intro();
	?>
	<section class="container split-intro split-intro--card">
		<div class="split-intro__text rvl">
			<span class="kicker">داستان ما</span>
			<h2 class="section-title">از نشانی هر ملک تا هر برگ سند</h2>
			<div class="prose split-intro__prose"><?php echo $atiyeh_intro ? $atiyeh_intro : '<p>[ متن معرفی شرکت ]</p>'; // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
			<dl class="facts">
				<div class="facts__item"><dt>آغاز فعالیت</dt><dd>۱۳۸۸</dd></div>
				<div class="facts__item"><dt>شماره ثبت</dt><dd><?php echo esc_html( atiyeh_contact( 'regno' ) ); ?></dd></div>
				<div class="facts__item"><dt>دفتر مرکزی</dt><dd>اصفهان</dd></div>
				<div class="facts__item"><dt>مدیرعامل</dt><dd><?php echo esc_html( atiyeh_contact( 'ceo' ) ); ?></dd></div>
			</dl>
			<?php get_template_part( 'template-parts/blocks/profile-download' ); ?>
		</div>
		<div class="arch-img rvr">
			<div class="arch-img__frame rvc"><img class="pimg" src="<?php echo esc_url( atiyeh_asset( 'img/plaque-install-officials.webp' ) ); ?>" alt="نصب پلاک کدپستی توسط تیم آمار پژوهان پارسیان" width="1280" height="960" loading="lazy" decoding="async"></div>
			<div class="arch-img__ball float" aria-hidden="true"></div>
			<div class="arch-img__frame2 spin" aria-hidden="true"></div>
		</div>
	</section>

	<section class="container quote-band rvs">
		<div class="quote-band__mark" aria-hidden="true">«</div>
		<p class="quote-band__text split-me">نشانی دقیق و اسناد ماندگار، برای شهری منظم‌تر.</p>
		<span class="quote-band__by">رسالت آمار پژوهان پارسیان</span>
	</section>

	<?php
	get_template_part(
		'template-parts/blocks/icon-cards',
		null,
		array(
			'items'  => atiyeh_reasons(),
			'kicker' => 'چرا ما',
			'title'  => 'شش دلیل برای انتخاب ما',
			'cols'   => 3,
		)
	);
	get_template_part( 'template-parts/blocks/about-nav' );
	get_template_part( 'template-parts/blocks/cta' );
endwhile;
get_footer();
