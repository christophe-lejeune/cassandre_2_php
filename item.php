<?php

  include 'http.php';

  header('content-type: application/json;charset=utf-8');
  $corpus = $_GET['corpus'];
  $item = $_GET['item'];
  $isItem = isset($_GET['item']);
  $key = ($isItem)? $item : $corpus;
  echo '{"rows":[', "\n";
  $first = true;
  $rows = get('corpus_pattern?key="'.$corpus.'"')->rows;
  foreach ($rows as $r) {
    $pattern = urlencode($r->value->text);
    $kwic = get('kwic?startkey=["'.$key.'","'.$pattern
      .'"]&endkey=["'.$key.'","'.$pattern.'\ufff0"]'
    )->rows;
    foreach ($kwic as $k) { 
      if ($first) {
        $first = false;
      } else {
        echo ",\n";
      }
      if (!$isItem) {
        $item = $k->id;
      }
      echo '{"key":["', $corpus, '","', $item, '","', $r->value->highlight, 
        '|', $k->value->match,
        '"], "value":{"coordinates":[', $k->value->begin, ',', $k->value->end, 
        '], "topic":{"viewpoint":"', $r->value->viewpoint, '", "id":"',
        $r->value->topic, '"}, "text":', json_encode($k->value->before.$k->key[1]),
        ($k->value->actor)?', "actor":'.json_encode($k->value->actor):'', 
        '}}';
    }
  }
  $rows = get('item?'.(
    ($isItem)
      ?'key=["'.$corpus.'","'.$item.'"]'
      :'startkey=["'.$corpus.'"]&endkey=["'.$corpus.'",{}]'
  ))->rows;
  foreach ($rows as $r) {
    if ($first) {
      $first = false;
    } else {
      echo ",\n";
    }
    echo(json_encode($r));
  }
  echo(']}');

?>
