<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-user-dashboard-access-notice">
	<div class="directorist-alert directorist-alert-warning">
		<?php directorist_icon( 'fas fa-info-circle' ); ?>
		<?php echo wp_kses_post( sprintf(__( 'You need to be logged in to view the content of this page. You can login <a href="#" data-bs-toggle="modal" data-bs-target="#theme-login-modal">Here.</a> Don\'t have an account? <a href="#" data-bs-toggle="modal" data-bs-target="#theme-register-modal">Sign Up</a>', 'onelisting') ) ); ?>
	</div>
</div>