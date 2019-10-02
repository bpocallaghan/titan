function initGoogleMap(selector, latitude, longitude, zoom_level)
{
    var mapCoords = new google.maps.LatLng(latitude, longitude);

    var mapOptions = {
        zoom: zoom_level,
        center: mapCoords,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
    };

    return new google.maps.Map(document.getElementById(selector), mapOptions);
}

function bindInfoWindow(marker, map, title, address, phone, email) {
    var html= '';

    if(title != '' || address != '' || phone != '' || email != ''){
        html +='<div class="map_pop">';

        if(title != ''){
            html += "<h3 class='head'>"+title+"</h3>";
        }
        if(address != ''){
            html += "<p><b>Address:</b> "+address+"</p>";
        }
        if(phone != ''){
            html += "<p><b>Phone:</b> "+phone+"</p>";
        }
        if(email != ''){
            html += "<p><b>Email:</b> "+email+"</p>";
        }

        html += "</div>";
    }

    if(html != ''){
        google.maps.event.addListener(marker, 'click', function() {

            iw = new google.maps.InfoWindow({content:html});
            iw.open(map,marker);
        });

        google.maps.event.addListener(map, 'click', function() {
            iw.close();
        });
    }

}

function addGoogleMapsMarker(map, latitude, longitude, icon, title, address, phone, email)
{
    var marker = new google.maps.Marker({
        map: map,
        icon: '/images/pins/' + icon + '.png',
        animation: google.maps.Animation.DROP,
        position: new google.maps.LatLng(latitude, longitude)
    });

    bindInfoWindow(marker, map, title, address, phone, email);

    return marker;
}