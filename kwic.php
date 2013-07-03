<?php
  $remote_headers = '';
  $contents = '';
  $headers = apache_request_headers();
  foreach ($headers as $header => $value) {
    $remote_headers .= "$header: $value\r\n";
  }
  $opts = array(
    'http'=>array(
      'method'=>"GET",
      'header'=>"$remote_headers"
    )
  );
  $context = stream_context_create($opts);
  include 'http.php';
  $corpus = $_GET['corpus'];
  $start = rawurlencode($_GET['pole']);
  $end = $start.'\ufff0';
  $location = substr(BASE_URL,0,-5).'list/kwic/kwic?startkey=["'.$corpus.'","'.$start.'"]&endkey=["'.$corpus.'","'.$end.'"]';
  $contents = file_get_contents($location, false, $context);
  echo $contents; 
?>

