<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Return early when review is disabled.
if ( ! directorist_is_review_enabled() ) {
	return;
}

$reviews_count = $listing->get_review_count();
$review_text = ( $reviews_count === 1 ) ? __('review', 'onelisting') : __('reviews', 'onelisting');
?>

<div class="directorist-info-item directorist-review-meta directorist-info-item-review">

    <span class="directorist-rating-avg">
		<?php directorist_icon( 'fas fa-star' ); ?>
		<span class="rating-count"><?php echo ATBDP()->review->get_average( $listing->id ) ?></span>

	</span>

	<?php printf( '%s %s', $reviews_count, $review_text ); ?>

</div>