<?php

  include 'http.php';

  header('content-type: application/json;charset=utf-8');
  $viewpoint = $_GET['viewpoint'];
  $topic = $_GET['topic'];
  echo '{"rows":[', "\n";
  $first = true;
  $query = ($topic)
    ? 'key=["'.$viewpoint.'","'.$topic.'"]'
    : 'startkey=["'.$viewpoint.'"]&endkey=["'.$viewpoint.'",{}]';
  $rows = get('topic_pattern?'.$query)->rows;
  foreach ($rows as $r) {
    $corpus = $r->value->corpus;
    $topic = $r->key[1];
    $pattern = urlencode($r->value->text);
    $kwic = get('kwic?startkey=["'.$corpus.'","'.$pattern
      .'"]&endkey=["'.$corpus.'","'.$pattern.'\ufff0"]'
    )->rows;
    foreach ($kwic as $k) { 
      if ($first) {
        $first = false;
      } else {
        echo ",\n";
      }
      $item = $k->id;
      echo '{"key":["', $viewpoint, '","', $topic, 
        '"], "value":{"highlight":{"id":"', $r->value->highlight, 
        '|', $k->value->match,
        '", "corpus":"', $corpus, '", "item":"', $item,
        '", "coordinates":[', $k->value->begin, ',', $k->value->end, 
        '], "text":', json_encode($k->value->context),
        '}}}';
    }
  }
  echo(']}');

?>
