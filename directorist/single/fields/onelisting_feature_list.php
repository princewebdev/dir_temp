<?php
/**
 * @author  wpWax
 * @since   1.0
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( ! $value ) {
	return;
}

$lis = explode( "\n", $value );
$ul_html = "<ul>";
foreach ( $lis as $li ) {
	$ul_html .= "<li>" . directorist_icon( 'las la-check-circle', false ) . $li . "</li>";
}
$ul_html .= "</ul>";

$widget_label = isset( $data['label'] ) ? $data['label'] : '';
?>

<div class="directorist-single-info directorist-single-info__feature__list directorist-single-info__list">
	
	<?php if( $widget_label ) : ?>

		<div class="directorist-single-info__label">
			<span class="directorist-single-info__label--text"><?php echo esc_html( $widget_label ); ?></span>
		</div>

	<?php endif; ?>

	<div class="directorist-single-info__value">
	
		<?php echo $ul_html; ?>
	
	</div>

</div>