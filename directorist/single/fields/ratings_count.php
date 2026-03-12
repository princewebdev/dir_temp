<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.4.0
 */

use Directorist\Review\Comment;

if ( ! defined( 'ABSPATH' ) ) exit;

// Return early when review is disabled.
if ( ! directorist_is_review_enabled() ) {
	return;
}
?>

<div class="directorist-info-item directorist-review-meta directorist-info-item-rating">
	
    <span class="directorist-rating-avg">
		<?php directorist_icon( 'fas fa-star' ); ?>
		<span class="rating-count"><?php echo wp_kses_post( Comment::get_average_rating_for_listing( $listing->id ) ) ?></span>

	</span>

</div>