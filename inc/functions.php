<?php
function wrp_get_related_posts($user_atts = [], $content = null, $tag = '') {
    if (!is_single()) {
        return;
    }

    $default_title = get_option('wrp_default_title', __('Related Posts', 'wcms19-relatedposts'));

    $default_atts = [
        'posts' =>  4,
        'title' =>  $default_title,
        'categories'    =>  null,
        'post'      =>  get_the_id(),
        'show_metadata'     => true,
    ];


    if (isset($user_atts['show_metadata']) && $user_atts['show_metadata'] === 'false') {
        $user_atts['show_metadata'] = false;
    }

    $atts = shortcode_atts($default_atts, $user_atts, $tag);

    if (!empty($atts['categories'])) {
        $category_ids = explode(',', $atts['categories']);
    }   else {
        $category_ids = wp_get_post_terms($atts['post'], 'category', ['fields' => 'ids']);
    }

    $posts = new WP_Query([
        'posts_per_page'    =>  $atts['posts'],
        'post__not_in'      =>  [$atts['post']],
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

            if($atts['show_metadata']) {
                $output .= "<small>";
                $output .= " in ";
                $output .= get_the_category_list(', ');
                $output .= " by ";
                $output .= get_the_author();
                $output .= " ";
                $output .= human_time_diff(get_the_time('U')) . ' ago';
                $output .= "</small>";
            }

            $output .= "</li>";
        endwhile;
        wp_reset_postdata();
        $output .= "</ul>";
    }   else {
        $output .= "Sorry, no related posts found!";
    }

    return $output;
}