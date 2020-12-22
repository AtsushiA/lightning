<?php

function lightning_get_template_part( $slug, $name = null, $args = array() ) {
    $current_skin = get_option( 'lightning_design_skin' );
    if ( $current_skin !== 'origin3' ){
        get_template_part( $slug, $name, $args );
    } else {
        /* Almost the same as the core */
        $templates = array();
        $name      = (string) $name;
        if ( '' !== $name ) {
            $templates[] = LIG_G3_DIR . '/' . "{$slug}-{$name}.php";
        }
     
        $templates[] = LIG_G3_DIR . '/' . "{$slug}.php";
     
        /**
         * Fires before a template part is loaded.
         *
         * @since 5.2.0
         * @since 5.5.0 The `$args` parameter was added.
         *
         * @param string   $slug      The slug name for the generic template.
         * @param string   $name      The name of the specialized template.
         * @param string[] $templates Array of template files to search for, in order.
         * @param array    $args      Additional arguments passed to the template.
         */
        do_action( 'get_template_part', $slug, $name, $templates, $args );
     
        if ( ! locate_template( $templates, true, false, $args ) ) {
            return false;
        }
    }
}

function lightning_get_class_name( $position = '' ) {

    $class_name = apply_filters( "lightning_get_class_name_{$position}", $position );

    if ( is_array( $class_name ) ){
        $classname = implode( " ", $classname );
    }

    return $class_name;
}

function lightning_the_class_name( $position = '' ) {
    echo esc_attr( lightning_get_class_name( $position ) );
}

/*
  Head logo
/*-------------------------------------------*/
function lightning_get_print_headlogo() {
	$options = get_option( 'lightning_theme_options' );
	if ( ! empty( $options['head_logo'] ) ) {
		$head_logo = apply_filters( 'lightning_head_logo_image_url', $options['head_logo'] );
		if ( $head_logo ) {
			return '<img src="' . esc_url( $head_logo ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" />';
		}
	}
	return get_bloginfo( 'name' );
}
function lightning_print_headlogo() {
	echo lightning_get_print_headlogo();
}