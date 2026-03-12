<?php

use Directorist\Helper;

if( ! Helper::multi_directory_enabled()  ) {
	return;
}

global $bdmv_listings;
?>
<div class="directorist-type-nav directorist-type-nav--listings-map">

	<ul class="directorist-type-nav__list">

		<?php if ( $bdmv_listings->data['listings']->listing_types ) :?>
			
			<?php foreach ( $bdmv_listings->data['listings']->listing_types as $id => $value ): ?>

				<li class="<?php echo ( $bdmv_listings->data['listings']->current_listing_type == $value['term']->term_id ) ? 'current' : ''; ?> bdmv-directorist-type">
					<a class="directorist-type-nav__link" data-id="<?php echo $value['term']->term_id; ?>">
					<?php directorist_icon( $value['data']['icon'] ); ?>
					<?php echo esc_html( $value['name'] ); ?></a>
				</li>

			<?php endforeach;?>

		<?php endif; ?>

	</ul>

</div>