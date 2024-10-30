<?php 
function combrite_generate_the_admin_interface() {
	wp_enqueue_style('combrite-global', plugins_url('css/style.css', __FILE__));
	wp_enqueue_style('combrite-wp', plugins_url('css/wp.css', __FILE__));
	wp_enqueue_script('combrite-script', plugins_url('js/script.js', __FILE__));
	wp_enqueue_script('jquery-ui-accordion');
	?>

	<div id="nsCombrite" class="container roundedBox">
  	<a class="poweredByEventbrite" href="http://www.eventbrite.com/r/combrite" target="_blank"></a>
    <div class="links">
      <a href="http://wordpress.org/plugins/combrite/faq/" target="_blank">FAQ</a> |
      <a href="http://wordpress.org/support/plugin/combrite" target="_blank">Support</a>
    </div>
    <div class="container">

      <div class="combrite header-unit">
      	
        <div class="bg-trans">

          <div class="header-logo">
          </div>
          <h1><span>Search</span> for an eventbrite event to publish</h1>

          <form method="post">
            <div class="pad">
              <div class="searchForm">
                <a id="search">
                  <input type="text" name="filter_keywords" id="combrite-keywords-field" value="" site="50" placeholder="Keywords (comma separated)" tabindex="1">
                </a>
                <div class="loader" id="loader-search"></div>
                <button type="submit" value="Submit" id="combrite-search-button">Search</button>
              </div>
            </div>
          </form>
        </div>

        <div id="events-container">
        	<div class="accordion" id="eventList"></div>
        </div>
        <div id="pagination-container"></div>
      </div>
    </div>
  </div>

  <?php
}