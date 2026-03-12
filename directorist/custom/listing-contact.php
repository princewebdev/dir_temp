<?php
/**
 * @author  wpWax
 * @since   1.0
 * @version 1.0
 */

use Directorist\Helper;
?>

<div class="modal theme-modal theme-contact-modal fade" id="theme-author-contact-modal" tabindex="-1" role="dialog" aria-labelledby="contact_modal_title" aria-hidden="true">

	<div class="modal-dialog modal-dialog-centered" role="document">

		<div class="modal-content">

			<div class="modal-header">

				<h5 class="modal-title" id="contact_modal_title"><?php esc_html_e( 'Request Info', 'onelisting' ); ?></h5>

				<button type="button" class="theme-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>

			</div>

			<div class="modal-body">

				<?php Helper::get_template( 'widgets/contact-form', [ 'email' => get_post_meta( get_the_ID(), '_email', true ) ] ); ?>

			</div>

		</div>

	</div>

</div>