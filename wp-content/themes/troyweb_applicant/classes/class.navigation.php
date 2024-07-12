<?php

namespace monotone;

class Navigation {
    public function __construct() {
        add_action('after_setup_theme', [$this, 'register_menus']);
        add_filter('nav_menu_link_attributes', [$this, 'prefix_bs5_dropdown_data_attribute'], 20, 3);
    }

    public function register_menus() {
        register_nav_menus([
            'primary' => __('Primary Menu', 'monotone'),
            'footer'  => __('Footer Menu', 'monotone'),
        ]);
    }
    /**
     * Use namespaced data attribute for Bootstrap's dropdown toggles.
     * @see https://github.com/wp-bootstrap/wp-bootstrap-navwalker
     * @uses class.wp-bootstrap-navwalker.php
     *
     * @param array    $atts HTML attributes applied to the item's `<a>` element.
     * @param WP_Post  $item The current menu item.
     * @param stdClass $args An object of wp_nav_menu() arguments.
     * @return array
     */
    function prefix_bs5_dropdown_data_attribute($atts, $item, $args) {
        if (is_a($args->walker, 'WP_Bootstrap_Navwalker')) {
            if (array_key_exists('data-toggle', $atts)) {
                unset($atts['data-toggle']);
                $atts['data-bs-toggle'] = 'dropdown';
            }
        }
        return $atts;
    }
}

new Navigation();
