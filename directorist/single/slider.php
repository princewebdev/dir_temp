<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 7.7.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$total_photos = isset( $data['images'] ) ? count( $data['images'] ) : '';
?>

<?php if ( $total_photos ): ?>

	<div class="theme-single-listing-slider-wrap<?php echo esc_attr(  ( $total_photos > 2 ) ? '' : ' has-background-color' ); ?>">

		<div id="theme-single-listing-slider" class="theme-single-listing-slider theme-swiper theme-carousel background-<?php echo esc_attr( $data['background-size'] ); ?>"
			data-width="<?php echo esc_attr( $data['width'] ); ?>"
			data-height="<?php echo esc_attr( $data['height'] ); ?>"
			data-rtl="<?php echo esc_attr( $data['rtl'] ); ?>"
			data-show-thumbnails="<?php echo esc_attr( $data['show-thumbnails'] ); ?>"
			data-background-size="<?php echo esc_attr( $data['background-size'] ); ?>"
			data-blur-background="<?php echo esc_attr( $data['blur-background'] ); ?>"
			data-background-color="<?php echo esc_attr( $data['background-color'] ); ?>"
			data-thumbnail-background-color="<?php echo esc_attr( $data['thumbnail-bg-color'] );?>"
			data-sw-items="4" 
			data-sw-margin="6" 
			data-sw-loop="true" 
			data-sw-perslide="1" 
			data-sw-speed="1000" 
			data-sw-autoplay="{}" 
			data-sw-responsive='{
			"0": {"slidesPerView": "1"},
			"768": {"slidesPerView": "2"},
			"992": {"slidesPerView": "3"},
			"1200": {"slidesPerView": "4"}
		}'>
			<div class="swiper-wrapper">

					<?php foreach ( $data['images'] as $image ): ?>

						<div class="swiper-slide theme-single-listing-slider__item" style="--blur-image: url('<?php echo esc_attr( isset( $image['id'] ) ? atbdp_get_image_source( $image['id'], 'full' ) : $image['src'] ) ?>'); --bg-color: <?php echo esc_attr( $data['background-color'] ); ?>;">

							<a href="<?php echo esc_url( $image['src'] ); ?>">
								<img src="<?php echo esc_url( $image['src'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ) ?>"/>
							</a>

						</div>

					<?php endforeach;?>

			</div>

			<div class="theme-swiper-pagination"></div>

			<div class="theme-swiper-navigation">

				<div class="theme-swiper-button-nav theme-swiper-button-prev"><?php directorist_icon( 'fas fa-angle-left', true );?></div>

				<div class="theme-swiper-button-nav theme-swiper-button-next"><?php directorist_icon( 'fas fa-angle-right', true );?></div>

			</div>
		</div>
		<?php if ( $total_photos >= 4 ): ?>

			<div class="theme-single-listing-see-all">

				<?php printf( '<a href="" class="btn theme-btn btn-listing-see-all">%s %s %s</a>', directorist_icon( 'las la-image', false ), __( 'See photos', 'onelisting' ), $total_photos )?>

			</div>

		<?php endif;?>

	</div>

<?php endif;?>