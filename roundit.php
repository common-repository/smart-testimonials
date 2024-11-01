<?php

function quick_image_roundness_testi( $form_fields, $post ) {
	// existing value of roundness field
    $field_value = get_post_meta( $post->ID, 'location', true );
    $location = get_post_meta( $post->ID, 'location', true );
        $border = get_post_meta( $post->ID, 'border', true );
        $border_color = get_post_meta( $post->ID, 'border_color', true );
        $shadow_size = get_post_meta( $post->ID, 'shadow_size', true );
        $shadow_blur = get_post_meta( $post->ID, 'shadow_blur', true );
    $form_fields['location'] = array(
        'value' => $field_value ? $field_value : '',
        'label' => __( 'Roundness' ),
		'input' => 'html',
		'html' => '<input style="background-color:#BDFFBD;" type="text" id="attachments-'.$post->ID.'-location" name="attachments['.$post->ID.'][location]" data-setting="location" value="'.$location.'" placeholder="(e.g. 65px or 100%)"/>',
        'helps' => __( '' )
    );
    
    
    $form_fields['border'] = array(
        'value' => $field_value ? $field_value : '',
        'label' => __( 'Border' ),
		'input' => 'html',
		'html' => '<input style="background-color:#BDFFBD;" type="text" id="attachments-'.$post->ID.'-border" name="attachments['.$post->ID.'][border]" data-setting="border" value="'.$border.'" placeholder="(e.g. 2px or 5px)"/>',
        'helps' => __( '' )
    );
    
    $form_fields['border_color'] = array(
        'value' => $field_value ? $field_value : '',
        'label' => __( 'Border Color' ),
		'input' => 'html',
		'html' => '<input style="background-color:#BDFFBD;" type="text" id="attachments-'.$post->ID.'-border_color" name="attachments['.$post->ID.'][border_color]" data-setting="border" value="'.$border_color.'" placeholder="(e.g. white or #FFFFFF)"/>',
        'helps' => __( '' )
    );
    
    $form_fields['shadow_size'] = array(
        'value' => $field_value ? $field_value : '',
        'label' => __( 'Shadow Size' ),
		'input' => 'html',
		'html' => '<input style="background-color:#BDFFBD;" type="text" id="attachments-'.$post->ID.'-shadow_size" name="attachments['.$post->ID.'][shadow_size]" data-setting="border" value="'.$shadow_size.'" placeholder="(e.g. 5px)"/>',
        'helps' => __( '' )
    );
    $form_fields['shadow_blur'] = array(
        'value' => $field_value ? $field_value : '',
        'label' => __( 'Shadow Blur' ),
		'input' => 'html',
		'html' => '<input style="background-color:#BDFFBD;" type="text" id="attachments-'.$post->ID.'-shadow_blur" name="attachments['.$post->ID.'][shadow_blur]" data-setting="border" value="'.$shadow_blur.'" placeholder="(e.g. 10px )"/>',
        'helps' => __( '' )
    );
    
    
     
     
	// returning form fields with new field i.e. roundness
    return $form_fields;
}

/**
*  adding filter for displaying attachment fields
*/

add_filter( 'attachment_fields_to_edit', 'quick_image_roundness_testi', 10, 2 );


/**
*  function to save roundness field
* @return none
*/

function quick_image_roundness_save_testi( $attachment_id ) {
    if ( isset( $_REQUEST['attachments'][$attachment_id]['location'] ) ) {
        $location = $_REQUEST['attachments'][$attachment_id]['location'];
		// updating roundness field
        update_post_meta( $attachment_id, 'location', $location );
    }
    if ( isset( $_REQUEST['attachments'][$attachment_id]['border'] ) ) {
        $location = $_REQUEST['attachments'][$attachment_id]['border'];
		// updating border field
        update_post_meta( $attachment_id, 'border', $location );
    }
    if ( isset( $_REQUEST['attachments'][$attachment_id]['border_color'] ) ) {
        $location = $_REQUEST['attachments'][$attachment_id]['border_color'];
		// updating border_color field
        update_post_meta( $attachment_id, 'border_color', $location );
    }
    if ( isset( $_REQUEST['attachments'][$attachment_id]['shadow_size'] ) ) {
        $location = $_REQUEST['attachments'][$attachment_id]['shadow_size'];
		// updating border_color field
        update_post_meta( $attachment_id, 'shadow_size', $location );
    }
    if ( isset( $_REQUEST['attachments'][$attachment_id]['shadow_blur'] ) ) {
        $location = $_REQUEST['attachments'][$attachment_id]['shadow_blur'];
		// updating border_color field
        update_post_meta( $attachment_id, 'shadow_blur', $location );
    }

}

/**
*  adding action for saving attachment fields
*/

add_action( 'edit_attachment', 'quick_image_roundness_save_testi' );

/**
*  function to add style tag with the attachment link
*  @return html string containing the attachment link with style added
*/

function quick_image_add_style_testi($html, $id, $caption, $title, $align, $url, $size, $alt = '' ){

	$location = get_post_meta( $id, 'location', true );
        $border = get_post_meta( $id, 'border', true );
        if($border !='' ){ $solid = 'solid'; } else {$solid = ''; };
        $border_color = get_post_meta( $id, 'border_color', true );
        $shadow_size = get_post_meta( $id, 'shadow_size', true );
        $shadow_blur = get_post_meta( $id, 'shadow_blur', true );
	//replacing old link with new styled attachment link
	$html = str_replace('/></a>','style="-moz-box-shadow: 0px 0px 10px 5px '.$shadow.' #000;
-webkit-box-shadow: 0px 0px '.$shadow.' #000;
box-shadow: 0px 0px '.$shadow_size.' '.$shadow_blur.' #000 ;border:'.$border.' '.$border_color.' '.$solid.' ;border-radius:'.$location.'; behavior:url('.plugins_url().'/smart-testimonials/pie/PIE.php); position:relative; '. get_post($id)->post_excerpt.'" /></a>',$html);
	// returning new link
	return $html;
}

/**
*  adding filter for inserting new attachment link in wp_editor
*/
add_filter('image_send_to_editor','quick_image_add_style_testi',10,8);
?>
