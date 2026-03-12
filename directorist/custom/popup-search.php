<?php
/**
 * @author  wpWax
 * @since   1.0
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="dspb-search">
	<div class="dspb-search__popup">
		<div class="dspb-search__form-close">
			<?php directorist_icon( 'la times' );?>
		</div>
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="dspb-search__form">
						<?php echo do_shortcode( '[directorist_search_listing more_filters_button="no" show_title_subtitle="no" show_popular_category="no"]' ); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>