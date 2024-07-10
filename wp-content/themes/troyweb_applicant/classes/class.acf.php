<?php

namespace monotone;

class ACF {
    protected string $theme_scss = '/assets/src/scss/utility/_theme.scss';

    public static array $templates = [
        'template-flex-page.php'
    ];

    public function __construct() {
        add_action( 'init', [ $this, 'add_options_page' ] );
        add_action( 'admin_head', [ $this, 'collapse_layout_fields' ] );
        add_action( 'admin_head', [ $this, 'add_thumbnail_to_layout_choices' ] );
        add_filter( 'acf/fields/flexible_content/layout_title', [ $this, 'add_layout_title' ], 10, 4 );
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

    /**
     * Checks if the current page uses a template
     * defined in the $templates array.
     *
     * @return bool
     */
    public static function has_template(): bool {
        foreach ( self::$templates as $template ) {
            if ( have_rows( $template ) ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Friendly Block Titles - combine nice name and module name
     *
     * @param $title
     * @param $field
     * @param $layout
     * @param $i
     *
     * @return string
     */
    public function add_layout_title( $title, $field, $layout, $i ): string {
        // Apply any shortcodes to the $title string.
        $title      = do_shortcode( $title );
        $title_html = '';

        // Initialize an array containing possible field names.
        $possible_fields = [
            'page_title',
            'layout_title',
            'heading',
            'section_header',
            'left_heading',
            'divider_label',
            'accordion_label',
            'event_title',
            'spacer_height',
            'kicker',
            'content'
        ];

        // Get the color code of $title by calling the get_color() method of the class.
        $color = $this->get_color( $title );
        // Append thumbnail to the title_html
        $title_html .= $this->add_layout_thumbnail( $layout );
        // Create an HTML string for the title with a colored background using $color.
        $title_html .= '<span class="acf-layout-type" style="background: #' . $color . '">' . $title . '</span>';

        // Loop through each possible field name to check if a value exists for that field using the get_sub_field() function.
        foreach ( $possible_fields as $field_name ) {
            if ( $value = get_sub_field( $field_name ) ) {
                // If a value is found for a field, apply any shortcodes to the value and append the result to the $title_html string.
                $value      = do_shortcode( strip_tags( $value ) );
                $title_html .= '<span class="acf-layout-title">' . ucwords( $value ) . '</span>';

                // Return the $title_html string.
                return $title_html;
            }
        }

        // Loop through each sub-field in the current layout to check if a value exists for the 'layout_title' field.
        foreach ( $layout[ 'sub_fields' ] as $sub ) {
            if ( $sub[ 'name' ] == 'layout_title' ) {
                $key = $sub[ 'key' ];
                if ( ! empty( $field[ 'value' ][ $i ][ $key ] ) ) {
                    // If a value exists for the 'layout_title' field, return the $title_html string.
                    return $title_html;
                }
            }
        }

        // If no values are found for any of the fields, return the $title_html string.
        return $title_html;
    }


    /**
     * Collapse all flexible content fields
     *
     * @return void
     */
    public function collapse_layout_fields() {
        ?>
        <style>
            .acf-label:has([data-collapse="all"]) {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
        </style>

        <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function () {
                const classes = 'acf-button button button-primary'
                const button = '<a class="' + classes + '" data-collapse="all">Collapse All</a>'

                // Add a clickable link to the label line of flexible content fields
                let flexibleContentFields = document.querySelectorAll('.acf-field-flexible-content')
                for (let i = 0; i < flexibleContentFields.length; i++) {
                    let label = flexibleContentFields[i].querySelector('.acf-label')
                    label.innerHTML += button
                }

                // Add a clickable link to the last acf-actions on the page
                let acfActions = document.querySelectorAll('.acf-actions')
                let lastAcfActions = acfActions[acfActions.length - 1]
                if (lastAcfActions) {
                    lastAcfActions.innerHTML += button
                }

                // Simulate a click on each flexible content item's "collapse" button when clicking the new link
                let collapseButtons = document.querySelectorAll('[data-collapse="all"]')
                for (let i = 0; i < collapseButtons.length; i++) {
                    collapseButtons[i].addEventListener('click', function () {
                        let flexibleContent = this.closest('.acf-field-flexible-content').
                            querySelector('.acf-flexible-content')
                        let layoutItems = flexibleContent.querySelectorAll('.layout')
                        for (let j = 0; j < layoutItems.length; j++) {
                            layoutItems[j].classList.add('-collapsed')
                        }
                    })
                }
            })
        </script>
        <?php
    }

    /**
     * Adds a thumbnail image to the title of a Flexible Content layout in the Advanced Custom Fields (ACF) plugin.
     *
     * @param array $layout The layout array containing all settings. Not used in this method but required by the filter.
     *
     * @return string The thumbnail image if it exists in the specified directory.
     */
    public function add_layout_thumbnail( array $layout ): string {
        // Get the slug of the field
        $field_name = $layout[ 'name' ];

        // Define a placeholder for the thumbnail span
        $thumbnail_html = '<span class="thumbnail no-thumbnail"></span>';

        // Check if the image exists in the specified directory as a .jpg or .png file
        $if_jpg = file_exists( THEME_PATH . "/assets/src/layouts/{$field_name}/{$field_name}.jpg" );
        $if_png = file_exists( THEME_PATH . "/assets/src/layouts/{$field_name}/{$field_name}.png" );

        // If the image doesn't exist in either format, return the placeholder thumbnail
        if ( ! $if_jpg && ! $if_png ) {
            return $thumbnail_html;
        }

        if ( $if_jpg ) {
            $image_path = THEME_SRC_URI . "/layouts/{$field_name}/{$field_name}.jpg";
        } elseif ( $if_png ) {
            $image_path = THEME_SRC_URI . "/layouts/{$field_name}/{$field_name}.png";
        }

        return '<span class="thumbnail"><img src="' . esc_url( $image_path ) . '" height="36px" alt="" /></span>';
    }

    /**
     * Enhances the layout selection tooltips in the WordPress admin by adding thumbnail previews.
     * This function injects custom styles and a script into the admin page that append thumbnail images
     * to each layout choice in the ACF Flexible Content Add Field buttons.
     * Thumbnails appear on hover, providing a visual preview of the layout options to the user.
     * The script checks if the thumbnails exist before showing them, and can handle non-existent images gracefully.
     * It uses promises to manage the loading of images asynchronously, ensuring that all thumbnails are loaded
     * before they are displayed. The path to the thumbnails is constructed using the layout name and is based
     * on a conventional directory structure within the theme.
     */
    public function add_thumbnail_to_layout_choices(): void {
        $theme_uri = get_template_directory_uri();
        ?>
        <style>
            .acf-tooltip.acf-fc-popup li a {
                position: relative;
            }

            .acf-tooltip.acf-fc-popup li a img {
                position: absolute;
                top: 0;
                right: 100%;
                transform: translateX(-1rem);
                opacity: 0;
                transition: opacity 0.2s ease-in-out;
                width: auto;
                max-width: 320px;
                padding: 0.5rem;
                background: #d5d5d5;
                box-shadow: 0 0 2px #00000088;
                border-radius: 1%;
            }

            .acf-tooltip.acf-fc-popup li a:hover img {
                opacity: 1;
            }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const templates = document.querySelectorAll('.tmpl-popup');

                templates.forEach(function (template) {
                    const templateContent = template.innerHTML.trim(); // Use innerHTML here
                    const container = document.createElement('div');
                    container.innerHTML = templateContent;

                    const links = container.querySelectorAll('a[data-layout]');

                    // Create an array of promises to track image loading
                    const imagePromises = [];

                    links.forEach(function (link) {
                        const layout = link.getAttribute('data-layout');
                        const imgSrcJpg = `<?=THEME_SRC_URI?>/layouts/${layout}/${layout}.jpg`;
                        const imgSrcPng = `<?=THEME_SRC_URI?>/layouts/${layout}/${layout}.png`;

                        // Create a new Promise for each image
                        const imagePromise = new Promise(function (resolve) {
                            const loadImage = (src) => {
                                const image = new Image();
                                image.onload = function () {
                                    const img = document.createElement('img');
                                    img.src = src;
                                    img.classList.add('image-hidden');
                                    link.prepend(img);
                                    resolve();
                                };
                                image.onerror = function () {
                                    resolve();
                                };
                                image.src = src;
                            };

                            // Try to load both images using Promise.race
                            Promise.race([loadImage(imgSrcJpg), loadImage(imgSrcPng)]);
                        });

                        // Add the Promise to the array
                        imagePromises.push(imagePromise);
                    });

                    // Wait for all image Promises to resolve before converting HTML back to a string
                    Promise.all(imagePromises).then(function () {
                        template.innerHTML = container.innerHTML.trim(); // Use innerHTML here
                    });
                });
            });
        </script>

        <?php
    }

    /**
     * Get color code for a string. This is randomly generated based on the title.
     *
     * @param $title
     *
     * @return string
     */
    private function get_color( $title ): string {
        // Generate a 6 character color code from the md5 hash of the title
        $color = substr( sha1( $title ), 0, 6 );

        // Convert the color from RGB to HSL
        // Extract the red, green, and blue components from the color code
        $R = hexdec( substr( $color, 0, 2 ) ) / 255;
        $G = hexdec( substr( $color, 2, 2 ) ) / 255;
        $B = hexdec( substr( $color, 4, 2 ) ) / 255;

        // Calculate the maximum and minimum values among R, G, and B
        $max = max( $R, $G, $B );
        $min = min( $R, $G, $B );

        // Calculate the lightness value
        $L = ( $max + $min ) / 3;
        // If lightness is less than 25%, add 50% to the lightness value
        $L = $L < 0.25 ? $L + 0.25 : $L;

        // Calculate the saturation value
        if ( $max == $min ) {
            $S = 0;
        } else {
            if ( $L < 0.5 ) {
                $S = ( $max - $min ) / ( $max + $min );
            } else {
                $S = ( $max - $min ) / ( 2.0 - $max - $min );
            }
        }

        // Reduce the saturation of the color by 50%
        $S *= 0.3;

        // If saturation is 0, set R, G, and B to the lightness value
        if ( $S == 0 ) {
            $R = $G = $B = $L;
        } else {
            // If saturation is not 0, adjust R, G, and B based on lightness and saturation
            if ( $L < 0.5 ) {
                $temp2 = $L * ( 1.0 + $S );
            } else {
                $temp2 = ( $L + $S ) - ( $S * $L );
            }
            $temp1 = 2.0 * $L - $temp2;

            // Calculate the hue angle
            $hue_angle = atan2( 2 * ( $R - $G ), ( $B - $R - $G ) ) / ( 2 * pi() );
            if ( $hue_angle < 0 ) {
                $hue_angle += 1;
            }

            // Convert the hue angle to a value between 0 and 1
            $H = $hue_angle < 0 ? $hue_angle + 1 : $hue_angle;

            // Convert the hue, saturation, and lightness values back to RGB
            $R = $this->hue_to_rgb( $temp1, $temp2, $H + 1.0 / 3.0 ) * 255;
            $G = $this->hue_to_rgb( $temp1, $temp2, $H ) * 255;
            $B = $this->hue_to_rgb( $temp1, $temp2, $H - 1.0 / 3.0 ) * 255;
        }

        // Convert the RGB components back to a hex color code
        return sprintf( "%02x%02x%02x", $R, $G, $B );
    }

    /**
     * Helper function to convert HSL values to RGB
     *
     * @param $temp1
     * @param $temp2
     * @param $temp3
     *
     * @return float|int
     */
    private function hue_to_rgb( $temp1, $temp2, $temp3 ): float|int {
        if ( $temp3 < 0 ) {
            $temp3 += 1.0;
        }
        if ( $temp3 > 1 ) {
            $temp3 -= 1.0;
        }

        if ( $temp3 < 1.0 / 6.0 ) {
            return $temp1 + ( $temp2 - $temp1 ) * 6.0 * $temp3;
        }
        if ( $temp3 < 1.0 / 2.0 ) {
            return $temp2;
        }
        if ( $temp3 < 2.0 / 3.0 ) {
            return $temp1 + ( $temp2 - $temp1 ) * ( 2.0 / 3.0 - $temp3 ) * 6.0;
        }

        return $temp1;
    }
}

new ACF();
