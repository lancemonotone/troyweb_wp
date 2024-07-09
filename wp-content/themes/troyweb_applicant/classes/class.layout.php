<?php namespace monotone;

class Layout {
	public static string $template_path = 'layouts/';

	public function __construct() {
	}

	/**
	 * Retrieve layout parts and display them.
	 *
	 * @param string $layout - The layout to retrieve.
	 * @param string $parent - The parent to retrieve from.
	 * @param bool $return - Whether to return the content or echo it.
	 *
	 * @return string|void
	 */
	public static function get_layout( string $layout, string $parent, bool $return = FALSE ) {
		$count = 1;
		while ( have_rows( $layout ) ) {
			the_row();
			$row_layout            = get_row_layout();
			$layout_settings       = Layout::get_layout_settings( $row_layout, $parent, $count ++ );
			$template_part_content = self::get_layout_template( $row_layout, $layout_settings );

			if ( $return && $template_part_content ) {
				return $template_part_content;
			}

			echo $template_part_content;
		}

		if ( $return ) {
			return;
		}
	}

	/**
	 * Retrieve and display a template part.
	 *
	 * @param string $slug - The template part slug.
	 * @param array $args - The arguments to pass to the template.
	 *
	 * @return string|null
	 */
	public static function get_layout_template( string $slug, array $args = [] ): ?string {
		$template_path = self::$template_path . $slug;
		ob_start();
		get_template_part( $template_path, NULL, $args );
		$content = ob_get_clean();

		return ( $content === '' ) ? NULL : $content;
	}

	/**
	 * Get the layout settings for a given module.
	 * This is used to set the module ID, classes, and styles.
	 *
	 * @param $row_layout
	 * @param $parent
	 * @param int $count
	 *
	 * @return array
	 */
	public static function get_layout_settings( $row_layout, $parent, int $count ): array {
		// convert _ to -
		$layout    = str_replace( '_', '-', $row_layout );
		$module_id = get_sub_field( 'module_id' ) ?: $parent . '-' . $layout . '-' . $count;

		$classes = [ 'layout', $layout ];
		$styles  = [];

		if ( get_sub_field( 'no_bottom_padding' ) ) {
			$classes [] = 'no-padding-bottom';
		}

		if ( get_sub_field( 'no_top_padding' ) ) {
			$classes [] = 'no-padding-top';
		}

		if ( get_sub_field( 'add_top_padding' ) ) {
			$classes [] = 'add-padding-top';
		}

		/**
		 * BACKGROUND COLOR
		 */
		if ( get_sub_field( 'background_color' ) ) {
			$classes [] = get_sub_field( 'background_color' );
		}

		/**
		 * HIDE PAGE TITLE
		 */
		if ( get_sub_field( 'hide_page_title' ) ) {
			$classes [] = 'hide-page-title';
		}

		/**
		 * SPLIT IMAGE
		 */
		if ( $split_type = get_sub_field( 'split_type' ) ) {
			$classes [] = $split_type;
		}

		/**
		 * BACKGROUND IMAGE
		 */
		if ( get_sub_field( 'background_image' ) ) {
			$classes [] = 'has-background-img lazy-bg';
			$styles []  = self::get_background_image_style( get_sub_field( 'background_image' ) );
		}

		$classes = implode( ' ', $classes );
		$styles  = implode( ' ', $styles );

		return [
			'id'      => $module_id,
			'classes' => $classes,
			'styles'  => $styles
		];
	}

	/**
	 * Get the background image style for a given element.
	 * If the image is an array, it will use the 2048x2048 size.
	 *
	 * If $url_only is true, it will only return the url of the image.
	 *
	 * @param $image
	 * @param bool $url_only
	 *
	 * @return string
	 */
	public static function get_background_image_style( $image, bool $url_only = FALSE ): string {
		$url = Images::get_image_url( [
			'id' => $image,
			'size'  => 'full'
		] );

		$style = 'background-image: url(' . $url . ');';

		if ( $url_only ) {
			return $style;
		}

		$style .= 'background-repeat: no-repeat; background-position: center center; background-size: cover;';

		return $style;
	}
}

new Layout();
