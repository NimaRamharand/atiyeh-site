<?php
/**
 * «کارفرمایان»: every client (managed in the dashboard → کارفرمایان) with a filter by the service we provided.
 * Services come from the «نوع خدمت» checkboxes on each client.
 *
 * @package Atiyeh
 */

get_header();
while ( have_posts() ) :
	the_post();
	get_template_part( 'template-parts/page-hero', null, array( 'kicker' => 'مورد اعتماد کارفرمایان', 'desc' => 'شرکت ملی پست، مرکز آمار ایران، شهرداری‌ها و بیمارستان‌هایی که طرح‌های آماری، کدپستی، پلاک و آرشیو اسناد خود را به ما سپرده‌اند.', 'img' => 'plaque-site-inspection.webp' ) );
	$atiyeh_clients = post_type_exists( 'client' ) ? get_posts( array( 'post_type' => 'client', 'numberposts' => -1, 'orderby' => array( 'menu_order' => 'ASC', 'title' => 'ASC' ) ) ) : array();
	$atiyeh_filters = array( 'plaque' => 'پلاک و کدپستی', 'archive' => 'اسکن و آرشیو اسناد', 'stats' => 'آمار و داده' );
	$atiyeh_intro   = atiyeh_page_intro();
	?>
	<section class="container section-pad clients-page" data-filter>
		<?php if ( $atiyeh_intro ) : ?>
			<div class="team__intro prose rv"><?php echo $atiyeh_intro; // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
		<?php endif; ?>
		<div class="clients-page__bar rv">
			<div class="chips" role="group" aria-label="فیلتر بر اساس خدمت">
				<button type="button" class="chip chip--btn is-active" aria-pressed="true" data-filter-btn="all">همه</button>
				<?php foreach ( $atiyeh_filters as $atiyeh_slug => $atiyeh_label ) : ?>
					<button type="button" class="chip chip--btn" aria-pressed="false" data-filter-btn="<?php echo esc_attr( $atiyeh_slug ); ?>"><?php echo esc_html( $atiyeh_label ); ?></button>
				<?php endforeach; ?>
			</div>
			<span class="clients-page__count mut"><?php echo esc_html( atiyeh_fa_num( count( $atiyeh_clients ) ) ); ?> کارفرما</span>
		</div>
		<ul class="clients-grid" role="list">
			<?php foreach ( $atiyeh_clients as $atiyeh_c ) : ?>
				<?php
				$atiyeh_terms = get_the_terms( $atiyeh_c, 'service' );
				$atiyeh_slugs = is_array( $atiyeh_terms ) ? wp_list_pluck( $atiyeh_terms, 'slug' ) : array();
				// «بهنگام‌سازی» is part of the plaque service for this filter.
				if ( in_array( 'update', $atiyeh_slugs, true ) ) {
					$atiyeh_slugs[] = 'plaque';
				}
				$atiyeh_city = get_post_meta( $atiyeh_c->ID, '_atiyeh_city', true );
				$atiyeh_url  = get_post_meta( $atiyeh_c->ID, '_atiyeh_url', true );
				$atiyeh_tag  = $atiyeh_url ? 'a' : 'div';
				?>
				<li class="projects__cell" data-filter-item="<?php echo esc_attr( implode( ' ', array_unique( $atiyeh_slugs ) ) ); ?>">
					<<?php echo $atiyeh_tag; // phpcs:ignore WordPress.Security.EscapeOutput ?> class="client-card lift rvs"<?php echo $atiyeh_url ? ' href="' . esc_url( $atiyeh_url ) . '" target="_blank" rel="noopener"' : ''; ?>>
						<span class="client-card__logo">
							<?php
							if ( has_post_thumbnail( $atiyeh_c ) ) {
								echo get_the_post_thumbnail( $atiyeh_c, 'medium', array( 'alt' => 'لوگوی ' . get_the_title( $atiyeh_c ) ) );
							} else {
								// No logo yet: an icon for the kind of work we did for this client.
								$atiyeh_ic = in_array( 'archive', $atiyeh_slugs, true ) ? 'doc' : ( in_array( 'plaque', $atiyeh_slugs, true ) ? 'plaque' : ( in_array( 'stats', $atiyeh_slugs, true ) ? 'chart' : 'users' ) );
								echo '<span class="client-card__ph" aria-hidden="true">' . atiyeh_icon( $atiyeh_ic, 34 ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput
							}
							?>
						</span>
						<b class="client-card__name"><?php echo esc_html( get_the_title( $atiyeh_c ) ); ?></b>
						<?php if ( $atiyeh_city ) : ?><span class="mut client-card__city"><?php echo esc_html( atiyeh_nb( $atiyeh_city ) ); ?></span><?php endif; ?>
						<span class="client-card__tags">
							<?php if ( is_array( $atiyeh_terms ) ) : ?>
								<?php foreach ( $atiyeh_terms as $atiyeh_t ) : ?><i><?php echo esc_html( $atiyeh_t->name ); ?></i><?php endforeach; ?>
							<?php else : ?>
								<i class="is-empty">[ نوع خدمت ]</i>
							<?php endif; ?>
						</span>
					</<?php echo $atiyeh_tag; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
				</li>
			<?php endforeach; ?>
		</ul>
		<p class="projects__empty"<?php echo $atiyeh_clients ? ' hidden' : ''; ?> data-filter-empty>کارفرمایی در این دسته ثبت نشده است.</p>
	</section>
	<?php
	get_template_part( 'template-parts/blocks/cta' );
endwhile;
get_footer();
