<?php

function combrite_clean_html($text) {
  $doc = new DOMDocument();
  @$doc->loadHTML('<?xml encoding="UTF-8">' . $text);
  // dirty fix
  foreach ($doc->childNodes as $item) {
      if ($item->nodeType == XML_PI_NODE) {
        $doc->removeChild($item); // remove hack
      }
  }
  $doc->encoding = 'UTF-8'; // insert proper

  $xpath = new DOMXPath($doc);            // create a new XPath
  $nodes = $xpath->query('//*[@style]');  // Find elements with a style attribute
  foreach ($nodes as $node) {              // Iterate over found elements
    $node->removeAttribute('style');    // Remove style attribute
  }
  $text = $doc->saveHTML();      

  return $text;
}

function _combrite_prepare_content($event_id) {
  $event = combrite_get_item($event_id);

  $template = wp_remote_get(plugins_url() . '/../uploads/combrite/custom.html');
  if ($template['response']['code'] == '404') {
    $template = wp_remote_get(plugins_url() . '/combrite/front/template/default.html');
  }
  $template = wp_remote_retrieve_body($template);
  
  // START calculate the event 'When' string
  $when = '';

  if (isset($event->repeat_schedule)) {

    $string = $event->repeat_schedule;
    $tokens = explode("-", $string);

    if ($tokens[0] == 'custom') { /* decide what to do */ }
    if ($tokens[0] == 'daily') {
      $when .= 'Daily Event';

      if ($tokens[1] > 1) {
          $when .= ' - Every ' . $tokens[1] . ' days';
      }
    }
    if ($tokens[0] == 'weekly') {
      $when .= 'Weekly Event - ';

      if ($tokens[1] > 1) {
          $when .= 'Every ' . $tokens[1] . ' weeks - ';
      }

      $when .= 'Every ';

      $days = '';
      if (substr($tokens[2], 12, 1) == 'Y') {
          $days = 'Sunday';
          $alreadyOne = true;
      }

      if (substr($tokens[2], 10, 1) == 'Y') {
          if ($alreadyOne) {
              $days = '& ' . $days;
              $lastAlreadyInserted = true;
          }
          $days = 'Saturday ' . $days;
          $alreadyOne = true;
      }
      if (substr($tokens[2], 8, 1) == 'Y') {
          if ($alreadyOne && !$lastAlreadyInserted) {
              $days = '& ' . $days;
              $lastAlreadyInserted = true;
          }
          $days = 'Friday ' . $days;
          $alreadyOne = true;
      }
      if (substr($tokens[2], 6, 1) == 'Y') {
          if ($alreadyOne && !$lastAlreadyInserted) {
              $days = '& ' . $days;
              $lastAlreadyInserted = true;
          }
          $days = 'Thursday ' . $days;
          $alreadyOne = true;
      }
      if (substr($tokens[2], 4, 1) == 'Y') {
          if ($alreadyOne && !$lastAlreadyInserted) {
              $days = '& ' . $days;
              $lastAlreadyInserted = true;
          }
          $days = 'Wednesday ' . $days;
          $alreadyOne = true;
      }
      if (substr($tokens[2], 2, 1) == 'Y') {
          if ($alreadyOne && !$lastAlreadyInserted) {
              $days = '& ' . $days;
              $lastAlreadyInserted = true;
          }
          $days = 'Tuesday ' . $days;
          $alreadyOne = true;
      }
      if (substr($tokens[2], 0, 1) == 'Y') {
          if ($alreadyOne && !$lastAlreadyInserted) {
              $days = '& ' . $days;
              $lastAlreadyInserted = true;
          }
          $days = 'Monday ' . $days;
          $alreadyOne = true;
      }

      $when .= $days;
      $when = substr($when, 0, -1);

    }

    if ($tokens[0] == 'monthly') {
      $when .= 'Monthly Event - ';

      if ($tokens[1] > 1) {
          $when .= 'Every ' . $tokens[1] . ' months, ';
      }

      if (strstr($tokens[2], '/')) {
          $tokens2 = explode("/", $tokens[2]);

          $when .= ucfirst($tokens2[0]);
          $when .= ' ';

          switch ($tokens2[1]) {
              case 'mon': 
                  $when .= 'Monday';
                  break;
              case 'tue': 
                  $when .= 'Tuesday';
                  break;
              case 'wed': 
                  $when .= 'Wednesday';
                  break;
              case 'thu': 
                  $when .= 'Thursday';
                  break;
              case 'fri': 
                  $when .= 'Friday';
                  break;
              case 'sat': 
                  $when .= 'Saturday';
                  break;
              case 'sun': 
                  $when .= 'Sunday';
                  break;
          }
          $when .= ' of every month';
      } else {
          $when .= 'Day ' . $tokens[2];
          $when .= ' of the month';
      }
    }

    if ($tokens[0] != 'custom') {
      $when .= ': ';
      $time = substr($event->start_date, 11, 5) . ' to ' . substr($event->end_date, 11, 5);
      $when .= $time;
    }
  } else {
    $when .= '';

    $start_date = new DateTime($event->start_date);
    $end_date = new DateTime($event->end_date);

    $date_format = get_option('date_format');
    $time_format = get_option('time_format');

    if ($start_date->format($date_format) == $end_date->format($date_format)) {
        $when .= $start_date->format($date_format) .' from '. $start_date->format($time_format) . ' to '. $end_date->format($time_format);
    } else {
        $when .= $start_date->format($date_format) .' at '. $start_date->format($time_format) . ' | ' . $end_date->format($date_format) .' at '. $end_date->format('g:i A');
    }
  }
  // END calculate the event 'When' string

  // format the address
  $address = "";
  if ($event->venue->city != '') {
      $address .= $event->venue->city;
  }
  if ($event->venue->country != '') {
      if ($address) {
          $address .= ", ".$event->venue->country;
      } else {
          $address .= $event->venue->country;
      }
  }

  // clean html of description
  $description = combrite_clean_html($event->description);

  // Replace placeholders in file with data
  $search = Array(
    "{EVENT_ID}",
    "{EVENT_LOGO}",
    "{EVENT_TITLE}",
    "{EVENT_URL}",
    "{EVENT_CATEGORY}",
    "{EVENT_START}",
    "{EVENT_END}",
    "{EVENT_WHEN}",
    "{EVENT_DESCRIPTION}",
    "{NUMBER_OF_TICKETS}",

    "{ORGANIZER_URL}",
    "{ORGANIZER_NAME}",
    "{ORGANIZER_ID}",

    "{VENUE_ID}",
    "{VENUE_NAME}",
    "{VENUE_URL}",
    "{VENUE_CITY}",
    "{VENUE_COUNTRY}",
    "{VENUE_ADDRESS}",
    "{VENUE_FORMATED_ADDRESS}",
    "{VENUE_LAT}",
    "{VENUE_LNG}"
  );
 
  $replace = Array (
    $event->id,
    @$event->logo,
    @str_replace(";", " ", $event->title), // we need to replace ";" so plugin can put it in map
    @$event->url,
    @ucwords(str_replace(",", ", ", $event->category)),
    @$start,
    @$end,
    @$when,
    @$description,
    @count($event->tickets),

    @$event->organizer->url,
    @$event->organizer->name,
    @$event->organizer->id,

    @$event->venue->id,
    @$event->venue->name,
    @$event->venue->url,
    @$event->venue->city,
    @$event->venue->country,
    @str_replace(";", " ", $event->venue->address), // we need to replace ";" so plugin can put it in map
    @$address,
    @$event->venue->latitude,
    @$event->venue->longitude,
  );

  $article_body = str_replace($search, $replace, $template);

  $post = array(
    'post_title'    => $event->title,
    'post_content'  => $article_body,
  );  

  return $post;
}

function combrite_create_post($event_id) {
  $post = _combrite_prepare_content($event_id);
  $page['post_type'] = 'post';
  return wp_insert_post($post);
}

function combrite_create_page($event_id) {
  $page = _combrite_prepare_content($event_id);
  $page['post_type'] = 'page';
  return wp_insert_post($page);
}