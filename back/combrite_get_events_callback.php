<?php

function combrite_get_events_callback() {
	$keyword = $_POST['keyword'];
	$page = $_POST['page'];
	echo json_encode(combrite_get_items($keyword, $page));
	die(); // this is required to return a proper result
}
