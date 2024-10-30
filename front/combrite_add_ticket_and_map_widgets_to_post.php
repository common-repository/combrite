<?php

function combrite_add_ticket_and_map_widgets_to_post($content)
{
  if (strpos($content, 'class="cb-container"') || strpos($content, 'class="cb-container"')) {
  	//Add CSS

    wp_enqueue_style('combrite-wp', plugins_url('css/front.css', __FILE__));

    $style = wp_remote_get(plugins_url() . '/../uploads/combrite/custom.css');
    if ($style['response']['code'] != '404') {
      wp_enqueue_style('combrite-wp-custom', plugins_url() . '/../uploads/combrite/custom.css', __FILE__);
    }

    // now we look for map
    $regex = '/{combrite_map\s+(.*?)}/i';
    preg_match_all($regex, $content, $matches, PREG_SET_ORDER);
    $content = _combrite_process_map($content, $matches);

    $regex = '/{combrite_tickets\s+(.*?)}/i';
    preg_match_all($regex, $content, $matches, PREG_SET_ORDER);
    $content = _combrite_process_ticket_widget($content, $matches);
  }

	return $content;
}

function _combrite_process_map($content, $matches) {
  if ($matches) {
    foreach ($matches as $match) {
      $matcheslist = explode(';', $match[1]);
      $lat = (int) trim($matcheslist[0]);
      $lng = (int) trim($matcheslist[1]);

      if ($lat != 0 && $lng != 0) {
        //the event has a location
        $output = '<div class="cb-map">';
        $output .= '<a class="cb-map__link" href="https://www.google.com/maps?q='.$lat.','.$lng.'" target="_blank"><div class="cb-map__marker" style="cursor: pointer"></div></a>';  
        $language = substr(get_bloginfo('language'), 0, 2);
        $languagesSupportedByGoogleMaps = array('ar', 'bg', 'bn', 'ca', 'cs', 'da', 'de', 'el', 'en', 'es', 'eu', 'fa', 'fi', 'fi', 'fr', 'gl', 'gu', 'hi', 'hr', 'hu', 'id', 'it', 'iw', 'ja', 'kn', 'ko', 'lt', 'lv', 'ml', 'mr', 'nl', 'nn', 'no', 'or', 'pl', 'pt', 'pt', 'rm', 'ro', 'ru', 'sk', 'sl', 'sr', 'sv', 'tl', 'ta', 'te', 'th', 'tr', 'uk', 'vi', 'zh');
        if (!in_array($language, $languagesSupportedByGoogleMaps)) {
          $language = 'en';
        }
        
        $output .= '<a href="https://www.google.com/maps?q='.$lat.','.$lng.'" target="_blank"><div class="cb-map__image" style="background-image: url(http://maps.googleapis.com/maps/api/staticmap?center='.$lat.','.$lng.'&zoom=16&size=640x180&sensor=false&scale=2&language='.$language.');"></div></a>';
        $output .= '</div>';
      }

      // we need this because sometimes title of event contain chars that are special to regex (for example () )
      $plugin_code = quotemeta($match[0]);
      $content = preg_replace("|($plugin_code)|", addcslashes($output, '\\$'), $content, 1);
    }
  }

  return $content;
}

function _combrite_process_ticket_widget($content, $matches) {
  if ($matches) {
    foreach ($matches as $match) {
      $p = explode(";", $match[1]);
      $eventID = $p[0];
      $no_of_tickets = $p[1];

      if (isset($p[2])) {
        $height = $p[2];
      } else {
        $height = 180 + $no_of_tickets * 45;
      }

      $output = '<div style="width:100%; text-align:left;" ><iframe src="http://www.eventbrite.com/tickets-external?eid='.$eventID.'&ref=etckt&v=2" frameborder="0" height="'.$height.'" width="100%" vspace="0" hspace="0" marginheight="5" marginwidth="5" scrolling="auto" allowtransparency="true"></iframe></div>';
      $content = preg_replace("|$match[0]|", addcslashes($output, '\\$'), $content, 1);
    }
  }

  return $content;
}