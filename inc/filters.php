<?php
function wrp_the_content($content)  {
    if (is_single()) {
        // If there is NO shortcode called "related-posts" in $content, add it to end of $content
        if (!has_shortcode($content, 'related-posts')) {
            $related_posts = wrp_get_related_posts();
            $content = $content . $related_posts;
            // $content .= $related_posts // More efficient
        }
    }
    return $content;
}
add_filter('the_content', 'wrp_the_content');