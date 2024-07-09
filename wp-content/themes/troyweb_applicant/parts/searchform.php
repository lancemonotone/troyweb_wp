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

<form class="navbar-form" role="search" action="<?php echo esc_url(home_url('/')); ?>" method="get" <?php echo $_aria_label; ?>>
	<div class="input-group">
		<input type="text" class="form-control search-field" placeholder="Search" name="s" id="<?php echo esc_attr($_unique_id); ?>" value="<?php echo get_search_query(); ?>">
		<div class="input-group-btn">
			<button class="btn btn-default search-submit" type="submit"><?php echo esc_attr_x('Search', 'submit button', 'monotone'); ?></button>
		</div>
	</div>
</form>