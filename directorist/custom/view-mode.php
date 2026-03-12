<?php
/**
 * @author  wpWax
 * @since   1.0
 * @version 1.0
 */
?>

<div class="theme-view-mode directorist-viewas-dropdown">

	<?php foreach ( $listings->get_view_as_link_list() as $key => $value ): ?>

		<?php if ( ! strpos( $value['link'], 'map' ) ):?>
			
			<a class="directorist-dropdown__links--single <?php echo esc_attr( $value['active_class'] ); ?>" href="<?php echo esc_attr( $value['link'] ); ?>">
				<?php if ( strpos( $value['link'], 'grid' ) ): ?>
					<?php directorist_icon( 'las la-grip-horizontal' ); ?>
				<?php elseif ( strpos( $value['link'], 'list' ) ): ?>
					<?php directorist_icon( 'las la-list' ); ?>
				<?php endif;?>
			</a>
			
		<?php endif;?>

	<?php endforeach;?>

</div>