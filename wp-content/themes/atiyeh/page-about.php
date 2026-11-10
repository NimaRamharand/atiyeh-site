<?php
/**
 * «درباره ما»: intro (editable page content) with an image collage, mission & vision, values,
 * the two services, links to the four sub-pages, and a closing call to action.
 *
 * @package Atiyeh
 */

get_header();
while ( have_posts() ) :
	the_post();
	get_template_part(
		'template-parts/page-hero',
		null,
		array(
			'kicker' => 'درباره ما',
			'desc'   => 'مجموعه‌ای تخصصی در دو حوزه: پلاک کدپستی و آرشیو دیجیتال اسناد؛ با تمرکز بر دقت میدانی و نظم اطلاعات.',
			'img'    => 'careers-team.webp',
		)
	);
	$atiyeh_intro = atiyeh_page_intro();
	?>
	<section class="container split-intro">
		<div class="collage rvr" aria-hidden="true">
			<div class="collage__big rvc"><img class="pimg" src="<?php echo esc_url( atiyeh_asset( 'img/hero-1-green-city.webp' ) ); ?>" alt="" width="700" height="700" loading="lazy" decoding="async"></div>
			<div class="collage__small rvc"><img class="pimg" src="<?php echo esc_url( atiyeh_asset( 'img/project-postal-plaque.webp' ) ); ?>" alt="" width="360" height="450" loading="lazy" decoding="async"></div>
			<div class="collage__badge float"><b>۲</b><span>خدمت تخصصی</span></div>
			<div class="collage__ring spin"></div>
		</div>
		<div class="split-intro__text rvl">
			<span class="kicker">ما که هستیم</span>
			<h2 class="section-title">نظم در نشانی‌ها، ماندگاری در اسناد</h2>
			<div class="prose split-intro__prose"><?php echo $atiyeh_intro ? $atiyeh_intro : '<p>[ متن معرفی شرکت ]</p>'; // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
			<ul class="ticks" role="list">
				<li><?php echo atiyeh_icon( 'check', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>اجرای کامل پروژه با یک تیم، از برداشت تا تحویل</li>
				<li><?php echo atiyeh_icon( 'check', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>کنترل کیفیت چندمرحله‌ای برای هر پلاک و هر سند</li>
				<li><?php echo atiyeh_icon( 'check', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>گزارش‌دهی منظم و شفاف به کارفرما</li>
			</ul>
		</div>
	</section>

	<section class="container mv">
		<article class="mv__card mv__card--mission rvs">
			<span class="mv__icon float" aria-hidden="true"><?php echo atiyeh_icon( 'target', 30 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
			<span class="kicker kicker--pale">رسالت ما</span>
			<h2 class="mv__title">نشانی دقیق و اسناد ماندگار، برای شهری منظم‌تر</h2>
			<p>کمک به شهرداری‌ها و سازمان‌ها برای داشتن نشانی‌های درست و اسنادی که همیشه در دسترس‌اند؛ با اجرای دقیق، سریع و قابل پیگیری.</p>
		</article>
		<article class="mv__card mv__card--vision rvs">
			<span class="mv__icon float2" aria-hidden="true"><?php echo atiyeh_icon( 'growth', 30 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
			<span class="kicker">چشم‌انداز</span>
			<h2 class="mv__title">همراهی مطمئن برای هر پروژه نشانی و آرشیو</h2>
			<p>تبدیل شدن به انتخاب نخست کارفرمایان در پروژه‌های پلاک کدپستی و دیجیتال‌سازی اسناد، با تکیه بر کیفیت و پایبندی به تعهدات.</p>
		</article>
	</section>

	<?php
	get_template_part(
		'template-parts/blocks/icon-cards',
		null,
		array(
			'items'  => atiyeh_values(),
			'kicker' => 'ارزش‌های ما',
			'title'  => 'آنچه ما را متمایز می‌کند',
			'desc'   => 'چهار اصل که در همه پروژه‌ها، کوچک یا بزرگ، به آن پایبندیم.',
		)
	);
	?>

	<section class="container duo">
		<?php foreach ( atiyeh_service_blocks() as $atiyeh_i => $atiyeh_s ) : ?>
			<a class="duo__card lift zoom <?php echo $atiyeh_i ? 'rvl' : 'rvr'; ?>" href="<?php echo esc_url( atiyeh_page_url( 'services', '/#products' ) . '#' . $atiyeh_s['id'] ); ?>">
				<img class="zi pimg duo__img" src="<?php echo esc_url( atiyeh_asset( 'img/' . $atiyeh_s['img'] ) ); ?>" alt="" width="600" height="450" loading="lazy" decoding="async">
				<span class="duo__shade" aria-hidden="true"></span>
				<span class="duo__body">
					<span class="duo__badge"><?php echo esc_html( $atiyeh_s['badge'] ); ?></span>
					<b class="duo__title"><?php echo esc_html( $atiyeh_s['title'] ); ?></b>
					<span class="duo__desc"><?php echo esc_html( $atiyeh_s['lead'] ); ?></span>
					<span class="duo__more">جزئیات خدمت <span aria-hidden="true">←</span></span>
				</span>
			</a>
		<?php endforeach; ?>
	</section>

	<?php
	get_template_part( 'template-parts/blocks/about-nav' );
	get_template_part( 'template-parts/blocks/cta' );
endwhile;
get_footer();
