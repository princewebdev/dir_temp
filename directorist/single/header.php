<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 7.5
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<?php if ( empty( $listing->single_page_enabled() ) ) : ?>

	<?php Helper::get_template( 'single/header-parts/slider', ['listing' => $listing] ); ?>
	
	<div class="directorist-single-listing-header-wrap directorist-single-wrapper">

		<div class="theme-container">

			<div class="row">

				<div class="col-12">
					
					<div class="directorist-single-listing-header">

						<div class="directorist-single-listing-header__left">

							<div class="directorist-single-listing-header__listing-title-wrapper d-flex align-items-start">

								<?php if ( $display_title ): ?>
									<h1 class="directorist-single-listing-header__listing-title">
										<?php echo esc_html( $listing->get_title() ); ?>
									</h1>
								<?php endif; ?>

								<?php do_action( 'directorist_single_listing_after_title', $listing->id ); ?>

							</div>

							<?php if ( $display_tagline && $listing->get_tagline() ): ?>
								<p class="directorist-listing-details-tagline">
									<?php echo esc_html( $listing->get_tagline() ); ?>
								</p>
							<?php endif; ?>
							
							<?php $listing->quick_info_template(); ?>
							
						</div>

						<div class="directorist-single-listing-header__right">

							<?php $listing->quick_actions_template(); ?>
							
						</div>
						
					</div>

				</div>

			</div>
			
		</div>
		
	</div>

<?php else : ?>

	<?php Helper::get_template( 'single/header-parts/slider', ['listing' => $listing] ); ?>
	<div class="directorist-single-listing-header-wrap directorist-single-wrapper">

		<div class="theme-container">

			<div class="row">

				<div class="col-12">
					
					<div class="directorist-single-listing-header">

						<div class="directorist-single-listing-header__left">

							<div class="directorist-single-listing-header__listing-title-wrapper d-flex align-items-start">

								<?php if ( $display_title ): ?>
									<h1 class="directorist-single-listing-header__listing-title">
										<?php echo esc_html( $listing->get_title() ); ?>
									</h1>
								<?php endif; ?>

								<?php do_action( 'directorist_single_listing_after_title', $listing->id ); ?>

								<?php if ( $display_tagline && $listing->get_tagline() ): ?>
									<p class="directorist-listing-details-tagline">
										<?php echo esc_html( $listing->get_tagline() ); ?>
									</p>
								<?php endif; ?>
								
							</div>
							
							<?php $listing->quick_info_template(); ?>
							
						</div>

						<div class="directorist-single-listing-header__right">

							<?php $listing->quick_actions_template(); ?>
							
						</div>
						
					</div>

				</div>

			</div>
			
		</div>
		
	</div>

<?php endif; ?>