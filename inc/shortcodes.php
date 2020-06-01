<?php
function wrp_shortcode($user_atts = [], $content = null, $tag = '') {
    return wrp_get_related_posts($user_atts, $content, $tag);
}

function wrp_init() {
    add_shortcode('related-posts', 'wrp_shortcode');
}
add_action('init', 'wrp_init');