<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use wpWax\OneListing\Directorist_Support;
?>

<div class="directorist-author-profile-area theme-profile-area">

	<?php do_action( 'directorist_before_author_profile_section' );?>

	<div class="directorist-card directorist-author-profile-wrap directorist-mb-40">
		<div class="directorist-card__body directorist-flex directorist-justify-content-between directorist-align-center">

			<div class="directorist-author-avatar">

				<?php echo wp_kses_post( $author->avatar_html() ); ?>

				<div class="directorist-author-avatar__info">
					<h1 class="directorist-author-name"><?php echo esc_html( $author->display_name() ); ?></h1>
					<p class="directorist-joined-date"><?php echo Directorist_Support::get_member_joined_text( $author->id ); ?></p>

					<ul class="directorist-author-meta-list">

						<li class="directorist-author-meta-list__item directorist-info-meta directorist-auhtor-meta-review">
							<span class="directorist-listing-count"><?php echo wp_kses_post( $author->listing_count_html() ); ?></span>
						</li>

						<?php if ( $author->review_enabled() ): ?>

							<li class="directorist-author-meta-list__item directorist-auhtor-meta-rating">
								<span class="directorist-listing-rating-meta"><?php echo Directorist_Support::get_rating_stars_html( $author->rating ); ?></span>
							</li>

							<li class="directorist-author-meta-list__item directorist-info-meta directorist-auhtor-meta-review-count">
								<span class="directorist-review-count"><?php echo wp_kses_post( $author->review_count_html() ); ?></span>
							</li>

						<?php endif;?>

					</ul>

				</div>

			</div>

		</div>
	</div>

</div>