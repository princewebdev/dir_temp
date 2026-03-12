<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.3.1
 */

use \Directorist\Helper;
use wpWax\OneListing\Directorist_Support;

$columns    = floor( 12 / $taxonomy->columns );
if ( '5' == $taxonomy->columns ) {
	$columns = $columns . '-5';
}
?>
<div id="directorist" class="atbd_wrapper directorist-w-100">
	
	<?php
	/**
	 * @since 5.6.6
	 */
	do_action( 'atbdp_before_all_locations_loop', $taxonomy );
	?>

	<div class="<?php Helper::directorist_container_fluid();?>">

		<div class="directorist-location  directorist-location--grid directorist-location--grid-one">

			<div class="<?php Helper::directorist_row();?>">

				<?php 
				if ( $locations ) : 

					foreach ( $locations as $key => $location ) :
						$loc_class = $location['img'] ? '' : ' directorist_location_grid-default';
						$col_key = apply_filters( 'onelisting_directorist_column_key', array(
							'key'     => $key,
							'columns' => $columns,
						) );
						?>

						<div class="<?php Helper::directorist_column( $col_key['columns'] );?>">

							<a class="directorist-location__single<?php echo esc_attr( $loc_class ); ?>" href="<?php echo esc_url( $location['permalink'] ); ?>">
								
								<figure>

									<?php if ( $location['img'] ) : ?>

										<img src="<?php echo esc_url( $location['img'] ); ?>" title="<?php echo esc_attr( $location['name'] ); ?>" alt="<?php echo esc_attr( $location['name'] ); ?>">
									
									<?php endif; ?>

									<figcaption>
										
										<div class="directorist-location-content">

											<?php if( Directorist_Support::has_parent_name( $location ) ) :?>

												<p class="directorist-location-parent"><?php echo Directorist_Support::has_parent_name( $location ); ?></p>

											<?php endif; ?>

											<h3 class="directorist-location"><?php echo esc_html( $location['name'] ); ?><?php directorist_icon( 'las la-arrow-right' ); ?></h3>

											<p class="directorist-listing-count">

												<?php $num_items = Directorist_Support::remove_bracket( $location['grid_count_html'], true );?>

												<?php printf( _nx( '%s listing', '%s listings', intval( $num_items ), '', 'onelisting' ), wp_kses_post( $num_items ) );?>

											</p>

										</div>
									
									</figcaption>

								</figure>

							</a>

						</div>

					<?php 
					endforeach;
						
				else:?>
					<p><?php esc_html_e( 'No Results found!', 'onelisting' ); ?></p>
				<?php endif; 
				?>

			</div>

		</div>

	</div>

</div>