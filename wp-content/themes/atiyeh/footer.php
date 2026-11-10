<?php
/**
 * Site footer: wave, intro, quick links, contact, newsletter.
 *
 * @package Atiyeh
 */
?>
</main>

<footer class="site-footer" id="contact">
	<?php atiyeh_wave( 'M0 80V40C240 0 520 70 800 30S1240 10 1440 40V80Z', 'var(--c-footer)', 'wave--footer', 80 ); ?>
	<div class="site-footer__inner">
		<div class="site-footer__about rv">
			<b class="site-footer__name"><span class="site-footer__mark" aria-hidden="true"><?php echo atiyeh_icon( 'logo', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span><?php bloginfo( 'name' ); ?></b>
			<p>پلاک کدپستی · آرشیو دیجیتال اسناد</p>
			<p class="site-footer__blurb">تولید و نصب پلاک کدپستی، بهنگام‌سازی نشانی‌ها و تبدیل اسناد کاغذی به آرشیو دیجیتال قابل جستجو؛ با دقت میدانی و امنیت اطلاعات.</p>
		</div>

		<nav class="site-footer__col rv" aria-label="دسترسی سریع">
			<b class="site-footer__title">دسترسی سریع</b>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'footer',
					'container'      => false,
					'menu_class'     => 'site-footer__links',
					'fallback_cb'    => false,
					'depth'          => 1,
				)
			);
			?>
		</nav>

		<div class="site-footer__col rv">
			<b class="site-footer__title">تماس</b>
			<address class="site-footer__contact">
				<span><?php echo esc_html( atiyeh_nb( atiyeh_contact( 'address' ) ) ); ?></span>
				<span dir="auto"><?php echo esc_html( atiyeh_nb( atiyeh_contact( 'phone' ) ) ); ?></span>
				<span dir="auto"><?php echo esc_html( atiyeh_nb( atiyeh_contact( 'email' ) ) ); ?></span>
			</address>
		</div>

		<div class="site-footer__newsletter rv" id="newsletter">
			<b class="site-footer__title">دریافت خبرنامه</b>
			<?php
			$atiyeh_nl  = isset( $_GET['newsletter'] ) ? sanitize_key( $_GET['newsletter'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
			$atiyeh_nlm = array(
				'ok'      => array( 'ok', 'عضویت شما در خبرنامه ثبت شد.' ),
				'invalid' => array( 'err', 'لطفاً یک ایمیل معتبر وارد کنید.' ),
				'expired' => array( 'err', 'زمان فرم به پایان رسیده است؛ دوباره تلاش کنید.' ),
			);
			if ( isset( $atiyeh_nlm[ $atiyeh_nl ] ) ) {
				printf( '<p class="form-msg form-msg--%1$s form-msg--dark" role="%2$s" tabindex="-1" data-focus>%3$s</p>', esc_attr( $atiyeh_nlm[ $atiyeh_nl ][0] ), 'ok' === $atiyeh_nlm[ $atiyeh_nl ][0] ? 'status' : 'alert', esc_html( $atiyeh_nlm[ $atiyeh_nl ][1] ) );
			}
			?>
			<form class="newsletter" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
				<input type="hidden" name="action" value="atiyeh_newsletter">
				<?php wp_nonce_field( 'atiyeh_newsletter', '_atiyeh_nonce' ); ?>
				<label class="screen-reader-text" for="newsletter-email">ایمیل</label>
				<input id="newsletter-email" type="email" name="email" placeholder="ایمیل شما" required autocomplete="email">
				<button class="btn btn--primary" type="submit">عضویت</button>
			</form>
		</div>
	</div>
	<div class="site-footer__copy">© <?php echo esc_html( atiyeh_jyear() ); ?> کلیه حقوق برای <?php bloginfo( 'name' ); ?> محفوظ است.</div>
</footer>

<button class="to-top" type="button" aria-label="بازگشت به بالای صفحه" data-to-top>
	<svg class="to-top__ring" viewBox="0 0 48 48" aria-hidden="true" focusable="false"><circle cx="24" cy="24" r="21"/><circle cx="24" cy="24" r="21" data-to-top-ring/></svg>
	<?php echo atiyeh_icon( 'up', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
</button>

<?php wp_footer(); ?>
</body>
</html>
