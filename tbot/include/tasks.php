<?
/* *** */
require_once("include/connect.php");
/* queries */
$sql_tasks = 'SELECT * FROM hq_pm_tasks WHERE status = 0'; // Tasks
$result_tasks = mysqli_query($link, $sql_tasks);

/* *** */
while ($row_tasks = mysqli_fetch_array($result_tasks)) {
  $id_project = $row_tasks['project_id'];
  $title_task = $row_tasks['title'];
  $id_task = $row_tasks['id'];
  $deadline_task = $row_tasks['due_date'];
  $sql_project = 'SELECT title FROM hq_pm_projects WHERE id = '.$id_project; // Project

  $result_project = mysqli_query($link, $sql_project);
  if ($row_project = mysqli_fetch_array($result_project)) {
    $title_project = $row_project['title'];
  }

/* *** */
$mess .= "Задача '".$title_task."' для проекта <b>".$title_project."</b>\r\n";
if ($deadline_task) {
  $deadline_task = substr($deadline_task, 0, strpos($deadline_task, ' '));
  // текущая дата на сервере
  $date_1 = date("Y-m-d");
  $date_2 = $deadline_task; 
  // перевод дат в формат timestamp
  $date_timestamp_1 = strtotime($date_1);
  $date_timestamp_2 = strtotime($date_2);
  /* *** */
  $diff = abs($date_timestamp_2 - $date_timestamp_1);
  $years = floor($diff / (365*60*60*24));
  $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
  $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
  /* *** */
  if ($months > 0) {
    $df = 'Через '.$months.' месяцев '.$days.' дней';
  } else {
    $df = 'Через '.$days.' дней';
  }
  $mess .= "Дедлайн задачи: ".$deadline_task." (".$df.")\r\n";
  /* *** */
}
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
/* *** */
if (empty($mess)) {
  $mess = 'Список задач пустой';
}
?>
