<?php namespace monotone;

class TinyMce {
    function __construct() {
        add_filter( 'mce_buttons', [ $this, 'add_custom_format_dropdown' ] );
        add_filter( 'tiny_mce_before_init', [ $this, 'add_custom_classname_options' ] );
        add_action( 'admin_init', [ $this, 'add_shortcode_button' ] );
        add_action( 'admin_init', [ $this, 'add_editor_stylesheet' ] );
    }

    public function add_editor_stylesheet() {
        add_editor_style( THEME_ASSETS_URI . '/build-editor/css/editor.css' );
    }

    /**
     * Add a custom format dropdown to the TinyMCE editor.
     *
     * @param $buttons
     *
     * @return array
     */
    public function add_custom_format_dropdown( $buttons ): array {
        $remove  = [ 'formatselect' ];
        $buttons = array_diff( $buttons, $remove );
        array_unshift( $buttons, 'styleselect' );

        return $buttons;
    }

    public function add_custom_classname_options( $settings ) {
        $style_formats = [
            [
                'title'    => 'Header Large',
                'block'    => 'h3',
                'selector' => '*', // All elements
                'classes'  => 'header-lg',
            ],
            [
                'title'    => 'Header Medium',
                'block'    => 'h3',
                'selector' => '*', // All elements
                'classes'  => 'header-md',
            ],
            [
                'title'    => 'Header Small',
                'block'    => 'h3',
                'selector' => '*', // All elements
                'classes'  => 'header-sm',
            ],
            [
                'title'    => 'Body Large',
                'block'    => 'p',
                'selector' => '*', // All elements
                'classes'  => 'body-lg',
            ],
            [
                'title'    => 'Body Small',
                'block'    => 'p',
                'selector' => '*', // All elements
                'classes'  => 'body-sm',
            ],
            [
                'title'    => 'List Override',
                'block'    => 'ul',
                'classes'  => 'override',
                'selector' => 'ul'
            ],
            [
                'title'    => 'List Quad',
                'block'    => 'ul',
                'classes'  => 'list-quad',
                'selector' => 'ul'
            ],
            [
                'title'    => 'List Triple',
                'block'    => 'ul',
                'classes'  => 'list-triple',
                'selector' => 'ul'
            ],
            [
                'title'    => 'Inactive',
                'selector' => '*',
                'classes'  => 'text-inactive',
            ],
            [
                'title'    => 'Small All Caps',
                'selector' => '*',
                'classes'  => 'small-all-caps',
            ],
        ];

        $settings[ 'block_formats' ]       = 'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5; Heading 6=h6; Blockquote=blockquote; Code=code; Preformatted=pre;';
        $settings[ 'toolbar1' ]            .= ',styleselect';
        $settings[ 'style_formats_merge' ] = true;
        $settings[ 'style_formats' ]       = json_encode( [
            [ 'title' => 'Custom Styles', 'items' => $style_formats, ],
        ] );
        $settings[ 'style_formats_label' ] = 'Styles';

        return $settings;
    }

    public function add_shortcode_button() {
        if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) ) {
            add_filter( 'mce_buttons_2', [ $this, 'register_shortcode_button' ] );
            add_filter( 'mce_external_plugins', [ $this, 'add_shortcode_plugin' ] );
            add_action( 'admin_head', [ $this, 'localize_shortcode_data' ] );

            // New: Add the click_to_copy button
            add_filter( 'mce_buttons', [ $this, 'register_click_to_copy_button' ] );
        }
    }

    public function register_click_to_copy_button( $buttons ) {
        // Add the new button to the first row of the editor
        array_push( $buttons, 'click_to_copy_button' );

        return $buttons;
    }

    public function register_shortcode_button( $buttons ) {
        array_splice( $buttons, 0, 0, 'custom_shortcodes_dropdown' );

        return $buttons;
    }

    public function add_shortcode_plugin( $plugin_array ) {
        // Register the existing custom shortcode plugin
        if ( file_exists( THEME_BUILD_URI . '/js/mce.custom_shortcodes_dropdown.js' ) ) {
            $plugin_array[ 'custom_shortcodes' ] = THEME_BUILD_URI . '/js/mce.custom_shortcodes_dropdown.js';
        }

        // New: Register the click_to_copy plugin
        if ( file_exists( THEME_BUILD_URI . '/js/mce.click_to_copy_shortcode.js' ) ) {
            $plugin_array[ 'click_to_copy_button' ] = THEME_BUILD_URI . '/js/mce.click_to_copy_shortcode.js';
        }

        return $plugin_array;
    }

    /**
     * Localize shortcode data for use in the TinyMCE editor Shortcodes dropdown.
     */
    public function localize_shortcode_data() {
        // Return value should be in this format:
        // $shortcodes = [
        // 	(object) [ 'text' => 'Navigation Menu', 'value' => '[menu name="" class=""]' ],
        // 	(object) [ 'text' => 'Button', 'value' => '[button url="#" class="" target=""]Button[/button]' ],
        // ];

        $shortcodes = array_map( function ( $shortcode ) {
            return (object)[
                'text'  => $shortcode[ 'template' ][ 'text' ],
                'value' => $shortcode[ 'template' ][ 'value' ],
            ];
        }, array_values( Shortcodes::get_shortcodes() ) );
        ?>
        <script type='text/javascript'>
            const custom_shortcodes_data = {
                'shortcodes': <?php echo json_encode( $shortcodes ); ?>,
            }
        </script>
        <?php
    }
}

new TinyMce();
