
function initMap() {

  var mapOptions = {

    center: new google.maps.LatLng(0, 0),
    zoom: 12,
  };

  var map = new google.maps.Map(document.getElementById('map'), mapOptions);
  var autocomplete = new google.maps.places.Autocomplete(document.getElementById('place-input'), {
    types: ['establishment'],
    componentRestrictions: {country: 'GR'}
  });


  var marker = new google.maps.Marker({
    map: map,
    draggable: true,
  });
  marker.setVisible(true);

  google.maps.event.addDomListener(autocomplete, 'place_changed', function () {
    var place = autocomplete.getPlace();

    var markerPosition = {
      lat: place.geometry.location.lat(),
      lng: place.geometry.location.lng()
    };

    if (!marker) {
      marker = new google.maps.Marker({
        position: markerPosition,
        map: map,
        title: place.name
      });
    } else {
      marker.setPosition(markerPosition);
    }
    map.setCenter(markerPosition);
  });


}


