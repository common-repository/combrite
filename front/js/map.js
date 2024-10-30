jQuery(document).ready(function() {
  var myLatlng = new google.maps.LatLng(params.lat, params.lng);
  var mapOptions = {
    center: new google.maps.LatLng(params.lat, params.lng),
    zoom: 8,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };
  var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions)
  var marker = new google.maps.Marker({
    position: myLatlng,
    map: map,
    title: params.title
  });
  var infowindow = new google.maps.InfoWindow({
    content: '<strong>' + params.title + '</strong><br>' + params.address
  });
  google.maps.event.addListener(marker, "click", function() {
    infowindow.open(map,marker);
  });
});
