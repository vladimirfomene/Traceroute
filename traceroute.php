<?php



    print_r();
    if($_SERVER["REQUEST_METHOD"] == "GET"){
      echo json_encode(getHopsIp($_GET['domain']));
    }

    /**
    * This function takes in a domain an runs the traceroute command
    * on it. Parses the result with regex and returns the ips of the hops
    * in an associative array.
    **/
    function getHopsIp($domain){
      $routes = shell_exec("traceroute " . $domain);
      preg_match_all('/[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}/',
      $routes, $matches, PREG_PATTERN_ORDER);
      $match = $matches[0];
      $iP = [];
      for($i = 0; $i < count($match); $i++){
        $iP[$match[$i]] = $match[$i];
      }
      return $iP;
    }
?>
