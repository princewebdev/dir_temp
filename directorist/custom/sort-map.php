<?php
/**
 * @author  wpWax
 * @since   1.0
 * @version 1.0
 */

use wpWax\OneListing\Directorist_Support;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$listings = Directorist_Support::get_listings_options();
global $bdmv_listings;
?>
<div class="dropdown directorist-dropdown directorist-dropdown-js directorist-dropdown-right">

	<p><?php echo esc_html( isset( $bdmv_listings->options['sort_by_text'] ) ? $bdmv_listings->options['sort_by_text'] : __( 'Sort by', 'onelisting' ) ); ?> </p>

	<a class="directorist-dropdown__toggle directorist-dropdown__toggle-js directorist-btn directorist-btn-sm directorist-btn-px-15 directorist-btn-outline-primary directorist-toggle-has-icon" href="#" role="button" id="sortByDropdownMenuLink"> <?php echo Directorist_Support::get_map_sort_default_title( $_POST, $listings ); ?> <span class="caret"></a>

	<div class="directorist-dropdown__links directorist-dropdown__links-js sort-by" aria-labelledby="sortByDropdownMenuLink">

		<?php foreach ( $listings->get_sort_by_link_list() as $key => $value ): ?>

			<a class="directorist-dropdown__links--single dropdown-item sort-<?php echo $value['key']; ?>" data-sort="<?php echo $value['key'] ?>"><?php echo esc_html( $value['label'] ); ?></a>
		
		<?php endforeach;?>

	</div>

</div>