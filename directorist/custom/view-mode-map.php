<?php
/**
 * @author  wpWax
 * @since   1.0
 * @version 1.0
 */

use wpWax\OneListing\Directorist_Support;

global $bdmv_listings;
?>
<div class="theme-view-mode view-mode-2 view-as directorist-viewas-dropdown">

	<a data-view="grid" class="directorist-dropdown__links--single action-btn-2 ab-grid map-view-grid <?php echo Directorist_Support::get_map_view_mode( $bdmv_listings, 'grid');?>">
		<?php directorist_icon( 'las la-grip-horizontal' ); ?>
	</a>

	<a data-view="list" class="directorist-dropdown__links--single action-btn-2 ab-list map-view-list <?php echo Directorist_Support::get_map_view_mode( $bdmv_listings, 'list');?>">
		<?php directorist_icon( 'las la-list' ); ?>
	</a>

</div>