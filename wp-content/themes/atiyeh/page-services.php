<?php
/**
 * «خدمات»: the two services in detail (image / document-stack visual + feature cards),
 * the four-step process, FAQ, and a call to action.
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
			'kicker' => 'پلاک کدپستی · آرشیو دیجیتال اسناد',
			'desc'   => 'دو خدمت تخصصی در یک مجموعه؛ از نصب پلاک بر سر در ساختمان‌ها تا آرشیو دیجیتال قابل جستجو.',
			'img'    => 'project-map-update.webp',
		)
	);
	$atiyeh_intro = atiyeh_page_intro();
	if ( $atiyeh_intro ) {
		echo '<div class="container content-card prose rv">' . $atiyeh_intro . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput
	}
	?>
	<nav class="container svc-tabs rv" aria-label="پرش به خدمت">
		<?php foreach ( atiyeh_service_blocks() as $atiyeh_s ) : ?>
			<a class="chip svc-tabs__a" href="#<?php echo esc_attr( $atiyeh_s['id'] ); ?>"><?php echo esc_html( $atiyeh_s['title'] ); ?></a>
		<?php endforeach; ?>
	</nav>

	<?php foreach ( atiyeh_service_blocks() as $atiyeh_i => $atiyeh_s ) : ?>
		<section class="container svc<?php echo $atiyeh_i ? ' svc--alt' : ''; ?>" id="<?php echo esc_attr( $atiyeh_s['id'] ); ?>" aria-labelledby="svc-<?php echo esc_attr( $atiyeh_s['id'] ); ?>">
			<div class="svc__visual <?php echo $atiyeh_i ? 'rvl' : 'rvr'; ?>" aria-hidden="true">
				<div class="svc__blob">
					<img class="pimg" src="<?php echo esc_url( atiyeh_asset( 'img/' . $atiyeh_s['img'] ) ); ?>" alt="" width="600" height="600" loading="lazy" decoding="async">
				</div>
				<?php if ( 'archive' === $atiyeh_s['id'] ) : ?>
					<div class="doc-stack svc__docs">
						<div class="doc-stack__sheet doc-stack__sheet--back"></div>
						<div class="doc-stack__sheet doc-stack__sheet--mid"></div>
						<div class="doc-stack__sheet doc-stack__sheet--front">
							<i class="doc-stack__head"></i><i></i><i></i><i class="w80"></i><i></i><i class="w55"></i>
							<i class="doc-stack__scan"></i>
						</div>
					</div>
				<?php else : ?>
					<div class="plaque-mock float">
						<span class="plaque-mock__no">[ ۰۰ ]</span>
						<span class="plaque-mock__code" dir="ltr">[ ۰۰۰۰۰-۰۰۰۰۰ ]</span>
					</div>
				<?php endif; ?>
				<div class="svc__orbit spin"></div>
				<div class="svc__dot drift"></div>
			</div>
			<div class="svc__text <?php echo $atiyeh_i ? 'rvr' : 'rvl'; ?>">
				<span class="svc__badge"><?php echo esc_html( $atiyeh_s['badge'] ); ?></span>
				<h2 class="section-title" id="svc-<?php echo esc_attr( $atiyeh_s['id'] ); ?>"><?php echo esc_html( $atiyeh_s['title'] ); ?></h2>
				<p class="svc__lead"><?php echo esc_html( $atiyeh_s['lead'] ); ?></p>
				<p class="mut"><?php echo esc_html( $atiyeh_s['text'] ); ?></p>
				<ul class="svc__features" role="list">
					<?php foreach ( $atiyeh_s['features'] as $atiyeh_k => [ $atiyeh_n, $atiyeh_t, $atiyeh_d ] ) : ?>
						<li class="svc-feature rv">
							<span class="svc-feature__icon" aria-hidden="true"><?php echo atiyeh_icon( $atiyeh_s['icons'][ $atiyeh_k ] ?? 'check', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
							<span><b class="svc-feature__title"><?php echo esc_html( $atiyeh_t ); ?></b><span class="mut svc-feature__desc"><?php echo esc_html( $atiyeh_d ); ?></span></span>
						</li>
					<?php endforeach; ?>
				</ul>
				<a class="btn btn--primary" href="<?php echo esc_url( atiyeh_page_url( 'request', '/#request' ) ); ?>">درخواست <?php echo esc_html( $atiyeh_s['title'] ); ?> <span class="ar" aria-hidden="true">←</span></a>
			</div>
		</section>
	<?php endforeach; ?>

	<div class="services-process"><?php get_template_part( 'template-parts/home/process' ); ?></div>

	<section class="container faq" aria-labelledby="faq-title">
		<div class="faq__head rv">
			<span class="kicker">پرسش‌های متداول</span>
			<h2 class="section-title" id="faq-title">پاسخ پرسش‌های رایج</h2>
			<p class="mut">اگر پاسخ پرسش خود را پیدا نکردید، از طریق صفحه تماس با ما در ارتباط باشید.</p>
			<div class="faq__art float" aria-hidden="true">؟</div>
		</div>
		<div class="faq__list">
			<?php foreach ( atiyeh_faq() as $atiyeh_k => [ $atiyeh_q, $atiyeh_a ] ) : ?>
				<details class="job faq__item rv"<?php echo 0 === $atiyeh_k ? ' open' : ''; ?>>
					<summary class="job__head"><b class="job__title"><?php echo esc_html( $atiyeh_q ); ?></b><span class="job__toggle" aria-hidden="true"></span></summary>
					<div class="job__body"><p class="mut"><?php echo esc_html( $atiyeh_a ); ?></p></div>
				</details>
			<?php endforeach; ?>
		</div>
	</section>
	<?php
	get_template_part( 'template-parts/blocks/cta' );
endwhile;
get_footer();
