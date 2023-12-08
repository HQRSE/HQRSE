<?

// Путь к файлу
$file = 'answer_databse_clear.txt'; 
// Читаем содержимое файла в массив
$lines = file($file);
$j = 0;
foreach ($lines as $line) {
  /*echo '<pre>';
	  print_r($line);
  echo '</pre>';*/
  $phrase = explode('#', $line);
  $x = $phrase[1];
  preg_match_all('/([a-zA-Zа-яА-Я]+)/u',$x,$ok);
  for ($i = 0; $i < count($ok[1]); $i++) {
    $result = strcasecmp('БОТЫ', $ok[1][$i]);
    if ($result == 0) {
      echo $line." - ".$j."<br>";
      unset($lines[$j]);
    }
  }
  $j++;
}
file_put_contents('answer_databse_clear.txt', implode("", $lines));

?>
