<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$count         = count( $actions );
$more_than_two = array();

if ( $count > 2 ) {
	$more_than_two = array_splice( $actions, 2 );
}
?>

<div class="directorist-single-listing-action directorist-single-listing-action-quick directorist-flex directorist-align-center">

	<?php
	foreach ( $actions as $action ) {
		$listing->field_template( $action );
	}
	?>

	<?php if ( $more_than_two ): ?>

		<div class="directorist-single-listing-action__extra">

			<div class="dropdown theme-dropdown">

				<a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><?php directorist_icon( 'las la-ellipsis-h' ); ?></a>

				<div class="dropdown-menu">

					<?php foreach ( $more_than_two as $action ): ?>

						<?php $listing->field_template( $action );?>

					<?php endforeach;?>

				</div>

			</div>

		</div>

	<?php endif;?>

</div>

<?php foreach ( $args['actions'] as $key => $action ): ?>

	<?php if ( isset( $action['widget_key'] ) && 'report' == $action['widget_key'] ): ?>

		<section class="directorist-modal directorist-modal-js directorist-fade directorist-report-abuse-modal">

			<div class="directorist-modal__dialog">

				<div class="directorist-modal__content">

					<form id="directorist-report-abuse-form">

						<header class="directorist-modal__header">

							<div class="directorist-modal-title" id="directorist-report-abuse-modal__label"><?php esc_html_e('Report Abuse', 'onelisting'); ?></div>

							<button class="directorist-modal-close directorist-modal-close-js" aria-label="Report Modal Close">
								<span aria-hidden="true">&times;</span>
							</button>

						</header>

						<div class="directorist-modal__body">

							<div class="directorist-form-group">

								<label for="directorist-report-message"><?php esc_html_e( 'Your Complaint', 'onelisting' ); ?><span class="directorist-report-star">*</span></label>

								<textarea class="directorist-form-element" id="directorist-report-message" rows="3" placeholder="<?php esc_attr_e( 'Message...', 'onelisting' ); ?>" required></textarea>
								<input type="hidden" name="atbdp-post-id" id="atbdp-post-id" value="<?php echo esc_attr( get_the_id() ); ?>" />
							</div>

							<div id="directorist-report-abuse-g-recaptcha"></div>

							<div id="directorist-report-abuse-message-display"></div>

						</div>

						<div class="directorist-modal__footer">

							<button type="submit" class="directorist-btn directorist-btn-sm"><?php esc_html_e( 'Submit', 'onelisting' ); ?></button>

						</div>

					</form>

				</div>

			</div>

		</section>

	<?php endif;?>

<?php endforeach;?>