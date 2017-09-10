<!Doctype html>
<html>
  <head>
    <title>Traceroute-Online</title>
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <style>
      html, body {
       height: 100%;
       margin: 0;
       padding: 0;
      }
    </style>
  </head>
  <body>

        <div id="map" style="height: 100%;"></div>
    <script>

    function initMap(){
      var mapOptions = {
        center: new google.maps.LatLng(5.758921,-0.2209543),
        zoom: 12,
        mapTypeId: google.maps.MapTypeId.ROADMAP
      };

      new google.maps.Map(document.getElementById('map'), mapOptions);
    }

    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDi5XLlJPvGIvOx3lia08f307ahecCQhXM&callback=initMap"
    async defer></script>
  </body>
</html>
