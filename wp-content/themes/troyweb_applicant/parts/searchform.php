<?php

namespace monotone; ?>

<?php

/*
 * Generate a unique ID for each form and a string containing an aria-label
 * if one was passed to get_search_form() in the args array.
 */
$_unique_id = wp_unique_id('search-form-');

$_aria_label = !empty($args['aria_label']) ? 'aria-label="' . esc_attr($args['aria_label']) . '"' : '';
?>
<form role="search" <?php echo $_aria_label; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped above. 
					?> method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
	<label for="<?php echo esc_attr($_unique_id); ?>"><?php _e('Search&hellip;', 'monotone'); // phpcs:ignore: WordPress.Security.EscapeOutput.UnsafePrintingFunction -- core trusts translations 
														?></label>
	<input type="search" id="<?php echo esc_attr($_unique_id); ?>" class="search-field" value="<?php echo get_search_query(); ?>" placeholder="Search" name="s" />
	<input type="submit" class="search-submit" value="<?php echo esc_attr_x('Search', 'submit button', 'monotone'); ?>" />
</form>