<?
$data = file_get_contents('php://input');
$data = json_decode($data, true);
if ($data) {
    $servername = "localhost";
    $username = "u1182208_hqrse";
    $password = "]xSK7v1!p5";
    $dbname = "u1182208_bot";
    $bot_token = '5267388785:AAHjsBkueZ4KMEMA1_A93UbSxp60855-cg8';
    if (!empty($data['message']) || !empty($data['callback_query'])) {
        $chat_id = $data['message']['from']['id'] ?? $data['callback_query']['from']['id'];
        $user_name = $data['message']['from']['username'] ?? $data['callback_query']['from']['username'];
        $first_name = $data['message']['from']['first_name'] ?? $data['callback_query']['from']['first_name'];
        $last_name = $data['message']['from']['last_name'] ?? $data['callback_query']['from']['last_name'];
        if($data['callback_query']['data']) {
            $text = trim($data['callback_query']['data']);
        } elseif (trim($data['message']['text'])) {
            $text = trim($data['message']['text']);
        } elseif (trim($data['message']['caption'])) {
            $text = trim($data['message']['caption']);
        }
    }
    if ($text === '/start' || $text === 'Привет') {
        $welcome_1 = 'Добро пожаловать!
Меня зовут HQRSE BQT 🏆';
        message_to_telegram($bot_token, $chat_id, $welcome_1);
        $welcome_2 = 'Вот с чего стоит начать работу 💊';
        $r = json_encode(
            array(
                'inline_keyboard' => array(
                    array(
                        array(
                            'text' => '❓ Помощь',
                            'callback_data' => '/help',
                        ),
                        array(
                            'text' => '🎯 Задачи',
                            'callback_data' => '/tasks',
                        ),        
                    ),
                ),
            )
        );
        message_to_telegram($bot_token, $chat_id, $welcome_2, $r);
    } elseif ($text === '/help' || $text === 'Помощь') {
        $default = array(
            array(
                array(
                    'text' => '🏆 Главная',
                    'callback_data' => '/start',
                    ),                    
                array(
                    'text' => '❔ Помощь',
                    'callback_data' => '/help',
                ),                    
            ),
            array(
                array(
                    'text' => '🎯 Задачи',
                    'callback_data' => '/tasks',
                ),
                array(
                    'text' => '✅ Срочные',
                    'callback_data' => '/urgent',
                ),       
            )
        );
        $hqrse_help = array(
            array(
                array(
                    'text' => '✉ Поговорим',
                    'callback_data' => '/chat',
                ),
                array(
                    'text' => '💾 Сохранить',
                    'callback_data' => '/addlink',
                ),                     
            ),
            array(
                array(
                    'text' => '🌐 Все ссылки',
                    'callback_data' => '/alllink',
                ),
                array(
                    'text' => '📂 Все теги',
                    'callback_data' => '/alltag',
                ),     
            ),
        );        
        $help = 'Вот что я умею:';
        if ($chat_id == 405583257) {
            $de = array_merge($default, $hqrse_help);
            $de['inline_keyboard'] = $de;
        } else {
            $de = $default;
        }
        $r = json_encode($de);
        message_to_telegram($bot_token, $chat_id, $help, $r);
    } elseif ($text === '/tasks' || $text === 'Задачи') {
        require_once("include/tasks.php");
        message_to_telegram($bot_token, $chat_id, $mess);
    } elseif ($text === '/urgent') {
        require_once("include/urgent.php");
        message_to_telegram($bot_token, $chat_id, $mess);
    } elseif ($text === '/chat' || $text === 'Чат' || $text === 'Поговорим?') {
        set_bot_state ($chat_id, '/chat');
        $go = '{Го|Го!|Давай)|Погнали)|Начинай|Слушаю...|И кто ты?|С чего начнем?|Давно не виделись)|Привет|Привет!|Прививки)|Лец го|Камон|Поехали}';
        $go = preg_replace_callback('~{(?>[^{}]+|(?0))*}~', 'do_replace', $go);
        message_to_telegram($bot_token, $chat_id, $go);
    } elseif ($text === '/addlink' || $text === '+ ссылка') {
        set_bot_state ($chat_id, '/addlink');
        $message = 'Формат: ссылка|тег';        
        message_to_telegram($bot_token, $chat_id, $message);
        $text = '';
    } elseif ($text === '/alltag' || $text === 'Тег') {
        $link = mysqli_connect($servername, $username, $password, $dbname);
        mysqli_set_charset($link, "utf8");
        $tagQuery = "SELECT tag FROM links";
        $res_tags = mysqli_query($link, $tagQuery);
        $message = '';
        $alltag = [];
        while($tags = mysqli_fetch_array($res_tags)) {
            if (!in_array($tags['tag'], $alltag)) {
                $alltag[] = $tags['tag'];
                $message .= $tags['tag'].', ';
            }
        }
        $message = substr($message, 0, -2);
        message_to_telegram($bot_token, $chat_id, $message);
    } elseif ($text === '/golink' || $text === 'Ссылка' || $text === 'Rand') {
        set_bot_state ($chat_id, '/golink');
        $message = 'Какой тег интересует?';        
        message_to_telegram($bot_token, $chat_id, $message);
        $text = '';
    } elseif ($text === '/alllink' || $text === 'Ссылки') {
        set_bot_state ($chat_id, '/alllink');
        $message = 'Какой тег интересует?';        
        message_to_telegram($bot_token, $chat_id, $message);
        $text = '';
    }
    /* *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** */
    $bot_state = get_bot_state ($chat_id);
    $w8 = '{Хм...|Дай-ка подумать|Так...|Интересно)|Эм...|Ну...|Ща|Нужно время|Как сказать|Это не просто|Сек|Момент|Как бы тебе ответить)}';
    if (substr($bot_state, 0, 5) == '/chat') {
        if ($text === 'Stop' || $text === 'Стоп') {
            $stop = '{Окей|Пф...|Вот и все...|Ёбай)|Пока|До встречи!|Ладно...|Я офф|Гудбай|Бб|Ну и вали|Иди-иди|Напиши потом|Буду ждать тебя}';
            $stop = preg_replace_callback('~{(?>[^{}]+|(?0))*}~', 'do_replace', $stop);
            set_bot_state ($chat_id, '');        
            message_to_telegram($bot_token, $chat_id, $stop);
        } elseif ($text !== 'Чат') {
            $f = fopen(__DIR__ ."/include/chat/answer_databse_variable.txt", "r");
            $arr = [];
            while(!feof($f)) {
                $phrase = explode("#", fgets($f));
                $pos = stripos($text, $phrase[0]);
                if ($phrase[0] === trim($text)/* || $pos !== false*/) {
                    $arr[] = $phrase[1];
                }
            }
            fclose($f);
            if ($arr/* && 1 < 0*/) {
                $k = array_rand($arr);
                $answer_data = $arr[$k];
                $answer = preg_replace_callback('~{(?>[^{}]+|(?0))*}~', 'do_replace', $answer_data);
            } else {                
                $wai = preg_replace_callback('~{(?>[^{}]+|(?0))*}~', 'do_replace', $w8);
                message_to_telegram($bot_token, $chat_id, $wai);
                $z1 = seokeywords($text);
                $f = fopen(__DIR__ ."/include/chat/answer_databse_variable.txt", "r");
                $arr = [];
                while(!feof($f)) {
                    $phrase = explode("#", fgets($f));
                    $z2 = seokeywords($phrase[1]);
                    $v = similar_text($z1, $z2, $tmp);
                    if ($tmp > 70) {
                        $arr[] = $phrase[1];
                    }
                }
                fclose($f);
                if ($arr) {
                    $k = array_rand($arr);
                    $answer_data = $arr[$k];
                    $answer = preg_replace_callback('~{(?>[^{}]+|(?0))*}~', 'do_replace', $answer_data);
                } else {
                    $link = mysqli_connect($servername, $username, $password, $dbname);
                    mysqli_set_charset($link, "utf8");
                    $getBookSQL = "SELECT TABLE_NAME FROM information_schema.tables WHERE TABLE_NAME LIKE 'book%'";
                    $res_books = mysqli_query($link, $getBookSQL);
                    $arr = [];
                    while ($row_book = mysqli_fetch_array($res_books)) {
                        $book = $row_book['TABLE_NAME'];
                        $bookSQL = "SELECT ID,phrase FROM ".$book;
                        $res_book = mysqli_query($link, $bookSQL);
                        if ($row_link = mysqli_fetch_array($res_book)) {
                            /*$searched = preg_replace_callback('~{(?>[^{}]+|(?0))*}~', 'do_replace', $w8);*/
                            $searched = 'Ищу в книге '.$book;
                            message_to_telegram($bot_token, $chat_id, $searched);                            
                            while ($row_link = mysqli_fetch_array($res_book)) {
                                if (strlen($row_link['phrase']) < 4) {
                                    $ph = $row_link['phrase'];
                                    $del = 'Удалил содержание "'.$ph.'"';
                                    message_to_telegram($bot_token, $chat_id, $del);
                                    $delPhraseSQL = "DELETE FROM ".$book." WHERE ID = ".$row_link['ID'];
                                    $res_books = mysqli_query($link, $delPhraseSQL);
                                } else {
                                    $z2 = seokeywords($row_link['phrase']);
                                    $v = similar_text($z1, $z2, $tmp);
                                    if ($tmp > 70) {
                                        $arr[] = $row_link['phrase'];
                                    }
                                }
                            }
                        }                         
                    }
                    if ($arr) {
                        $k = array_rand($arr);
                        $answer = $arr[$k];
                    } else {
                        $answer = 'Нет ответа';
                    }
                }
                $link = mysqli_connect($servername, $username, $password, $dbname);
                mysqli_set_charset($link, "utf8");
                $insert_link = "INSERT INTO dialogs VALUES (NULL, '".$user_name."', '".$chat_id."', '".$text."', '".$answer."', '".$z1."')";
                $result_tasks = mysqli_query($link, $insert_link);
            }
        }
        message_to_telegram($bot_token, $chat_id, $answer);
    } elseif (substr($bot_state, 0, 8) == '/addlink') {
        if (strlen($text) > 5) {
            $linker = explode('|', $text);
            $address = trim($linker[0]);
            $tag = trim($linker[1]);
            if(filter_var($address, FILTER_VALIDATE_URL)) {
                $link = mysqli_connect($servername, $username, $password, $dbname);
                mysqli_set_charset($link, "utf8");
                $insert_link = "INSERT INTO links VALUES (NULL, '".$address."', '".$tag."')";
                $result_tasks = mysqli_query($link, $insert_link);
                $message = 'Коллекция пополнена';
            } else {
                $message = 'Это не ссылка. Повторить /addlink';
            }
            set_bot_state ($chat_id, '');
            message_to_telegram($bot_token, $chat_id, $message);            
        }
    } elseif (substr($bot_state, 0, 7) == '/golink') {
        if (strlen($text) > 3) {
            $tag = trim($text);
            $link = mysqli_connect($servername, $username, $password, $dbname);
            mysqli_set_charset($link, "utf8");
            $linkQuery = "SELECT link FROM links WHERE tag = '".$tag."'";
            $res_link = mysqli_query($link, $linkQuery);
            $arr = [];
            while ($row_link = mysqli_fetch_array($res_link)) {
                $arr[] = $row_link['link'];
            }
            if ($arr) {
                $k = array_rand($arr);
                $message = $arr[$k];
            } else {
                $message = 'Не найдено. Посмотрите /alltag';
            }
            message_to_telegram($bot_token, $chat_id, $message);
            set_bot_state ($chat_id, '');
        }
    } elseif (substr($bot_state, 0, 8) == '/alllink') {
        if (strlen($text) > 3) {
            $tag = trim($text);
            $link = mysqli_connect($servername, $username, $password, $dbname);
            mysqli_set_charset($link, "utf8");
            $linkQuery = "SELECT link FROM links WHERE tag = '".$tag."'";
            $res_alllink = mysqli_query($link, $linkQuery);
            $arr = [];
            while ($row_alllink = mysqli_fetch_array($res_alllink)) {
                $arr[] = $row_alllink['link'];
                message_to_telegram($bot_token, $chat_id, $row_alllink['link']);
            }
            if (!$arr) {
                $message = 'Не найдено. Посмотрите /alltag';
                message_to_telegram($bot_token, $chat_id, $message);
            }
            set_bot_state ($chat_id, '');
        }
    }
/* *** */
} else {
    echo '<a href="https://hqrse.ru/">HQRSE BQT</a>';
}
/* *************************************************************************** */
function message_to_telegram($bot_token, $chat_id, $text, $reply_markup = '') {
    $ch = curl_init();
    if ($reply_markup == '') {
        if ($chat_id == 405583257) {
            $btn[] = ["text"=>"Чат", "callback_data"=>'/chat'];
            $btn[] = ["text"=>"Add", "callback_data"=>'/addlink'];
            $btn[] = ["text"=>"Rand", "callback_data"=>'/golink'];
            $btn[] = ["text"=>"Тег", "callback_data"=>'/alltag'];
            $reply_markup = json_encode(["keyboard" => [$btn],  "resize_keyboard" => true]);
        }
    }
    $ch_post = [
        CURLOPT_URL => 'https://api.telegram.org/bot' . $bot_token . '/sendMessage',
        CURLOPT_POST => TRUE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_POSTFIELDS => [
            'chat_id' => $chat_id,
            'parse_mode' => 'HTML',
            'text' => $text,
            'reply_markup' => $reply_markup,
        ]
    ];
    curl_setopt_array($ch, $ch_post);
    curl_exec($ch);
}
function set_bot_state ($chat_id, $data) {
    file_put_contents(__DIR__ . '/include/chat/users/'.$chat_id.'.txt', $data);
}
function get_bot_state ($chat_id) {
    if (file_exists(__DIR__ . '/include/chat/users/'.$chat_id.'.txt')) {
        $data = file_get_contents(__DIR__ . '/include/chat/users/'.$chat_id.'.txt');
        return $data;
    } else {
        return '';
    }
}
function seokeywords($contents,$symbol=5,$words=5){
	$contents = @preg_replace(array("'<[\/\!]*?[^<>]*?>'si","'([\r\n])[\s]+'si","'&[a-z0-9]{1,6};'si","'( +)'si"),
	array("","\\1 "," "," "),strip_tags($contents));
	$rearray = array("~","!","@","#","$","%","^","&","*","(",")","_","+",
		                 "`",'"',"№",";",":","?","-","=","|","\"","\\","/",
		                 "[","]","{","}","'",",",".","<",">","\r\n","\n","\t","«","»");
	$adjectivearray = array("ые","ое","ие","ий","ая","ый","ой","ми","ых","ее","ую","их","ым",
		                        "как","для","что","или","это","этих",
		                        "всех","вас","они","оно","еще","когда",
		                        "где","эта","лишь","уже","вам","нет",
		                        "если","надо","все","так","его","чем",
		                        "при","даже","мне","есть","только","очень",
		                        "сейчас","точно","обычно"
	                        );
	$contents = @str_replace($rearray," ",$contents);
	$keywordcache = @explode(" ",$contents);
	$rearray = array();
	foreach($keywordcache as $word){
		if(strlen($word)>=$symbol && !is_numeric($word)){
			$adjective = substr($word,-2);
			if(!in_array($adjective,$adjectivearray) && !in_array($word,$adjectivearray)){
				$rearray[$word] = (array_key_exists($word,$rearray)) ? ($rearray[$word] + 1) : 1;
			}
		}
	}
	@arsort($rearray);
	$keywordcache = @array_slice($rearray,0,$words);
	$keywords = "";
	foreach($keywordcache as $word=>$count){
		$keywords.= ",".$word;
	}
	return substr($keywords,1);
}
function do_replace($matches) {
    $str = substr($matches[0], 1, strlen($matches[0]) - 2); // $str = Пожалуйста,|Просто|Если сможете,
    $str = preg_replace_callback('~{(?>[^{}]+|(?0))*}~', 'do_replace', $str); // раскрытие вложенных фигурных скобок
    $variants = explode('|', $str); // array('Пожалуйста,', 'Просто' , 'Если сможете,')
    return $variants[mt_rand(0, count($variants) - 1)];
}
?>
