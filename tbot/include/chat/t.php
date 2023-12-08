<?
/* http://webmasters.ru/forum/f24/generaciya-tekstov-dlya-dorveev-na-php-26172/ */
$servername = "localhost";
$username = "u1182208_hqrse";
$password = "]xSK7v1!p5";
$dbname = "u1182208_bot";
$link = mysqli_connect($servername, $username, $password, $dbname);
mysqli_set_charset($link, "utf8");
$f = fopen(__DIR__ ."/books/book3.txt", "r");
while(!feof($f)) {
    $phrase = fgets($f);
    $insert_link = "INSERT INTO book4 VALUES (NULL, '".$phrase."')";
    $result_tasks = mysqli_query($link, $insert_link);
}
fclose($f);

function gen_text($zapros)
{
    $servername = "localhost";
    $username = "u1182208_hqrse";
    $password = "]xSK7v1!p5";
    $dbname = "u1182208_bot";
    $link = mysqli_connect($servername, $username, $password, $dbname);
    mysqli_set_charset($link, "utf8");
    /******* НАСТРОЙКИ *******/
    //количество предложений в тексте, от
    $predl_from = 1;
    //количество предложений в тексте, до
    $predl_to = 1;
    //количество фраз в выражении, от
    $phrases_from = 1;
    //количество фраз в выражении, до
    $phrases_to = 1;
    //количество букв в слове из запроса, начиная с которого слово не считается шлаком
    $ne_shlak = 4;
    //вероятность того, что во фразу будет добавлено слово из запроса (включая общие слова вроде "скачать" и "бесплатно")
    $general_key_chance = 100;
    //вероятность того, что будет добавлено конкретное слово из запроса, без "скачать" и "бесплатно"
    $concrete_key_chance = 100;
    /*************************/
    
    $query = "SELECT ID FROM book2 ORDER BY id DESC LIMIT 1";
    $res = mysqli_query($link, $query) or die(mysql_error($link));
    $row = mysqli_fetch_array($res);
    $max_id = $row["ID"]; //общее количество выражений
    $keys = explode(" ", $zapros); //получаем массив слов из запроса
    $keys1 = array(); //сюда будем добавлять слова из запроса, отсеянные от мелких союзов, междометий и прочего шлака, а также от слов скачать, бесплатно и прочего в этом роде
    $keys2 = array(); //сюда будем добавлять общие слова из запроса ("скачать бесплатно")
    foreach ($keys as $word)
    {
        if (strlen($word) >= $ne_shlak)
        {
            if
            (
            (substr_count($word, 'бесплат') > 0)
            || (substr_count($word, 'безплатн') > 0)
            || (substr_count($word, 'качат') > 0)
            )
            {
                array_push($keys2, $word); //заполняем массив общих слов (скачать бесплатно)
            }
            else
            {
                array_push($keys1, $word); //заполняем массив конкретных слов
            }
        }
    }
    $full_text = ''; //сюда будем записывать генерируемый текст
    $pr = rand($predl_from, $predl_to); //количество предложений в генерируемом тексте
    for($i = 1; $i <= $pr; $i++)
    {
        $sl = rand($phrases_from, $phrases_to); //количество выражений в тексте
        for($j = 1; $j < $sl; $j++)
        {        
            $query = "SELECT phrase FROM book2 WHERE id = '".rand(1, $max_id)."'"; //выбираем случайную фразу
            $res = mysqli_query($link, $query) or die(mysqli_error($link));
            $row = mysqli_fetch_array($res);
            if ($j == 1) // если начало предложения...
            {
                $full_text .= ucfirst($row["phrase"]).", "; //то начинаем его с большой буквы
            }
            else
            {                
                $key_chance = rand(1,100);
                if ($key_chance <= $concrete_key_chance) //добавляем "конкретное" слово из запроса
                {
                    $full_text .= $row["phrase"]." ".$keys1[rand(0, count($keys1)-1)].", ";
                }
                elseif ($key_chance <= $general_key_chance) //добавляем "общее" слово из запроса
                {
                    $full_text .= $row["phrase"]." ".$keys2[rand(0, count($keys2)-1)].", ";
                }
                else
                {
                    $full_text .= $row["phrase"].", ";
                }
            }    
        }
        $query = "SELECT phrase FROM book2 WHERE id = '".rand(1,$max_id)."'";
        $res = mysqli_query($link, $query) or die(mysqli_error($link));
        $row = mysqli_fetch_array($res);
        $full_text .= $row["phrase"].". "; //оформляем конец предложения
    }
    return $full_text;
}

echo gen_text("Саша Спрей Выебал"); 
?>
