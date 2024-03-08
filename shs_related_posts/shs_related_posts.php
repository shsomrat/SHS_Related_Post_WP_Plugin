<?php
/*
 * Plugin Name:       S H Somrat's Related Posts Plugin
 * Plugin URI:        https://example.com/plugins/shs-related-posts/
 * Description:       Enhances user engagement by displaying related posts under the content of each post.
 * Version:           1.0.0
 * Author:            S H Somrat
 * Text Domain:       shs-related-posts
 * Domain Path:       /languages
 */

class SHS_Related_Posts {
    /**
     * Constructor to initialize the plugin.
     */
    public function __construct() {
        // Hook into WordPress initialization.
        add_action('init', array($this, 'init'));
    }

    /**
     * Initialize the plugin by adding necessary hooks.
     */
    public function init() {
        // Hook into the_content filter to add related posts.
        add_filter('the_content', array($this, 'add_related_posts'));
    }

    /**
     * Add related posts at the end of the content.
     *
     * @param string $content The content of the post.
     * @return string Modified content with related posts.
     */
    public function add_related_posts($content) {
        // Get the current post's URL and title.
        $current_link = esc_url(get_permalink());
        $title = get_the_title();
        // Start the custom content div with styling.
        $custom_content = '<div>';
        $custom_content .= '<h2 style="margin-top: 40px; margin-bottom: 20px;">You Can Read These</h2>';

        // Query for related posts.
        $args = array(
            'post__not_in' => array(get_the_ID()),
            'posts_per_page' => 5,
            'orderby' => 'rand',
            'post_type' => 'post',
            'category__in' => []
        );
        $related_query = new WP_Query($args);
        $related_posts = $related_query->get_posts();

        // Loop through related posts and build the HTML.
        foreach ($related_posts as $related_post) {
            // Get the thumbnail URL for each related post.
            $thumbnail_url = get_the_post_thumbnail_url($related_post->ID, 'thumbnail');
            // Add the thumbnail and post title to the custom content.
            $custom_content .= '<div style="margin-bottom: 10px;"><img src="' . esc_url($thumbnail_url) . '" alt="Thumbnail" style="max-width: 50px; height: auto; margin-right: 10px;">';
            $custom_content .= '<a href="' . esc_url(get_permalink($related_post->ID)) . '">' . esc_html($related_post->post_title) . '</a></div>';
        }

        // Close the custom content div.
        $custom_content .= '</div>';

        // Append the custom content to the post content.
        $content .= $custom_content;
        return $content;
    }
}

// Instantiate the plugin class to initiate the plugin.
new SHS_Related_Posts();
