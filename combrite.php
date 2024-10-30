<?php
/*
  Plugin Name: Combrite
  Plugin URI: http://wordpress.org/plugins/combrite/
  Description: The awesome Eventbrite and WordPress integration
  Version: 1.0.2
  Author: Combrite
  Author URI: http://wordpress.org/plugins/combrite/
  License: GPLv2
*/

include "class-symbolic-press.php";
new Symbolic_Press(__FILE__);

include(ABSPATH . 'wp-content/plugins/combrite/back/back.php');
include(ABSPATH . 'wp-content/plugins/combrite/front/front.php');
