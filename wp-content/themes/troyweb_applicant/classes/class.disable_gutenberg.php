<?php

namespace monotone;

/**
 * Disable Editor
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
 **/
class DisableGutenberg {
    private $excluded_templates = [];
    private $excluded_ids = [];
    private $excluded_post_types = [];

    public function __construct() {
        // This should move to theme options
        if (class_exists('ACF')) {
            $this->excluded_templates = ACF::$templates;
        }

        add_filter('use_block_editor_for_post_type', [$this, 'digwp_disable_gutenberg'], 10, 2);
    }

    /**
     * Disable Gutenberg for post types and templates.
     * We're hijacking the use_block_editor_for_post_type 
     * filter to disable Gutenberg for the page template.
     * 
     * @param bool $is_enabled
     * @param string $post_type
     * @return bool
     */
    function digwp_disable_gutenberg($is_enabled, $post_type) {
        global $post;

        if (empty($post)) {
            return $is_enabled;
        }

        // Exclude post types
        if (in_array($post_type, $this->excluded_post_types)) {
            return false;
        }

        // Exclude templates
        foreach ($this->excluded_templates as $template) {
            if (get_page_template_slug($post->ID) === $template) {
                return false;
            }
        }

        return $is_enabled;
    }
}

new DisableGutenberg();
