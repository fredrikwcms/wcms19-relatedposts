<?php 
/* 
*   Plugin Name: WCMS19 Related Posts
*   Plugin URI: null
*   Description: This plugin adds a shortcode to display the related posts for a post.
*   Version: 0.1
*   Author: Fredrik Larsson
* License:  WTFPL
* License URI: http://www.wtfpl.net/
* Text Domain: wcms19-relatedposts
* Domain Path: /languages
*/

function wrp_shortcode($user_atts = [], $content = null, $tag = '') {
    $default_atts = [
        'posts' =>  4,
        'title' =>  __('Related Posts', 'wcms19-relatedposts'),
    ];

    $atts = shortcode_atts($default_atts, $user_atts, $tag);

    $current_post_id = get_the_ID();
    $current_post_categories = get_the_category();

    $category_ids = [];
    foreach ($current_post_categories as $current_post_category)    {
        array_push($category_ids, $current_post_category->term_id);
    }

    $posts = new WP_Query([
        'posts_per_page'    =>  $atts['posts'],
        'post__not_in'      =>  [$current_post_id],
        'category__in'      =>  $category_ids,
    ]);

    $output = "<h2>" . esc_html($atts['title']) . "</h2>";
    
    if ($posts->have_posts()) {
        $output .= "<ul>";
        while ($posts->have_posts()) :
            $posts->the_post();
            $output .= "<li>";
            $output .= "<a href='" . get_the_permalink() . "'>";
            $output .= get_the_title();
            $output .= "</a>";

            $output .= "<small>";
            $output .= " in ";
            $output .= get_the_category_list(', ');
            $output .= " by ";
            $output .= get_the_author();
            $output .= " ";
            $output .= human_time_diff(get_the_time('U')) . ' ago';
            $output .= "</small>";

            $output .= "</li>";
        endwhile;
        wp_reset_postdata();
        $output .= "</ul>";
    }   else {
        $output .= "Sorry, no related posts found!";
    }

    return $output;
}

function wrp_init() {
    add_shortcode('related-posts', 'wrp_shortcode');
}
add_action('init', 'wrp_init');