<?php

  define('BASE_URL', 'http://127.0.0.1:5984/cassandre/_design/cassandre/_view/');

  function get($url) {
    $headers = array('Accept: application/json');
    $ch = curl_init(BASE_URL.$url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30 );
    $response = curl_exec( $ch );
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if(substr($status_code, 0, 1) != "2")
      throw new Exception(BASE_URL.$url, $status_code);
    return (strlen($response) > 0) ? json_decode($response) : true;
  }

?>
