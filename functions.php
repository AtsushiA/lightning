<?php
define( 'LIG_G3_DIR', '_g3' );

class LTG_Template_Redirect {

    public function __construct(){

        $ridirect_array = array(
            'index',
            '404', 
            'archive', 
            'author', 
            'category', 
            'tag', 
            'taxonomy', 
            'date', 
            'home', 
            'frontpage', 
            'privacypolicy', 
            'page',
            'search',
            'single',
            'embed',
            'singular',
            'attachment'
        );
        foreach ( $ridirect_array as $key => $type ){
            add_filter( "{$type}_template_hierarchy", array( __CLASS__, 'template_hierarchy_redirect' ) );
        }
        add_filter( 'template_directory_uri', array( __CLASS__, 'template_directory_uri' ) );
        // add_filter( 'comments_template', array( __CLASS__, 'comments_template' ) );
        add_filter( 'template_directory', array( __CLASS__, 'template_directory' ), 10, 3 );
    }



	public static function theme_directory(){
		$current_skin = get_option( 'lightning_design_skin' );
		if ( $current_skin === 'origin3' ){
			$dir = '_g3/';
		} else {
			$dir = '_g2/';
		}
		return $dir;
	}
	public static function template_hierarchy_redirect( $templates ){
        foreach ( $templates as $key => $template){
            $templates[$key] = self::theme_directory() . $template;
        }
        return $templates;
    }

    public static function template_directory_uri( $template_dir_uri  ){
        return $template_dir_uri . '/' . self::theme_directory();
    }

    // public static function comments_template( $theme_template  ){
    //     $theme_template = get_stylesheet_directory() . '/' . self::theme_directory() . '/comments.php';
    //     return $theme_template;
    // }
    public static function template_directory( $template_dir, $template, $theme_root  ){
		$template_dir = "$theme_root/$template";
        return $parent_theme_file_path . '/' . self::theme_directory();
    }
}

new LTG_Template_Redirect();

require get_parent_theme_file_path() . '/functions.php';