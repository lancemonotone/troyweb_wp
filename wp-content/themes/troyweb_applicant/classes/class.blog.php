<?php

namespace monotone;

class Blog {
    public function __construct() {
    }

    /**
     * Fetch an array of post IDs based on given criteria.
     *
     * @param int $posts_per_page Number of posts to fetch.
     * @param int &$total_pages The total number of pages, passed by reference.
     *
     * @return array  An array of post IDs.
     */
    public static function get_post_ids($term, int $posts_per_page = 10, int &$total_pages = 0): array {
        // Get the current page number from query vars
        $current_page = get_query_var('paged') ? get_query_var('paged') : 1;

        // Build WP_Query arguments
        $args = [
            'post_type'      => 'post',
            'posts_per_page' => $posts_per_page,
            'paged'          => $current_page,
        ];

        // If a term is specified, add it to the query
        if ($term) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'category',
                    'field'    => 'slug',
                    'terms'    => $term,
                ],
            ];
        }

        // Execute the query
        $query = new \WP_Query($args);

        // Calculate total pages
        $total_posts = $query->found_posts;
        $total_pages = ceil($total_posts / $posts_per_page);

        // Fetch post IDs
        $post_ids = [];
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $post_ids[] = get_the_ID();
            }
        }

        // Reset postdata
        wp_reset_postdata();

        return $post_ids;
    }

    /**
     * Get term links as a comma-separated string for a given post and taxonomy.
     *
     * @param int $post_id The ID of the post.
     * @param string $taxonomy The taxonomy to retrieve terms from.
     *
     * @return string  A comma-separated string of term links.
     */
    public static function get_term_links(int $post_id, string $taxonomy = 'category'): string {
        // Get the terms once and store them in a variable
        $terms = get_the_terms($post_id, $taxonomy);

        // Initialize an array to hold the term links
        $term_links = [];

        // Loop through the stored terms
        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                $term_links[] = '<a href="' . site_url($term->slug) . '/">' . $term->name . '</a>';
            }
        }

        // Join term links into a comma-separated string
        return implode(', ', $term_links);
    }

    /**
     * Get the linked title of a given post.
     *
     * @param int $post_id The ID of the post.
     *
     * @return string  The title of the post wrapped in an anchor tag.
     */
    public static function get_linked_title(int $post_id): string {
        // Fetch the post title and permalink
        $title     = get_the_title($post_id);
        $permalink = get_permalink($post_id);

        // Return the title wrapped in an anchor tag
        return '<a href="' . esc_url($permalink) . '">' . esc_html($title) . '</a>';
    }

    /**
     * Get the content of a given post.
     *
     * @param int $post_id
     *
     * @return string
     */
    public static function get_post_content(int $post_id): string {
        // Fetch the formatted post content
        // Return the content
        return apply_filters('the_content', get_post_field('post_content', $post_id));
    }

    /**
     * Get the excerpt of a given post, falling back to the first n words of the post content.
     *
     * @param int $post_id The ID of the post.
     * @param int $word_count Number of words to use as a fallback.
     *
     * @return string  The excerpt or truncated post content.
     */
    public static function get_excerpt(int $post_id, int $word_count = 15): string {
        $excerpt_more_closure = function () {
            return '...';
        };

        $excerpt_length_closure = function () use ($word_count) {
            return $word_count;
        };

        // Register hook to change the readmore ellipsis
        add_filter('excerpt_more', $excerpt_more_closure);

        // Register hook to change the excerpt length
        add_filter('excerpt_length', $excerpt_length_closure);

        $excerpt = '';

        // Try to get the excerpt first or console_log an error
        // This breaks on the live server when the user isn't logged in, don't know why.
        // It was just an attempt to save a few cycles, so let's just comment it out.
        //$excerpt = get_the_excerpt( $post_id );

        // If the excerpt is empty, use the post content as a fallback
        if (empty($excerpt)) {
            $post_content = get_post_field('post_content', $post_id);
            $post_content = wp_strip_all_tags($post_content);  // Strip HTML tags

            // Limit the content to the first n words
            $words = explode(' ', $post_content, $word_count + 1);
            if (count($words) > $word_count) {
                array_pop($words);
                $excerpt = implode(' ', $words) . '...';
            } else {
                $excerpt = $post_content;
            }
        }

        // Remove the filters
        remove_filter('excerpt_more', $excerpt_more_closure);
        remove_filter('excerpt_length', $excerpt_length_closure);

        // add paragraph tags
        return '<p>' . $excerpt . '</p>';
    }

    /**
     * Get the date and author of a given post in a specific format.
     *
     * @param int $post_id The ID of the post.
     *
     * @return string  The formatted date and author string.
     */
    public static function get_post_date(int $post_id): string {
        // Fetch the post date in the 'F j, Y' format (e.g., July 28, 2023)
        $date = get_the_date('F j, Y', $post_id);

        // Combine date and author into the desired format
        return '<span class="post-date">' . $date . '</span>';
    }

    /**
     * Get the date and author of a given post in a specific format.
     *
     * @param int $post_id The ID of the post.
     *
     * @return string  The formatted date and author string.
     */
    public static function get_post_author(int $post_id): string {
        $link_author_name = get_field('link_author_name', 'option') ?? false;
        // Step 1: Check if hide_author_card is false
        $hide_author_card = get_field('hide_author_card', $post_id) ?? false;
        if (!$hide_author_card) {
            // Step 2: Check for override author in current post options,
            // then check for default author in Theme Options
            [$author_id, $author] = self::get_author($post_id);

            // Include author link if required
            $author = $link_author_name ? '<a href="' . get_author_posts_url($author_id) . '">' . $author . '</a>' : $author;

            // Combine into the desired format
            return '<span class="post-author">' . $author . '</span>';
        }

        return '';  // Return an empty string if hide_author_card is true
    }

    /**
     * Checks if an author card should be displayed for a given post and, if not hidden,
     * it constructs and includes the author's contact card template with their details.
     *
     * @param $post_id
     */
    public static function maybe_get_author_card($post_id) {
        // Check if the author card should be hidden
        $hide_author_card = get_field('hide_author_card', $post_id) ?? false;
        if ($hide_author_card) {
            return;
        }
        // Get the post or default author
        [$author_id, $author, $author_email] = self::get_author($post_id);

        // Prepare the author card data
        $card = [
            'heading'       => __('For questions contact'),
            'content'       => "<p><strong>{$author}</strong><br>{$author_email}</p>",
            'card_link'     => [
                'url'    => 'mailto:' . $author_email,
                'target' => 'target="_blank" rel="noopener noreferrer"',
            ],
            'heading_size'  => 'header-sm',
            'acf_fc_layout' => 'external_link_card',
        ];

        // Filter out empty class values
        $card_classes = [
            'has-background',
            'has-primary-light-background-color',
            str_replace('_', '-', 'external_link_card') // Assuming acf_fc_layout is always 'external_link_card'
        ];

        // Link aria-label
        $link_aria = $card['heading'] . ' ' . $author;

        // Include the author card template
        include(locate_template('layouts/cards/' . $card['acf_fc_layout'] . '.php'));
    }

    /**
     * @param int $post_id
     *
     * @return array
     */
    public static function get_author(int $post_id): array {
        $override_author = get_field('override_author', $post_id) ?? false;
        $default_author  = get_field('default_author', 'option');

        // Step 3: If override is true or the default author is not set, use post author
        if ($override_author || empty($default_author)) {
            $author_id    = get_post_field('post_author', $post_id);
            $author       = get_the_author_meta('display_name', $author_id);
            $author_email = get_the_author_meta('user_email', $author_id);
        } else {
            // Step 4: Use default author from Theme Options
            $author_id    = $default_author['ID']; // Assuming 'ID' is a key in your default_author array
            $author       = $default_author['display_name']; // Assuming 'display_name' is a key in your default_author array
            $author_email = $default_author['user_email'];
        }

        return [$author_id, $author, $author_email];
    }

    /**
     * Display pagination links.
     *
     * @param string $term The term (category slug) for which pagination is displayed.
     * @param int $total_pages The total number of pages.
     *
     * @return string The pagination HTML as a string.
     */
    public static function paginate_posts_archive($term, int $total_pages): string {
        // If only one page exists, return an empty string to hide pagination.
        if ($total_pages <= 1) {
            return '';
        }

        $current_page = get_query_var('paged') ? get_query_var('paged') : 1;
        $base_url     = home_url('/news/');

        // Add the term slug to the base URL if it exists
        if ($term) {
            $base_url .= 'category/' . $term . '/';
        }

        $pagination = <<<EOD
<div class="pagination pagination-posts">
EOD;

        // Always show the first page
        $pagination .= $current_page == 1 ?
            <<<EOD
<span class="current">1</span>
EOD
            :
            <<<EOD
<a href="{$base_url}">1</a>
EOD;

        // Insert ellipsis if needed
        if ($current_page > 4) {
            $pagination .= " ... ";
        }

        // Loop through the range around the current page
        for ($i = max(2, $current_page - 2); $i <= min($total_pages - 1, $current_page + 2); $i++) {
            if ($i == $current_page) {
                $pagination .= <<<EOD
 <span class="current">{$i}</span> 
EOD;
            } else {
                $pagination .= <<<EOD
 <a href="{$base_url}{$i}/">{$i}</a> 
EOD;
            }
        }

        // Insert ellipsis if needed
        if ($current_page < $total_pages - 3) {
            $pagination .= " ... ";
        }

        // Always show the last page
        if ($total_pages > 1) {
            $pagination .= $current_page == $total_pages ?
                <<<EOD
<span class="current">{$total_pages}</span>
EOD
                :
                <<<EOD
<a href="{$base_url}{$total_pages}/">{$total_pages}</a>
EOD;
        }

        $pagination .= <<<EOD
</div>
EOD;

        return $pagination;
    }

    /**
     * Get single previous/next post navigation in same category.
     *
     * @return string
     */
    public static function paginate_single_post(): string {
        $prev_post = get_previous_post(true);
        $next_post = get_next_post(true);

        if (!$prev_post && !$next_post) {
            return '';
        }

        // Define the SVG markup
        $svg = <<<SVG
<span class="card-link-icon">
<svg width="54" height="52" viewBox="0 0 54 52" fill="none" xmlns="http://www.w3.org/2000/svg">
    <rect x="0.0612793" y="0.5" width="53" height="51" rx="25.5" fill="white"></rect>
    <path fill-rule="evenodd" clip-rule="evenodd" d="M33.0613 19.5L33.0613 29.25L30.8946 29.25L30.8946 23.1987L21.9106 32.1827C21.4876 32.6058 20.8016 32.6058 20.3786 32.1827C19.9555 31.7596 19.9555 31.0737 20.3786 30.6506L29.3625 21.6667L23.3113 21.6667L23.3113 19.5L33.0613 19.5Z" fill="#00A3DA"></path>
</svg>
</span>
SVG;

        $output = <<<EOD
<div class="pagination pagination-single-post">
EOD;

        if ($next_post) {
            $next_title = get_the_title($next_post->ID);
            $next_link  = get_permalink($next_post->ID);

            $output .= <<<EOD
<a class="nav-left" href="{$next_link}">
    {$svg} <!-- Embed the SVG here -->
    {$next_title}
</a>
EOD;
        } else {
            $output .= <<<EOD
<div class="nav-left empty-nav"></div>
EOD;
        }

        if ($prev_post) {
            $prev_title = get_the_title($prev_post->ID);
            $prev_link  = get_permalink($prev_post->ID);

            $output .= <<<EOD
<a class="nav-right" href="{$prev_link}">
    {$prev_title}
    {$svg} <!-- Embed the SVG here -->
</a>
EOD;
        } else {
            $output .= <<<EOD
<div class="nav-right empty-nav"></div>
EOD;
        }

        $output .= <<<EOD
</div>
EOD;

        return $output;
    }
}

new Blog();
