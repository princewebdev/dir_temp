<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.4.0
 */

 use \Directorist\Helper;
use wpWax\OneListing\Directorist_Support;

$columns = floor( 12 / $taxonomy->columns );
if ( '5' == $taxonomy->columns ) {
	$columns = $columns . '-5';
}
?>
<div id="directorist" class="atbd_wrapper directorist-w-100">
	<?php
	/**
	 * @since 5.6.6
	 */
	do_action( 'atbdp_before_all_categories_loop', $taxonomy );
	?>
	<div class="<?php Helper::directorist_container_fluid(); ?>">

		<div class="directorist-categories">
			<div class="<?php Helper::directorist_row(); ?>">
				<?php
				if( $categories ) {
					foreach ($categories as $category) {
						$cat_class = !$category['img'] ? ' atbd_category_no_image' : '';
						?>
						<div class="<?php Helper::directorist_column( $columns ); ?>">
							<a class="directorist-categories__single<?php echo esc_attr( $cat_class ); ?>" href="<?php echo esc_url($category['permalink']); ?>">

							<figure>

								<?php if ( $category['img'] ) { ?>
									<img src="<?php echo esc_url( $category['img'] ); ?>" title="<?php echo esc_attr($category['name']); ?>" alt="<?php echo esc_attr($category['name']); ?>">
									<?php
								}
								?>
							</figure>

									
								<div class="cat-box">

									<div class="cat-box__top">

										<?php
											if ($category['has_icon']) { ?>
												<div class="icon"><?php directorist_icon( $category['icon_class'] );?></div>
												<?php
											}
										?>

										<div class="cat-info">

											<h4 class="cat-name"><?php echo esc_html($category['name']); ?></h4>

										</div>

									</div>

									<div class="cat-box__bottom">

										<?php if( $taxonomy->show_count ){ ?>

											<span class="cat-count">

												<?php echo wp_kses_post( Directorist_Support::remove_bracket( $category['grid_count_html'] ) ); ?> <span><?php echo ( ( $category['term']->count > 1 ) || ( $category['term']->count == 0 ) ) ? __( 'listings', 'onelisting' ) : __( 'listing', 'onelisting' ); ?></span>

											</span>

										<?php } ?>

									</div>

								</div>

							</a>

						</div>

						<?php
					}
				} else {
					_e('<p>No Results found!</p>', 'onelisting');
				}
				?>
			</div>
		</div>
	</div>

	<?php
    /**
     * @since 5.6.6
     */
    do_action( 'atbdp_after_all_categories_loop' );
    ?>
</div>