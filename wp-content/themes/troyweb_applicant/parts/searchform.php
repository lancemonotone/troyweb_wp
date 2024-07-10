<?php

namespace monotone; ?>

<?php

/*
 * Generate a unique ID for each form and a string containing an aria-label
 * if one was passed to get_search_form() in the args array.
 */
$_unique_id = wp_unique_id( 'search-form-' );

$_aria_label = ! empty( $args[ 'aria_label' ] ) ? 'aria-label="' . esc_attr( $args[ 'aria_label' ] ) . '"' : '';
?>

<form class="d-flex" role="search" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" <?= $_aria_label; ?>>
    <label class="visually-hidden" for="<?php echo esc_attr( $_unique_id ); ?>"></label>
    <input type="text" class="form-control me-2" placeholder="<?php echo esc_attr_x( 'Search', 'submit button', 'monotone' ); ?>" name="s" id="<?php echo esc_attr( $_unique_id ); ?>" value="<?php echo get_search_query(); ?>">
    <button class="btn btn-outline-primary text-nowrap" type="submit"><?php echo esc_attr_x( 'Search', 'submit button', 'monotone' ); ?></button>
</form>


