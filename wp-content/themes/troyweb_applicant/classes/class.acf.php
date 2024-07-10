<?php namespace monotone;

class ACF {

    public function __construct() {
        add_action( 'init', [ $this, 'add_options_page' ] );
    }

    public function add_options_page() {
        if ( function_exists( 'acf_add_options_page' ) ) {
            acf_add_options_page( [
                'page_title' => 'Theme Options',
                'menu_title' => 'Theme Options',
                'menu_slug'  => 'theme-options',
                'capability' => 'edit_posts',
                'redirect'   => false,
                'position'   => 2,
            ] );
        }
    }


}

new ACF();
