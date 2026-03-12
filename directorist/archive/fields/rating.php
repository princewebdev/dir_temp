<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.3.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Return early when review is disabled.
if ( ! directorist_is_review_enabled() ) {
	return;
}

$avg_review_class = isset( $listings->loop['review']['average_reviews'] ) ? "theme-bg_" . $listings->loop['review']['average_reviews'] : 'no-review';
$reviews_count    = $listings->loop['review']['total_reviews'];
$review_text      = (  $reviews_count === 1 ) ? __( 'review', 'onelisting' ) : __( 'reviews', 'onelisting' );
?>
<span class="directorist-info-item directorist-rating-meta directorist-rating-transparent">

    <span class="directorist-rating-avg <?php echo esc_attr( $avg_review_class ); ?>">

        <?php directorist_icon( 'fas fa-star' ); ?>

        <span class="rating-count"><?php echo wp_kses_post( $listings->loop['review']['average_reviews'] ); ?></span>

    </span>

	<?php printf( '%s %s', wp_kses_post( $reviews_count ), wp_kses_post( $review_text ) );?>

</span>