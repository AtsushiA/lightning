<?php

/*
  customize_register
/*-------------------------------------------*/
add_action( 'customize_register', 'lightning_customize_register_column' );
function lightning_customize_register_column( $wp_customize ) {

	/*--------------------------------------*/
	/*	Column Setting
	/*--------------------------------------*/

	$wp_customize->add_section(
		'lightning_column',
		array(
			'title'    	=> lightning_get_prefix_customize_panel() . __( 'Column Setting', 'lightning' ),
			'panel'		=> 'lightning_layout',
		)
	);

	// Add setting
	$wp_customize->add_setting(
		'ltg_column_setting',
		array(
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Custom_Html_Control(
			$wp_customize,
			'ltg_column_setting',
			array(
				'label'            => __( 'Column Setting', 'lightning' ) . ' ( ' . __( 'PC mode', 'lightning' ) . ' )',
				'section'          => 'lightning_column',
				'type'             => 'text',
				'custom_title_sub' => '',
				'custom_html'      => '',
				// 'priority'         => 700,
			)
		)
	);

	$page_types = array(
		'front-page' => array(
			'label'       => __( 'Home page', 'lightning' ),
			'description' => '',
		),
		'search'     => array(
			'label' => __( 'Search', 'lightning' ),
		),
		'error404'   => array(
			'label' => __( '404 page', 'lightning' ),
		),
		'archive-author'     => array(
			'label' => __( 'Archive Page', 'lightning' ). ' [' . __( 'Author', 'lightning' ) . ']',
		),
		// If cope with custom post types that like a "archive-post" "single-post".
	);

	$get_post_types = get_post_types(
		array(
			'public'   => true,
		),
		'object'
	);

	foreach ( $get_post_types as $get_post_type ) {
		$archive_link = get_post_type_archive_link( $get_post_type->name );
		if ( $archive_link ){
			$page_types = $page_types + array(
				'archive-' . $get_post_type->name => array(
					'label' => __( 'Archive Page', 'lightning' ). ' [' . esc_html( $get_post_type->label ) . ']',
				),
			);
		}
	}

	foreach ( $get_post_types as $get_post_type ) {
		$page_types = $page_types + array(
			'single-' . $get_post_type->name => array(
				'label' => __( 'Single Page', 'lightning' ). ' [' . esc_html( $get_post_type->label ) . ']',
			),
		);
	}

	$choices = array(
		'default'               => __( 'Unspecified', 'lightning' ),
		'col-two'               => __( '2 column', 'lightning' ),
		'col-one'               => __( '1 column', 'lightning' ),
		'col-one-no-subsection' => __( '1 column ( No sub section )', 'lightning' ),
	);
	$choices = apply_filters( 'lighghtning_columns_setting_choice', $choices );

	$wp_customize->selective_refresh->add_partial(
		'lightning_theme_options[layout][front-page]',
		array(
			'selector'        => '.mainSection',
			'render_callback' => '',
		)
	);

	foreach ( $page_types as $key => $value ) {
		$wp_customize->add_setting(
			'lightning_theme_options[layout][' . $key . ']',
			array(
				'default'           => 'default',
				'type'              => 'option',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'lightning_theme_options[layout][' . $key . ']',
			array(
				'label'    => $value['label'],
				'section'  => 'lightning_column',
				'settings' => 'lightning_theme_options[layout][' . $key . ']',
				'type'     => 'select',
				'choices'  => $choices,
				// 'priority' => 700,
			)
		);
	}
}