<?php

include(ABSPATH . 'wp-content/plugins/combrite/front/combrite_add_ticket_and_map_widgets_to_post.php');

add_filter('the_content', 'combrite_add_ticket_and_map_widgets_to_post');
