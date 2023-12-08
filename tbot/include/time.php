<?
/* *** */
require_once("connect.php");
/* queries */
$sql_tasks = 'SELECT * FROM hq_pm_tasks WHERE status = 0'; // Tasks
$result_tasks = mysqli_query($link, $sql_tasks);

/* *** */
while ($row_tasks = mysqli_fetch_array($result_tasks)) {
  $id_project = $row_tasks['project_id'];
  $title_task = $row_tasks['title'];
  $id_task = $row_tasks['id'];
  $deadline_task = $row_tasks['due_date'];
  /* *** */
  $deadline_task = substr($deadline_task, 0, strpos($deadline_task, ' '));
  $date_1 = date("Y-m-d");
  $date_2 = $deadline_task; 
  $date_timestamp_1 = strtotime($date_1);
  $date_timestamp_2 = strtotime($date_2);
  $diff = abs($date_timestamp_2 - $date_timestamp_1);
  $years = floor($diff / (365*60*60*24));
  $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
  $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
  /* *** */
  $sql_project = 'SELECT title FROM hq_pm_projects WHERE id = '.$id_project; // Project

  $result_project = mysqli_query($link, $sql_project);
  if ($row_project = mysqli_fetch_array($result_project)) {
    $title_project = $row_project['title'];
  }

/* *** */
if ($days <= 7) {
$mess .= "Задача '".$title_task."' для проекта <b>".$title_project."</b>\r\n";
  if ($months > 0) {
    $df = 'Через '.$months.' месяцев '.$days.' дней';
  } else {
    $df = 'Через '.$days.' дней';
  }
  $mess .= "Дедлайн задачи: ".$deadline_task." (".$df.")\r\n";
/* * assigned * */
$sql_assign = 'SELECT assigned_to FROM hq_pm_assignees WHERE task_id = '.$id_task; //
$result_assign = mysqli_query($link, $sql_assign);
$names = '';
while ($row_assign = mysqli_fetch_array($result_assign)) {
  $assign_id = $row_assign['assigned_to'];
  /* *** */
  //$sql_names = 'SELECT user_login FROM hq_users WHERE ID = '.$assign_id; // $bodytag = str_replace("%body%", "black", "<body text='%body%'>");
  $sql_names = 'SELECT user_url FROM hq_users WHERE ID = '.$assign_id;
  $result_names = mysqli_query($link, $sql_names);

  while ($row_names = mysqli_fetch_array($result_names)) {
    $oneName = str_replace("https://", "", $row_names['user_url']);
    $names .= $oneName.", ";
  }
  /* *** */
}
$names = substr($names,0,-2);
$mess .= 'Исполнитель: '.$names."\r\n";
/* *** */
$mess .= "<a href='https://hqrse.ru/wp-admin/admin.php?page=pm_projects#/projects/".$id_project."/task-lists/tasks/".$id_task."'>Подробнее</a>\r\n\r\n";
/* *** */
}
}
/* ************************************************************************************************************************************************ */
//bot chat:     405583257
//hqrse chat:   -724999280
if ($names === '@topgunishna') {
  $chatID = 405583257;
} else {
  $chatID = -724999280;  
}
$token = "5267388785:AAHjsBkueZ4KMEMA1_A93UbSxp60855-cg8";
$response = array(
  'chat_id' => $chatID,
  'text' => $mess,
  'parse_mode' => 'html'
);  
    
$ch = curl_init('https://api.telegram.org/bot' . $token . '/sendMessage');  
curl_setopt($ch, CURLOPT_POST, 1);  
curl_setopt($ch, CURLOPT_POSTFIELDS, $response);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_exec($ch);
curl_close($ch);

?>
