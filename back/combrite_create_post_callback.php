<?php

function combrite_create_post_callback() {
  $event_id = $_POST['event_id'];
  echo json_encode(combrite_create_post($event_id));
  die(); // this is required to return a proper result
}

function combrite_create_page_callback() {
  $event_id = $_POST['event_id'];
  echo json_encode(combrite_create_page($event_id));
  die(); // this is required to return a proper result
}
