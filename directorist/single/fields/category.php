<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( empty( $listing->get_cat_list() ) ) {
	return;
}

$cats = get_the_terms( get_the_ID(), ATBDP_CATEGORY );
?>

<div class="directorist-info-item directorist-listing-category">

	<?php 
	if ( ! empty( $cats ) ):

		$term_icon = get_term_meta( $cats[0]->term_id, 'category_icon', true );
		$term_link = esc_url( get_term_link( $cats[0]->term_id, ATBDP_CATEGORY ) );
		$term_name = $cats[0]->name;
		?>

		<a href="<?php echo esc_url( $term_link ); ?>"><?php directorist_icon( $term_icon ) ?> <?php echo esc_html( $term_name ); ?></a>

		<?php 
		$totalTerm = count( $cats );

		if ( $totalTerm > 1 ):

			$totalTerm = $totalTerm - 1;
			?>

			<div class="directorist-listing-category__popup">

				<span class="directorist-listing-category__extran-count">+<?php echo esc_html( $totalTerm ); ?></span>

				<div class="directorist-listing-category__popup__content">

				<?php foreach ( array_slice( $cats, 1 ) as $cat ):

					$term_icon  = get_term_meta( $cat->term_id, 'category_icon', true );
					$term_icon  = directorist_icon( $term_icon, false );
					$term_label = trim( "{$term_icon} {$cat->name}" );
					$term_link  = esc_url( ATBDP_Permalink::atbdp_get_category_page( $cat ) );
					$term_link  = esc_url( get_term_link( $cat->term_id, ATBDP_CATEGORY ) );

					printf( "<a href='%s'>%s</a>", esc_url( $term_link ), wp_kses_post( $term_label ) );

					endforeach; ?>

				</div>

			</div>

			<?php
			endif;

		else: ?>

		<a href="#"><?php echo atbdp_get_term_icon(); ?><?php esc_html_e( 'Uncategorized', 'onelisting' );?></a>

	<?php endif;?>

</div>