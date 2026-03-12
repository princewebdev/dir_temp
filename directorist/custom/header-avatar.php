<?php
/**
 * @author  wpWax
 * @since   1.0
 * @version 1.0
 */

use wpWax\OneListing\Directorist_Support;
?>

<div class="theme-header-action__author--info">

	<?php
	$user_id = get_current_user_id();
	
	if ( ! $user_id ) {
		return;
	}

	$custom_avatar_id      = get_user_meta( $user_id, 'pro_pic', true );
	$custom_profile_image  = $custom_avatar_id ? wp_get_attachment_image_src( $custom_avatar_id ) : false;
	$user_display_name     = get_the_author_meta( 'display_name', $user_id );
	
	if ( $custom_profile_image && ! empty( $custom_profile_image[0] ) ) {
		printf(
			'<img width="40" src="%s" class="avatar rounded-circle" alt="%s"/>',
			esc_url( $custom_profile_image[0] ),
			esc_attr( $user_display_name )
		);
	} else {
		$gravatar_html = get_avatar( $user_id, 40, null, null, array( 'class' => 'avatar rounded-circle' ) );
		if ( $gravatar_html ) {
			echo wp_kses_post( $gravatar_html );
		} else {
			$default_avatar_url = get_template_directory_uri() . '/assets/img/avatar.png';
			printf(
				'<img width="40" src="%s" class="avatar rounded-circle" alt="%s"/>',
				esc_url( $default_avatar_url ),
				esc_attr( $user_display_name )
			);
		}
	}

	if ( atbdp_is_page( 'dashboard' ) ) {
		printf(
			'<span>%s, </span>%s',
			esc_html__( 'Hi', 'onelisting' ),
			esc_html( $user_display_name )
		);
	}
	?>

	<?php echo Directorist_Support::get_dashboard_navigation(); ?>

</div>