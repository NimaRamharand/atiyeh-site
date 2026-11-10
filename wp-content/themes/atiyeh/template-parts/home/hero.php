<?php
/**
 * Home: hero slider — 3 slides, auto-advance 5.5 s, pauses on hover/focus, keyboard + dots.
 *
 * @package Atiyeh
 */

$atiyeh_slides = atiyeh_hero_slides();
$atiyeh_total  = count( $atiyeh_slides );
?>
<section class="hero" data-slider aria-roledescription="carousel" aria-label="معرفی خدمات" tabindex="-1">
	<div class="hero__slides" aria-live="off" data-slider-track>
		<?php foreach ( $atiyeh_slides as $i => $s ) : ?>
			<?php $atiyeh_active = 0 === $i; ?>
			<div class="hero-slide<?php echo $atiyeh_active ? ' is-active' : ''; ?><?php echo $s['docs'] ? ' has-docs' : ''; ?>"
				style="--bg:<?php echo esc_attr( $s['bg'] ); ?>;--c1:<?php echo esc_attr( $s['c1'] ); ?>;--c2:<?php echo esc_attr( $s['c2'] ); ?>"
				role="group" aria-roledescription="اسلاید"
				aria-label="<?php echo esc_attr( atiyeh_fa_num( ( $i + 1 ) . ' از ' . $atiyeh_total ) ); ?>"
				<?php echo $atiyeh_active ? '' : 'aria-hidden="true" inert'; ?> data-slide>
				<div class="hero-slide__ring spin" aria-hidden="true"></div>
				<div class="hero-slide__diamond float" aria-hidden="true"></div>
				<div class="hero-slide__dot drift" aria-hidden="true"></div>

				<div class="hero-slide__inner container">
					<div class="hero-slide__text">
						<span class="pill"><i class="live" aria-hidden="true"></i><?php echo esc_html( $s['kicker'] ); ?></span>
						<?php printf( '<%1$s class="hero-slide__title">%2$s</%1$s>', 0 === $i ? 'h1' : 'h2', esc_html( $s['title'] ) ); ?>
						<p class="hero-slide__desc"><?php echo esc_html( $s['desc'] ); ?></p>
						<div class="hero-slide__btns">
							<a class="btn btn--white hero-slide__cta" href="#products"><?php echo esc_html( $s['cta'] ); ?> <span class="ar" aria-hidden="true">←</span></a>
							<a class="btn btn--ghost" href="#request">درخواست دمو</a>
						</div>
					</div>

					<div class="hero-slide__visual" aria-hidden="true">
						<div class="hero-slide__blob">
							<img src="<?php echo esc_url( atiyeh_asset( 'img/' . $s['img'] ) ); ?>" alt="" class="pimg" width="600" height="750"
								<?php echo 0 === $i ? 'fetchpriority="high"' : 'loading="lazy"'; ?> decoding="async">
						</div>
						<div class="hero-slide__orbit spin"></div>
						<?php if ( $s['docs'] ) : ?>
							<div class="doc-stack">
								<div class="doc-stack__sheet doc-stack__sheet--back"></div>
								<div class="doc-stack__sheet doc-stack__sheet--mid"></div>
								<div class="doc-stack__sheet doc-stack__sheet--front">
									<i class="doc-stack__head"></i><i></i><i></i><i class="w80"></i><i></i><i class="w55"></i>
									<i class="doc-stack__scan"></i>
								</div>
							</div>
						<?php endif; ?>
						<div class="hero-slide__sq float"></div>
						<div class="hero-slide__circle float2"></div>
						<div class="stat-chip">
							<div class="stat-chip__top"><b><?php echo esc_html( $s['chip'] ); ?></b><span>زنده</span></div>
							<div class="stat-chip__bars bars"><i></i><i></i><i></i><i></i><i></i><i></i></div>
							<div class="stat-chip__foot">[ شاخص کلیدی ] · <b>[ ۰۰۰ ]</b></div>
						</div>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>

	<div class="hero__controls container">
		<div class="hero__dots">
			<?php foreach ( $atiyeh_slides as $i => $s ) : ?>
				<button type="button" class="hero__dot<?php echo 0 === $i ? ' is-active' : ''; ?>" aria-label="<?php echo esc_attr( 'اسلاید ' . atiyeh_fa_num( $i + 1 ) ); ?>"<?php echo 0 === $i ? ' aria-current="true"' : ''; ?> data-slide-to="<?php echo (int) $i; ?>"></button>
			<?php endforeach; ?>
			<span class="hero__counter" dir="ltr" data-slider-counter><?php echo esc_html( atiyeh_fa_num( '1 / ' . $atiyeh_total ) ); ?></span>
			<button type="button" class="hero__pause" aria-label="توقف نمایش خودکار" data-slider-pause>
				<svg width="14" height="14" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path class="i-pause" d="M7 5h3v14H7zM14 5h3v14h-3z" fill="currentColor"/><path class="i-play" d="M8 5l11 7-11 7z" fill="currentColor"/></svg>
			</button>
		</div>
		<div class="hero__arrows">
			<button type="button" class="hero__arrow" aria-label="اسلاید قبلی" data-slider-prev>→</button>
			<button type="button" class="hero__arrow hero__arrow--next" aria-label="اسلاید بعدی" data-slider-next>←</button>
		</div>
	</div>

	<?php atiyeh_wave( 'M0 120V70C180 10 360 0 560 40S980 120 1180 80 1360 20 1440 40V120Z', 'var(--c-page)', 'hero__wave', 120 ); ?>
</section>
