<?php

namespace monotone;

class Navigation {
    public function __construct() {
        add_action( 'after_setup_theme', [ $this, 'register_menus' ] );
        add_filter( 'walker_nav_menu_start_el', [ $this, 'bm_nav_description' ], 10, 4 );
    }

    public function register_menus() {
        register_nav_menus( [
            'primary' => __( 'Primary Menu', 'monotone' ),
            'footer'  => __( 'Footer Menu', 'monotone' ),
        ] );
    }

    // Add descriptions to helpline navigation
	public function bm_nav_description( $item_output, $item, $depth, $args ) {
	    if ( 'helplineprimary' == $args->theme_location && $item->description ) {
	        // Insert a span to wrap the link text
	        $item_output = preg_replace('/(<a[^>]*>)([^<]*)(<\/a>)/', '$1<span class="menu-item-text">$2</span>$3', $item_output);

	        // Append the description after the link
	        $item_output = str_replace('</a>', '</a><div class="menu-item-description"><p>' . $item->description . '</p></div>', $item_output);
	    }

	    return $item_output;
	}

}

new Navigation();
