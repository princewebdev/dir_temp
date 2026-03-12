<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.7.0
 */

use wpWax\OneListing\Directorist_Support;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


$current_page_id	= isset( $_REQUEST['current_page_id'] ) ? esc_attr( $_REQUEST['current_page_id'] ) : get_the_ID();
$is_elementor		= isset( $listings->atts['is_elementor'] ) ? true : false;
?>

<div <?php $listings->wrapper_class(); $listings->data_atts();?>>

	<?php if ( $is_elementor ) { ?>

		<div class="col-12"> <?php $listings->archive_view_template(); ?> </div>

	<?php } else { ?>

		<?php if ( Directorist_Support::show_title( $current_page_id ) ): ?>

			<div class="row">

				<div class="col-12">

					<h1 class="directorist-archive-title reza">
						<?php
						if ( isset( $listings->atts['category'] ) && ! empty( $listings->atts['category'] ) ) {
							echo esc_html( get_term_by( 'slug', $listings->atts['category'], ATBDP_CATEGORY )->name );
						} else {
							echo Directorist_Support::get_header_title( $current_page_id );
						}
						?>
					</h1>

				</div>

			</div>

		<?php endif;?>

		<div class="directorist-archive-contents__top">
			<?php
			$listings->mobile_view_filter_template();
			$listings->directory_type_nav_template();
			$listings->header_bar_template();
			$listings->full_search_form_template();
			?>
		</div>

		<div class="directorist-archive-contents__listings"> <?php $listings->archive_view_template(); ?> </div>

	<?php } ?>
</div>