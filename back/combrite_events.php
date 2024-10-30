<?php

function combrite_get_items($keyword, $page = 1) {

  if ($keyword == "") {
    return Array();
  } else {
    $filter = Array();

    // cleaning spaces from keyword for passing keyword trought URL
    $keyword = str_replace(' ', '%20', $keyword);
    // cleaning minus "-" sign from search
    $keyword = str_replace('-', '', $keyword);
    // adding minus if magic word "LNOT" is used 
    $keyword = str_replace('LNOT', '-', $keyword);

    // striping "!" from the end of keyword search since this cause error (??)
    $keyword = rtrim($keyword, "!");
    $filter[] = "keywords=".$keyword;
    if ($page) {
      $filter[] = "page=".$page;
    }

    $filterString = implode("&", $filter);
    $eventBriteURL = "http://www.eventbrite.com/json/event_search?app_key=EHHWMU473LTVEO4JFY&$filterString";
    $events = json_decode(file_get_contents($eventBriteURL));
    $items = Array();
    $totalEventsFound = $events->events[0]->summary->total_items ? $events->events[0]->summary->total_items: 0;

    foreach ($events->events as $index => $obj) {
      if ($index > 0) {
        // some events doesn't have venue info at all
        // we must insert dummy data to be sure that we don't get errors trying to access object
        // that doesn't exist
        if (!isset($obj->event->venue)) {
          $empty_venue = '{
                            "name": "",
                            "city": "",
                            "country": "",
                            "region" : "",
                            "longitude": 0.0,
                            "postal_code": "",
                            "address_2": "",
                            "address": "",
                            "latitude": 0.0,
                            "country_code": "",
                            "id": 0,
                            "Lat-Long": "",
                            "url": ""
                          }';
          $obj->event->venue = json_decode($empty_venue);
        }
        $items[] = $obj->event;
      }
    }

    $return = array('items' => $items, 'total' => $totalEventsFound);

    return $return; 
  }
}

function combrite_get_item($id) {

  $eventBriteURL = "http://www.eventbrite.com/json/event_get?app_key=EHHWMU473LTVEO4JFY&id=$id";
  $event = json_decode(file_get_contents ($eventBriteURL));
  // some events doesn't have venue info at all
  // we must insert dummy data to be sure that we don't get errors trying to access object
  // that doesn't exist
  if (!isset($event->event->venue)) {
    $empty_venue = '{
                      "name": "",
                      "city": "",
                      "country": "",
                      "region" : "",
                      "longitude": 0.0,
                      "postal_code": "",
                      "address_2": "",
                      "address": "",
                      "latitude": 0.0,
                      "country_code": "",
                      "id": 0,
                      "Lat-Long": "",
                      "url": ""
                    }';
    $event->event->venue = json_decode($empty_venue);
  }
  return $event->event;
}
