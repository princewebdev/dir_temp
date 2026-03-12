<?php
/**
 * @author  wpWax
 * @since   1.0
 * @version 1.0
 */

use wpWax\OneListing\Helper;
use wpWax\OneListing\Theme;

if ( is_user_logged_in() || atbdp_is_page( 'login' ) || atbdp_is_page( 'registration' ) || atbdp_is_page( 'dashboard' ) ) {
	return;
}

if ( empty( Theme::$options['header_account'] ) ) {
	return;
}

if ( atbdp_is_page( 'add_listing' ) && get_directorist_option( 'guest_listings' ) ) {
	return;
}
?>

<div class="theme-authentication-modal">

	<div class="modal fade" id="theme-login-modal" role="dialog" aria-hidden="true">

		<div class="modal-dialog modal-dialog-centered" role="document">

			<div class="modal-content">
				
				<div class="modal-body">
					<button type="button" class="theme-close" data-bs-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>

					<?php echo do_shortcode( '[directorist_user_login]' ); ?>

				</div>

			</div>

		</div>

	</div>

</div>


