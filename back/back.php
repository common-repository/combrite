<?php

include(ABSPATH . 'wp-content/plugins/combrite/back/combrite_events.php');
include(ABSPATH . 'wp-content/plugins/combrite/back/combrite_create_post.php');
include(ABSPATH . 'wp-content/plugins/combrite/back/combrite_generate_the_admin_interface.php');
include(ABSPATH . 'wp-content/plugins/combrite/back/combrite_get_events_callback.php');
include(ABSPATH . 'wp-content/plugins/combrite/back/combrite_create_post_callback.php');

add_action('admin_menu', 'combrite_settings_menu');
add_action('wp_ajax_get_events', 'combrite_get_events_callback');
add_action('wp_ajax_create_post', 'combrite_create_post_callback');
add_action('wp_ajax_create_page', 'combrite_create_page_callback');

function combrite_settings_menu() {
  add_menu_page( 'Combrite', 'Combrite', 'manage_options', 'combrite', 'combrite_generate_the_admin_interface', plugins_url( 'img/logo.png', __FILE__ ) );
}