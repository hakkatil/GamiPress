<?php
/**
 * CMB2 Button and Multi Buttons Field Type
 */

/**
 * Render a button
 *
 * @param  array 			$field 			The field data array
 * @param  mixed 			$value 			The stored value for this field
 * @param  integer|string 	$object_id 		The object ID
 * @param  string 			$object_type 	The object type
 * @param  CMB2_Types 	    $field_type 	The field type object
 *
 * @return string       HTML markup for our field
 */
function cmb2_render_button( $field, $value, $object_id, $object_type, $field_type ) {

    // Parse args
    $attrs = $field_type->parse_args( 'button', array(
        'type'    => 'button',
        'name'    => $field_type->_name(),
        'id'      => $field_type->_id(),
        'class'   => 'button ' . ( isset( $field->args['button'] ) && ! empty( $field->args['button'] ) ? 'button-' . $field->args['button'] : '' ),
    ) );

    $button_pattern = '%s<button %s>%s</button>';

    if( isset( $field->args['action'] ) && ! empty( $field->args['action'] ) ) {
        $attrs['name'] = 'gamipress-action';
        $attrs['value'] = $field->args['action'];
        $attrs['type'] = 'submit';
    }

    echo sprintf( $button_pattern,
        $field_type->_desc( true ),
        $field_type->concat_attrs( $attrs ),
        ( isset( $field->args['label'] ) && ! empty( $field->args['label'] ) ? $field->args['label'] : $field->args( 'name' ) )
    );
}
add_action( 'cmb2_render_button', 'cmb2_render_button', 10, 5 );

/**
 * Render a multi buttons
 *
 * @param  array 			$field 			The field data array
 * @param  mixed 			$value 			The stored value for this field
 * @param  integer|string 	$object_id 		The object ID
 * @param  string 			$object_type 	The object type
 * @param  CMB2_Types 	    $field_type 	The field type object
 *
 * @return string       HTML markup for our field
 */
function cmb2_render_multi_buttons( $field, $value, $object_id, $object_type, $field_type ) {

    if( ! isset( $field->args['buttons'] ) ) {
        $field->args['buttons'] = array();
    }

    echo $field_type->_desc( true );

    foreach( $field->args['buttons'] as $button_id => $button ) {

        $attrs = $field_type->parse_args( 'multibutton', array(
            'class'   => 'button ' . ( isset( $button['button'] ) && ! empty( $button['button'] ) ? 'button-' . $button['button'] : '' ),
            'id'      => $button_id,
        ) );

        echo sprintf( '<button type="button" %s>%s</button>',
            $field_type->concat_attrs( $attrs ),
            ( isset( $button['label'] ) && ! empty( $button['label'] ) ? $button['label'] : '' )
        );
    }

}
add_action( 'cmb2_render_multi_buttons', 'cmb2_render_multi_buttons', 10, 5 );