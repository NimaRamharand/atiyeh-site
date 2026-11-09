<?php
/**
 * Site footer: wave, intro, quick links, contact, newsletter.
 *
 * @package Atiyeh
 */
?>
</main>

<footer class="site-footer" id="contact">
	<?php atiyeh_wave( 'M0 80V40C240 0 520 70 800 30S1240 10 1440 40V80Z', '#1f262b', 'wave--footer', 80 ); ?>
	<div class="site-footer__inner">
		<div class="site-footer__about">
			<b class="site-footer__name"><?php bloginfo( 'name' ); ?></b>
			<p>پلاک کدپستی · آرشیو دیجیتال اسناد</p>
		</div>

		<nav class="site-footer__col" aria-label="دسترسی سریع">
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

		<div class="site-footer__col">
			<b class="site-footer__title">تماس</b>
			<address class="site-footer__contact">
				<span><?php echo esc_html( atiyeh_contact( 'address' ) ); ?></span>
				<span dir="auto"><?php echo esc_html( atiyeh_contact( 'phone' ) ); ?></span>
				<span dir="auto"><?php echo esc_html( atiyeh_contact( 'email' ) ); ?></span>
			</address>
		</div>

		<div class="site-footer__newsletter">
			<b class="site-footer__title">دریافت خبرنامه</b>
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

<?php wp_footer(); ?>
</body>
</html>
