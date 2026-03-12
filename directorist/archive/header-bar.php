<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.3.1
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="directorist-header-bar">

	<div class="<?php Helper::directorist_container_fluid(); ?>">

		<?php if ( $listings->has_listings_header() ) : ?>

			<div class="directorist-listings-header">

				
				<?php if ( $listings->has_listings_header() ): ?>

					<div class="directorist-listings-header__left">

						<?php if ( $listings->has_filters_button && 'no_sidebar' === $listings->sidebar ): ?>
							<button class="directorist-btn directorist-btn-sm directorist-filter-btn directorist-modal-btn directorist-modal-btn--full" aria-label="Modal Button"><?php echo wp_kses_post( $listings->filter_btn_html() ); ?></button>
						<?php endif; ?>

						<?php 
							if ( $listings->header_title && ! empty( $listings->options['display_listings_count'] ) ) {
								echo $listings->listings_header_title();
							} 
						?>
					</div>

				<?php endif; ?>

				<?php do_action( 'onelisting_archive_header_center', $listings ); ?>

				<?php if ( $listings->has_header_toolbar() ): ?>

					<div class="directorist-listings-header__right">

						<div class="directorist-listings-header__actions">

							<?php
							if ( $listings->display_sortby_dropdown ) {
								$listings->sortby_dropdown_template();
							}

							if ( $listings->display_viewas_dropdown ) {
								$listings->viewas_dropdown_template();
							}
							?>
							
						</div>
						
					</div>

				<?php endif; ?>

			</div>

		<?php endif; ?>

	</div>

</div>