
console.log("Hello there.");
/* This codes sends a request to the server to get the ip addresses of the route */
var domaintxtField = document.getElementById("txtDomain");
var form = document.querySelector("form");
console.log(domaintxtField.value);
form.addEventListener("submit", function(event){
  var req = ajaxRequest();
  console.log(domaintxtField.value);
  req.open("GET", "traceroute.php?domain=" + domaintxtField.value, true);
  req.addEventListener("load", function() {
    if(req.status == 200 && req.readyState == 4){
      var Ips = JSON.parse(req.responseText);
      console.log(Ips);
      Ips.forEach(function(ip){
        var resultArea = document.querySelector(".gmap-result");
        var ipTxt = document.createTextNode(ip);
        resultArea.appendChild(ipTxt);
      });
    }
  });
  req.send(null);
  event.preventDefault();
});

/* Creates a XMLHttpRequest request object for recent and old browsers */
function ajaxRequest()
{
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
