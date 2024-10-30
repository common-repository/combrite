
jQuery(document).ready(function() {
	/**
		* Events list content generation
		*/
	var total = 0;
	var page = 1;
	var events_count = 0;

	var loadEvents = function(keyword, page) {

		var data = {
			action: 'get_events',
			page: page,
			keyword: keyword
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			response = JSON.parse(response);

			jQuery('#loader-search').hide();
			jQuery('#loader-loadmore').hide();

			var items = response.items;
			total = response.total;

			var eventHtml = '';
			events_count += items.length;

			if (events_count === 0) {
				var paginationHtml = '<div class="total">No events found</div>';
				jQuery('#pagination-container').html(paginationHtml);
				return;
			}

			items.forEach(function(event) {
				eventHtml += '	<div class="accordion-group">';
				eventHtml += '		<div class="accordion-heading">';
				eventHtml += '			<a data-toggle="collapse" data-parent="#eventList" class="accordion-toggle">';
				eventHtml += '			  <i class="icon-arrow-down"></i>';
				eventHtml += '			  <span>' + event.title + '</span>';
				eventHtml += '			</a>';
				eventHtml += '			<span class="btn makeArticle">Import as <a data-id="' + event.id + '" class="import-as-wp-post" href="#">Post</a> | <a data-id="' + event.id + '" class="import-as-wp-page" href="#">Page</a></span>';
				eventHtml += '		</div>';
				eventHtml += '		<div id="' + event.id + '" class="collapse accordion-body">';
				eventHtml += '			<div class="event_details_container accordion-inner ">';
				eventHtml += '				<table class="table table-striped ">';
	  		eventHtml += '					 <tbody>';
				eventHtml += '				    <tr>';
				eventHtml += '				      <td class="label-descr">Organizer</td>';
				eventHtml += '				      <td>' + event.organizer.name + '</td>';
				eventHtml += '				    </tr>';
				eventHtml += '				  	<tr>';
	      eventHtml += '              <td class="label-descr">Date and time</td>';
				eventHtml += '				      <td>' + event.start_date + '</td>';
				eventHtml += '				    </tr>';
				eventHtml += '				    <tr>';
				eventHtml += '				      <td class="label-descr">Venue (address)</td>';
	      eventHtml += '              <td>';
	      eventHtml += '                <em>' + event.venue.name + '</em> - ' + event.venue.address + ', ' + event.venue.city + ', ' + event.venue.country + '';
	      eventHtml += '              </td>';
				eventHtml += '				    </tr>';
				// eventHtml += '				    <tr>';
				// eventHtml += '				      <td class="label-descr">Description</td>';
				// eventHtml += '				      <td>' + event.description + '<a href="' + event.url + '" target="_blank">View on Eventbrite</a></td>';
				// eventHtml += '				    </tr>';
	  		eventHtml += '				  </tbody>';
				eventHtml += '				</table>';
				eventHtml += '			</div>';
				eventHtml += '		</div>';
				eventHtml += '	</div>';

			});

			jQuery('#eventList').append(eventHtml);

			var paginationHtml = '';
			paginationHtml += '<div class="loadmore">';
			paginationHtml += '	';
			paginationHtml += '	<button type="submit" value="Submit" id="combrite-load-more-button">Load more <span class="loader" id="loader-loadmore" style="display: none"></span></button>';
			paginationHtml += '</div>';
			paginationHtml += '<div class="total">Showing ' + events_count + ' events. Total: ' + total + ' </div>';
			jQuery('#pagination-container').html(paginationHtml);
			if (events_count === total) {
				jQuery('#pagination-container').html('');
			}

			jQuery('#eventList').accordion({
				header: '.accordion-heading', 
				heightStyle: "content", 
				active: false, 
				collapsible: true,
				beforeActivate: function(event, ui) {
					if (event.srcElement && event.srcElement.nodeName === 'A') {
						event.preventDefault();
					}
				}
			});
    	jQuery('#eventList').accordion('refresh'); 
		});
	};

	jQuery(document).on('click', '#combrite-search-button', function(e) {
		e.preventDefault();
		
		page = 1;
		events_count = 0;
		var keyword = jQuery('#combrite-keywords-field').val();
		if (keyword.length > 0) {
			jQuery('#loader-search').show();
			loadEvents(keyword, page);
			jQuery('#eventList').html('');
			jQuery('#pagination-container').html('');
		} else {
			alert('Enter a keyword');
		}
	});

	jQuery(document).on('click', '#combrite-load-more-button', function(event) {
		event.preventDefault();
		jQuery('#loader-loadmore').show();
		page++;
		var keyword = jQuery('#combrite-keywords-field').val();
		loadEvents(keyword, page);
	});


	/**
		* Post/page content generation
		*/
	jQuery(document).on('click', '.import-as-wp-post', function() {
		var event_id = jQuery(this).data('id');

		var data = {
			action: 'create_post',
			event_id: event_id
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			console.log(JSON.parse(response));

			if (response > 0) {
				window.location = 'post.php?post=' + response + '&action=edit'	
			}
			
		});
	});

	jQuery(document).on('click', '.import-as-wp-page', function() {
		var event_id = jQuery(this).data('id');

		var data = {
			action: 'create_page',
			event_id: event_id
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			console.log(JSON.parse(response));

			if (response > 0) {
				window.location = 'post.php?post=' + response + '&action=edit'	
			}
			
		});
	});

});