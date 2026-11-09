<?php
/**
 * «درگاه پرداخت»: page + button only. No real bank gateway is connected (by design, for the demo).
 *
 * @package Atiyeh
 */

get_header();
while ( have_posts() ) :
	the_post();
	get_template_part( 'template-parts/page-hero', null, array( 'kicker' => 'پرداخت آنلاین', 'desc' => 'پرداخت هزینه خدمات به‌صورت آنلاین.' ) );
	?>
	<section class="container section-pad">
		<div class="content-card prose pay-card">
			<?php the_content(); ?>
			<button type="button" class="btn btn--primary pay-card__btn" data-pay-btn aria-describedby="pay-note">پرداخت آنلاین <span class="ar" aria-hidden="true">←</span></button>
			<p class="pay-card__note mut" id="pay-note" role="status" data-pay-note hidden>درگاه پرداخت بانکی هنوز متصل نشده است.</p>
		</div>
	</section>
	<?php
endwhile;
get_footer();
