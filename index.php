<!Doctype html>
<html>
  <head>
    <title>Traceroute-Online</title>

    <!-- Custom styleSheet -->
    <link href="css/style.css" rel="stylesheet" type="text/css">

  </head>
  <body>
    <div class="container">
      <div class="content">
        <div class="user-form">
          <div class="form-header">Traceroute Online</div>
          <form method="get" class="input-form">
            <input id="txtDomain" type="text" name="domain" placeholder="www.yourdomain.com"><br>
            <input type="submit" value="Submit" class="btn">
          </form>
        </div>
        <div id="map"></div>
      </div>
      <div class="footer"></div>
    </div>
    <script>


        /* This codes sends a request to the server to get the ip addresses of the route */
        var ipLocations = [];
        var iconBase = "img/";
        var domaintxtField = document.getElementById("txtDomain");
        var form = document.querySelector("form");
        console.log(domaintxtField.value);
        form.addEventListener("submit", function(event){
          var req = ajaxRequest();
          var userForm = document.getElementsByClassName("user-form");
          console.log(domaintxtField.value);
          req.open("GET", "traceroute.php?domain=" + domaintxtField.value, true);
          req.addEventListener("load", function() {
            if(req.status == 200 && req.readyState == 4){
              //Get the result as a js object
              var Ips = JSON.parse(req.responseText);
              console.log(Ips);

              //Add user's location to ipLocations
              //getUserLocation();

              /*For each ip from server, append it as a text node and get its
              geolocation(lat, long) using the ipinfo.io api then print it to
              the console */
              for(var ip in Ips){
                getLocationData(Ips[ip]);
              }

              //userForm.style.visibility = "hidden";
              var locations = filterLocations(ipLocations);
              locations.shift();
              locations.shift();

              // Define the symbol, using one of the predefined paths ('CIRCLE')
              // supplied by the Google Maps JavaScript API.
              var lineSymbol = {
                path: google.maps.SymbolPath.CIRCLE,
                scale: 8,
                strokeColor: '#393'
              };

              // Create the polyline and add the symbol to it via the 'icons' property.
              var line = new google.maps.Polyline({
                path: locations,
                icons: [{
                  icon: lineSymbol,
                  offset: '100%'
                }],
                map: map
              });

              animatePacket(line);


            }
          });
          req.send(null);
          event.preventDefault();
        });

        function getLocationData(ip){
          var s = document.createElement("script");
          s.src = "http://ipinfo.io/" + ip + "/geo/" + "?callback=getLocByIp";
          document.body.appendChild(s);

        }

        function getUserLocation() {
          if (navigator.geolocation) {
              navigator.geolocation.getCurrentPosition(addToIpLocations);
          } else {
              alert("Geolocation is not supported by this browser.");
          }
        }

        function addToIpLocations(position){
          //var location = new Location(position.coords.latitude, position.coords.longitude);
          //ipLocations.unshift(location);
          ipLocations.push({lat: Number(position.coords.latitude), lng: Number(position.coords.latitude)});
        }


        /**Get location of an ip and create a new Location object and add it to
        ipLocations array **/
        function getLocByIp(location){
            var LatLong = String(location.loc).split(",");
            ipLocations.push({lat: Number(LatLong[0]), lng: Number(LatLong[1])});
        }


        function filterLocations(locations){
          var filteredLocations = [];
          locations.forEach(function(location){
            if(!isNaN(location.lat) && !isNaN(location.lng)){
              filteredLocations.push(location);
            }

          });

          return filteredLocations;
        }

        // Use the DOM setInterval() function to change the offset of the symbol
      // at fixed intervals.
        function animatePacket(line) {
            var count = 0;
            window.setInterval(function() {
              count = (count + 1) % 200;

              var icons = line.get('icons');
              icons[0].offset = (count / 2) + '%';
              line.set('icons', icons);
          }, 100);
        }

        //Google map initialization
        var map;
        var marker;
        function initMap(){
          var mapOptions = {
            center: new google.maps.LatLng(5.758921,-0.2209543),
            zoom: 12,
            mapTypeId: google.maps.MapTypeId.ROADMAP
          };

          map = new google.maps.Map(document.getElementById('map'), mapOptions);

        }

        /* Creates a XMLHttpRequest request object for recent and old browsers */
        function ajaxRequest(){
          try // Non IE Browser?
          {
            // Yes
            var request = new XMLHttpRequest()
          }
          catch(e1)
          {
            try // IE 6+?
            {
              // Yes
              request = new ActiveXObject("Msxml2.XMLHTTP")
            }
            catch(e2)
            {
              try // IE 5?
              {
                // Yes
                request = new ActiveXObject("Microsoft.XMLHTTP")
              }
              catch(e3) // There is no AJAX Support
              {
                request = false
              }
            }
          }
          return request
        }

    </script>
    <!-- Google maps javascript api -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDi5XLlJPvGIvOx3lia08f307ahecCQhXM&callback=initMap"
    async defer></script>
  </body>
</html>
