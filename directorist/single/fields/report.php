<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 8.0
 */

 if ( ! defined( 'ABSPATH' ) ) exit;
 ?>
 
 <?php if ( is_user_logged_in() ) : ?>
	 <button class="directorist-single-listing-action directorist-btn directorist-btn-sm directorist-btn-light directorist-action-report directorist-action-report-loggedin directorist-btn-modal directorist-btn-modal-js" href="#" data-directorist_target="directorist-report-abuse-modal" aria-label="Report Open Modal">
		 <?php directorist_icon( $icon );?><span class="directorist-single-listing-action__text"><?php esc_html_e( $data['label'] ?? 'Report', 'onelisting' ); ?></span> 
	 </button>
 <?php else : ?>
	 <button class="directorist-single-listing-action directorist-btn directorist-btn-sm directorist-btn-light directorist-action-report directorist-action-report-not-loggedin directorist-btn-modal directorist-btn-modal-js"><?php directorist_icon( $icon );?> <span class="directorist-single-listing-action__text" aria-label="Report Modal"> <?php esc_html_e( 'Report', 'onelisting' ); ?></span></button>
 <?php endif; ?>