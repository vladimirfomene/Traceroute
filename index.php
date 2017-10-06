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
        var domaintxtField = document.getElementById("txtDomain");
        var form = document.querySelector("form");
        console.log(domaintxtField.value);
        form.addEventListener("submit", function(event){
          var req = ajaxRequest();
          console.log(domaintxtField.value);
          req.open("GET", "traceroute.php?domain=" + domaintxtField.value, true);
          req.addEventListener("load", function() {
            if(req.status == 200 && req.readyState == 4){
              //Get the result as a js object
              var Ips = JSON.parse(req.responseText);

              //Add user's location to ipLocations
              getUserLocation();

              /*For each ip from server, append it as a text node and get its
              geolocation(lat, long) using the ipinfo.io api then print it to
              the console */
              for(var ip in Ips){
                var resultArea = document.querySelector(".gmap-result");
                getLocationData(Ips[ip]);
                var ipTxt = document.createTextNode(Ips[ip]);
                resultArea.appendChild(ipTxt);
              }

              for(var location in ipLocations){
                moveMarkerTo(location.lat, location.long);
              }

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
          var location = new Location(position.coords.latitude, position.coords.longitude);
          ipLocations.unshift(location);
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
          var iconBase = "img/"
          marker = new google.maps.Marker({
          	map: map,
          	position: new google.maps.LatLng(5.758921, -0.2209543),
          	icon: iconBase + "frontal-bus.png",
          });
        }

        //move packet from user's destination to server destination
        function moveMarkerTo(lat, long){
          setTimeOut(function(){
            marker.setPosition(new google.maps.LatLng(lat, long));
            map.panTo(new google.maps.LatLng(lat, long));
          }, 1500);
        }

        /**
        Get location of an ip and create a new Location object and add it to
        ipLocations array **/
        function getLocByIp(location){
          var LatLong = String(location.loc).split(",");
          var geoLocation = new Location(LatLong[0], LatLong[1]);
          ipLocations.push(geoLocation);
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

        //Constructor for position object
        function Location(lat, long){
          this.lat = lat;
          this.long = long;
        }

        //Add getter for long and lat properties of the Location object
        Object.defineProperty(Location.prototype, "lat", {
          get: function(){ return this.lat; }
        });

        Object.defineProperty(Location.prototype, "long", {
          get: function(){ return this.long; }
        });
    </script>
    <!-- Google maps javascript api -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDi5XLlJPvGIvOx3lia08f307ahecCQhXM&callback=initMap"
    async defer></script>
  </body>
</html>
