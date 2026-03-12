<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 7.3.1
 */

use \Directorist\Directorist_Single_Listing;
use \Directorist\Helper;
use wpWax\OneListing\Helper as OneListingHelper;

if ( ! defined( 'ABSPATH' ) ) exit;

$listing         = Directorist_Single_Listing::instance();
$related_listing = OneListingHelper::single_content_fields( $listing, 'related_listings' );
?>

<?php if ( $listing->single_page_enabled() ) : ?>

	<div class="directorist-single-contents-area directorist-w-100" data-id="<?php echo esc_attr( $listing->id ?? ''); ?>">

		<div class="<?php Helper::directorist_container(); ?>">

			<?php $listing->notice_template(); ?>

			<div class="<?php Helper::directorist_row(); ?>">

				<?php Helper::get_template( 'single/top-actions' ); ?>

				<div class="directorist-single-wrapper custom-listing-wrapper">

					<?php echo $listing->single_page_content(); ?>

				</div>

			</div>

		</div>
		
	</div>

<?php else: ?>

	<div class="directorist-single-contents-area directorist-w-100" data-id="<?php echo esc_attr( $listing->id ?? ''); ?>">

		<?php $listing->header_template(); ?>

		<div class="directorist-single-listing-content">

			<div class="theme-container">

				<?php $listing->notice_template(); ?>

				<div class="directorist-row">

					<div class="<?php Helper::directorist_single_column(); ?>">
						
						<?php Helper::get_template( 'single/top-actions' ); ?>

						<div class="directorist-single-wrapper">

							<?php
							foreach ( $listing->content_data as $section ) {
								
								if ( isset( $section['widget_name'] ) && 'related_listings' === $section['widget_name'] ) {
									continue;
								}

								$listing->section_template( $section );
							}
							?>

						</div>

					</div>

					<?php Helper::get_template( 'single-sidebar' ); ?>

				</div>

			</div>
			
		</div>

		<?php if ( $related_listing ):  ?>
			
			<div class="directorist-similar-properties">

				<div class="theme-container">

					<div class="row">

						<div class="col-12">

							<?php
							foreach ( $listing->content_data as $section ) {
								if ( isset( $section['widget_name'] ) && 'related_listings' === $section['widget_name'] ) {
									$listing->section_template( $section );
									break;
								}
							}
							?>

						</div>

					</div>
					
				</div>
				
			</div>
			
		<?php endif; ?>
		
	</div>

<?php endif; ?>
