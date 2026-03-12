<?php
/**
 * @author  wpWax
 * @since   1.0
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( atbdp_is_page( 'dashboard' ) ) {
	return;
}

$counter	   = 1;
$dashboard_url = get_permalink( get_directorist_option('user_dashboard')  )
?>

<div class="theme-header-author-navigation">
	<ul>

		<?php foreach ( $dashboard->dashboard_tabs() as $key => $value ): ?>

			<li>

				<a href="<?php echo esc_url( $dashboard_url ) . '#' . $key; ?>" class="directorist-booking-nav-link directorist-tab__nav__link">
					
					<span class="directorist_menuItem-text">

						<span class="directorist_menuItem-icon"> <?php directorist_icon( $value['icon'] );?> </span>
						
						<?php echo wp_kses_post( $value['title'] ); ?>

					</span>

				</a>
				
			</li>

			<?php $counter++; ?>

		<?php endforeach; ?>

		<?php if ( $dashboard->user_can_submit() ): ?>

			<li>

				<a href="<?php echo esc_url(ATBDP_Permalink::get_add_listing_page_link()); ?>" class="directorist-booking-nav-link directorist-tab__nav__link">
					
					<span class="directorist_menuItem-text">

						<span class="directorist_menuItem-icon"><?php directorist_icon( 'las la-plus' ); ?></span>

						<?php esc_html_e( 'Add Listing', 'onelisting' ); ?>

					</span>

				</a>

			</li>

		<?php endif; ?>

		<li>

			<a href="<?php echo esc_url( wp_logout_url(home_url() ) ); ?>" class="directorist-booking-nav-link directorist-tab__nav__link">
				
				<span class="directorist_menuItem-text">
					
					<span class="directorist_menuItem-icon"><?php directorist_icon( 'las la-sign-out-alt' ); ?>	</span>
					
					<?php esc_html_e( 'Log Out', 'onelisting' ); ?>

				</span>

			</a>

		</li>

	</ul>
</div>