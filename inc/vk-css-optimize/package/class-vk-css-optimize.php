<?php
/*
The original of this file is located at:
https://github.com/vektor-inc/vektor-wp-libraries
If you want to change this file, please change the original file.
*/

/**
 * VK CSS Optimize
 *
 */

/**
 * VK CSS Tree Shaking Class
 */
if ( ! class_exists( 'VK_CSS_Optimize' ) ) {
	class VK_CSS_Optimize {

		public function __construct() {
			add_action( 'customize_register', array( __CLASS__, 'customize_register' ) );
			add_filter( 'css_tree_shaking_exclude', array( __CLASS__, 'tree_shaking_exclude' ) );

			$options = VK_CSS_Optimize::get_css_optimize_options();

			if ( ! empty( $options['tree_shaking'] ) ) {
				add_action( 'get_header', array( __CLASS__, 'get_html_start' ), 2147483647 );
				add_action( 'shutdown', array( __CLASS__, 'get_html_end' ), 0 );
			}			
			
			if ( ! empty( $options['preload'] ) ) {
				add_filter( 'style_loader_tag', array( __CLASS__, 'css_preload' ), 10, 4 );
			}
		}

		public static function customize_register( $wp_customize ){
			global $prefix_customize_panel;
			$wp_customize->add_section(
				'css_optimize', array(
					'title'    => $prefix_customize_panel . __( 'CSS Optimize ( Speed up ) Settings', 'css_optimize_textdomain' ),
					'priority' => 450,
				)
			);
		
			// Tree shaking
			////////////////////////////////////////////////////////////////

			$wp_customize->add_setting(
				'tree_shaking_title',
				array(
					'sanitize_callback' => 'sanitize_text_field',
				)
			);
			$wp_customize->add_control(
				new Custom_Html_Control(
					$wp_customize,
					'tree_shaking_title',
					array(
						'label'            => __( 'Tree shaking', 'css_optimize_textdomain' ),
						'section'          => 'css_optimize',
						'type'             => 'text',
						'custom_title_sub' => '',
						// 'custom_html'      => __( 'Move part of CSS and JS to the footer to improve display speed.', 'css_optimize_textdomain' ),
					)
				)
			);
		
			$wp_customize->add_setting(
				'vk_css_optimize_options[tree_shaking]',
				array(
					'default'           => '',
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => array( 'VK_Helpers', 'sanitize_choice' ),
				)
			);
			$wp_customize->add_control(
				'vk_css_optimize_options[tree_shaking]',
				array(
					'label'    		=> __( 'Tree shaking activation settings', 'css_optimize_textdomain' ),
					'section'  		=> 'css_optimize',
					'settings' 		=> 'vk_css_optimize_options[tree_shaking]',
					'type'     		=> 'select',
					'description'	=> __( 'Output only the main CSS of the page inline', 'css_optimize_textdomain' ),
					'choices'  => array(
						''			=> __( 'Nothing to do', 'css_optimize_textdomain' ),
						'active'	=> __( 'Active Tree shaking (Recomend)', 'css_optimize_textdomain' ),
					),
				)
			);
		
			$wp_customize->add_setting(
				'vk_css_optimize_options[tree_shaking_class_exclude]',
				array(
					'default'           => '',
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'sanitize_text_field',
				)
			);
			$wp_customize->add_control(
				'vk_css_optimize_options[tree_shaking_class_exclude]',
				array(
					'label'       => __( 'Exclude class of tree shaking', 'css_optimize_textdomain' ),
					'section'     => 'css_optimize',
					'settings'    => 'vk_css_optimize_options[tree_shaking_class_exclude]',
					'type'        => 'textarea',
					'description' => __( 'If you choose "Active Tree shaking" that delete the useless css.If you using active css class that please fill in class name. Ex) btn-active,slide-active,scrolled', 'css_optimize_textdomain' ),
				)
			);

			// Preload
			////////////////////////////////////////////////////////////////
			$wp_customize->add_setting(
				'css_preload_title',
				array(
					'sanitize_callback' => 'sanitize_text_field',
				)
			);
			$wp_customize->add_control(
				new Custom_Html_Control(
					$wp_customize,
					'css_preload_title',
					array(
						'label'            => __( 'Preload CSS', 'css_optimize_textdomain' ),
						'section'          => 'css_optimize',
						'type'             => 'text',
						'custom_title_sub' => '',
						// 'custom_html'      => __( 'Move part of CSS and JS to the footer to improve display speed.', 'css_optimize_textdomain' ),
					)
				)
			);

			$wp_customize->add_setting(
				'vk_css_optimize_options[preload]',
				array(
					'default'           => '',
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'sanitize_text_field',
				)
			);
			$wp_customize->add_control(
				'vk_css_optimize_options[preload]',
				array(
					'label'       => __( 'Preload CSS activation settings', 'css_optimize_textdomain' ),
					'section'     => 'css_optimize',
					'settings'    => 'vk_css_optimize_options[preload]',
					'description' => __( 'Preload css except for critical css', 'css_optimize_textdomain' ),
					'type'        => 'select',
					'choices'  => array(
						''   	 	=> __( 'Nothing to do', 'css_optimize_textdomain' ),
						'active'    => __( 'Active Preload CSS (Recomend)', 'css_optimize_textdomain' ),
					),
				)
			);

		}

		/**
		 * 
		 */
		public static function get_css_optimize_options(){

			$theme_textdomain = wp_get_theme()->get( 'TextDomain' );
			if ( 'lightning' === $theme_textdomain || 'lightning-pro' === $theme_textdomain ){
				$old_options = get_option( 'lightning_theme_options' );
			} else if ( 'katawara' === $theme_textdomain ){
				$old_options = get_option( 'katawara_theme_options' );
			} else {
				$old_options = get_option( 'vk_blocks_options' );
			}

			$vk_css_optimize_options = get_option( 'vk_css_optimize_options' );
			$vk_css_optimize_options_default = array(
				'tree_shaking'	=> 'active',
				'preload' 		=> 'active',
			);

			// fall back function
			// Actualy other array exist but optimize_css is most important
			if ( ! isset( $vk_css_optimize_options['tree_shaking'] ) ) {

				if ( isset( $old_options['optimize_css'] ) ){
					if ( $old_options['optimize_css'] === 'optomize-all-css' || $old_options['optimize_css'] === 'tree-shaking' ){
						$vk_css_optimize_options['tree_shaking'] 	= 'active';
					} else {
						$vk_css_optimize_options['tree_shaking'] 	= '';
					}
				}
			}

			if ( ! isset( $vk_css_optimize_options['tree_shaking_class_exclude'] ) ) {
				if ( ! empty( $old_options['tree_shaking_class_exclude'] ) ){
					$vk_css_optimize_options['tree_shaking_class_exclude'] = esc_html( $old_options['tree_shaking_class_exclude'] );
				}
			}

			if ( ! isset( $vk_css_optimize_options['preload'] ) ) {

				if ( isset( $old_options['optimize_css'] ) ){
					if ( $old_options['optimize_css'] === 'optomize-all-css'){
						$vk_css_optimize_options['preload'] 		='active';
					} else {
						$vk_css_optimize_options['preload'] 		= '';
					}

				}
				
			}
			$vk_css_optimize_options = wp_parse_args( $vk_css_optimize_options, $vk_css_optimize_options_default );
			if (
				! isset( $vk_css_optimize_options['tree_shaking'] ) || 
				! isset( $vk_css_optimize_options['tree_shaking_class_exclude'] ) || 
				! isset( $vk_css_optimize_options['preload'] ) 
			){
				update_option( 'vk_css_optimize_options', $vk_css_optimize_options );
			}

			return $vk_css_optimize_options;
		}

		public static function get_html_start() {
			ob_start( 'VK_CSS_Optimize::css_tree_shaking_buffer' );
		}

		public static function get_html_end() {
			if ( ob_get_length() ){
				ob_end_flush();
			}
		}

		public static function css_tree_shaking_array() {
			$vk_css_tree_shaking_array = array();
			$vk_css_tree_shaking_array = apply_filters( 'vk_css_tree_shaking_array', $vk_css_tree_shaking_array );
			return $vk_css_tree_shaking_array;
		}

		public static function css_tree_shaking_buffer( $buffer ) {

			$options = VK_CSS_Optimize::get_css_optimize_options();

			// CSS Tree Shaking.
			require_once dirname( __FILE__ ) . '/class-css-tree-shaking.php';
			$vk_css_tree_shaking_array = VK_CSS_Optimize::css_tree_shaking_array();

			foreach ( $vk_css_tree_shaking_array as $vk_css_array ) {
				$options['ssl']['verify_peer']      = false;
				$options['ssl']['verify_peer_name'] = false;

				require_once(ABSPATH.'wp-admin/includes/file.php');
				$path_name = $vk_css_array['path'];
				if( WP_Filesystem() ){
					global $wp_filesystem;
					$css = $wp_filesystem->get_contents($path_name);
				}

				$css                                = celtislab\CSS_tree_shaking::extended_minify( $css, $buffer );
				$buffer                             = str_replace(
					'<link rel=\'stylesheet\' id=\'' . $vk_css_array['id'] . '-css\'  href=\'' . $vk_css_array['url'] . '?ver=' . $vk_css_array['version'] . '\' type=\'text/css\' media=\'all\' />',
					'<style id=\'' . $vk_css_array['id'] . '-css\' type=\'text/css\'>' . $css . '</style>',
					$buffer
				);
				$buffer                             = str_replace(
					'<link rel=\'stylesheet\' id=\'' . $vk_css_array['id'] . '-css\'  href=\'' . $vk_css_array['url'] . '\' type=\'text/css\' media=\'all\' />',
					'<style id=\'' . $vk_css_array['id'] . '-css\' type=\'text/css\'>' . $css . '</style>',
					$buffer
				);

			}

			return $buffer;
		}

		public static function css_preload( $tag, $handle, $href, $media ) {

			$vk_css_tree_shaking_array = VK_CSS_Optimize::css_tree_shaking_array();

			$tree_shaking_handles = array();

			$options = VK_CSS_Optimize::get_css_optimize_options();

			// tree shaking がかかっているものはpreloadから除外する
			// でないと表示時に一瞬崩れて結局実用性に問題があるため
			foreach ( $vk_css_tree_shaking_array as $vk_css_array ) {
				$tree_shaking_handles[] = $vk_css_array['id'];
			}
			// クリティカルじゃないCSS（tree shakingにかけているもの以外）をpreload
			if ( ! in_array( $handle, $tree_shaking_handles ) ){
				$tag = "<link rel='preload' id='".$handle."-css' href='".$href."' as='style' onload=\"this.onload=null;this.rel='stylesheet'\"/>\n";
				$tag .= "<link rel='stylesheet' id='".$handle."-css' href='".$href."' media='print' onload=\"this.media='all'; this.onload=null;\">\n";
			}

			return $tag;
		}


		/**
		 * Exclude CSS.
		 *
		 * @param string $inidata exclude css class.
		 */
		public static function tree_shaking_exclude( $inidata ) {
			$options = VK_CSS_Optimize::get_css_optimize_options();

			$exclude_classes_array = array();

			if ( ! empty( $options['tree_shaking_class_exclude'] ) ) {

				// delete before after space.
				$exclude_clssses = trim( $options['tree_shaking_class_exclude'] );

				// convert tab and br to space.
				$exclude_clssses = preg_replace( '/[\n\r\t]/', '', $exclude_clssses );

				// Change multiple spaces to single space.
				$exclude_clssses       = preg_replace( '/\s/', '', $exclude_clssses );
				$exclude_clssses       = str_replace( '，', ',', $exclude_clssses );
				$exclude_clssses       = str_replace( '、', ',', $exclude_clssses );
				$exclude_classes_array = explode( ',', $exclude_clssses );

			}

			$inidata['class'] = array_merge( $inidata['class'], $exclude_classes_array );

			return $inidata;
		}
		
	}
	new VK_CSS_Optimize();
}
