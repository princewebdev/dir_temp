<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */
use wpWax\OneListing\Directorist_Support;

if ( ! defined( 'ABSPATH' ) ) exit;

$is_elementor		= isset( $listings->atts['is_elementor'] )  ? true : false;
$current_page_id	= isset( $_REQUEST['current_page_id'] ) ? esc_attr( $_REQUEST['current_page_id'] ) : get_the_ID();
?>

<div <?php $listings->wrapper_class(); $listings->data_atts(); ?>>

	<?php if ( $is_elementor ) { ?>

	<div class="col-12"> <?php $listings->archive_view_template(); ?> </div>

	<?php } else { ?>

		<?php if ( Directorist_Support::show_title( $current_page_id ) ): ?>

			<div class="row">

				<div class="col-12">

					<h1 class="directorist-archive-title">
						<?php
						if ( ! empty( $listings->atts['category'] ) ) {
							echo esc_html( get_term_by( 'slug', $listings->atts['category'], ATBDP_CATEGORY )->name );
						} else {
							echo Directorist_Support::get_header_title( $current_page_id );
						}
						?>
					</h1>

				</div>

			</div>

		<?php endif;?>

		<div class="listing-with-sidebar">
			<div class="directorist-container">
				<div class="listing-with-sidebar__wrapper">
					<div class="listing-with-sidebar__type-nav">
						<?php
							$listings->directory_type_nav_template();
						?>
					</div>

					<?php if( ! $listings->hide_top_search_bar_on_sidebar_layout() ) : ?>

						<div class="listing-with-sidebar__searchform">
							<?php
								$listings->basic_search_form_template();
							?>
						</div>

					<?php endif; ?>

					<div class="listing-with-sidebar__header">
						<?php
							$listings->header_bar_template();
						?>
					</div>
					<div class="listing-with-sidebar__contents">
						<aside class="listing-with-sidebar__sidebar <?php echo esc_attr( $listings->sidebar_class() ); ?>">
							<?php
								$listings->advance_search_form_template();
							?>
						</aside>
						<section class="listing-with-sidebar__listing">
							<?php
								$listings->archive_view_template();
							?>
						</section>
					</div>
				</div>
			</div>
		</div>

	<?php } ?>

</div>