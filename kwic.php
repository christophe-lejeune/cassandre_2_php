<?php
  include 'http.php';
  $corpus = $_GET['corpus'];
  $start = $_GET['pole'];
  $end = $start.'\ufff0';
  $location = substr(BASE_URL,0,-5).'list/kwic/kwic?startkey=["'.$corpus.'","'.$start.'"]&endkey=["'.$corpus.'","'.$end.'"]';
  $contents = file_get_contents($location);
  echo $contents; 
?>
