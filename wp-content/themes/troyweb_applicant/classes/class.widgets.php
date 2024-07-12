<?php

namespace monotone;

class Widgets {

	public function __construct() {
		add_action( 'widgets_init', array( $this, 'mytheme_widgets_init' ) );
	}

	public function mytheme_widgets_init() {
		register_sidebar( array(
            /* translators: Footer Sidebar text. */
			'name' => __( 'Footer Sidebar', 'monotone'),
			'id' => 'sidebar-1',
            /* translators: Sidebar that appears in the footer text. */
			'description' => __( 'Sidebar that appears in the footer.', 'monotone' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );
	}
}

new Widgets();
