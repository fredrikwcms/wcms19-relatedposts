<?php

require('class.RelatedPostsWidget.php');

function wrp_widgets_init() {
    register_widget('RelatedPostsWidget');
}
add_action('widgets_init', 'wrp_widgets_init');