<?php
/**
  * -----------------------------------------------------------------------------
  * 그누보드5 - SSE 현재접속자 스킨
  * id: g5-skin-connect-sse-basic
  * https://github.com/hompy-dev/g5-skin-connect-sse-basic
  * @author <contact@hompy.dev>
  * -----------------------------------------------------------------------------
  */

$n = ( preg_match("/\/theme\//", __FILE__) ) ? 5 : 3;
$n+= ( preg_match("/\/mobile\//", __FILE__) ) ? 1 : 0;
$incPre = str_repeat('../', $n);

include_once $incPre.'common.php';

if (!$_SERVER['HTTP_REFERER'] || $_SERVER['HTTP_REFERER'] != G5_BBS_URL.'/current_connect.php') {
  header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found'); exit;
}

header('Cache-Control: no-cache');
header('Content-Type: text/event-stream');
session_write_close();
ob_end_flush();

$sql = "
  SELECT a.mb_id, b.mb_nick, b.mb_name, b.mb_email, b.mb_homepage, b.mb_open, b.mb_point, a.lo_ip, a.lo_location, a.lo_url
  FROM {$g5['login_table']} a LEFT JOIN {$g5['member_table']} b ON a.mb_id = b.mb_id
  WHERE a.mb_id <> '{$config['cf_admin']}'
  ORDER BY a.lo_datetime DESC
";

while (true) {
  $sel = sql_query($sql);
  $list = [];
  $i = 0;

  while ( $row = sql_fetch_array($sel) ) {
    $data = [];
    if ($row['mb_id']) {
      $data['name'] = get_sideview($row['mb_id'], cut_str($row['mb_nick'], $config['cf_cut_name']), $row['mb_email'], $row['mb_homepage']);
      $data['img'] = get_member_profile_img($row['mb_id']);
    } else {
      $data['name'] = $is_admin ? $row['lo_ip'] : preg_replace("/([0-9]+).([0-9]+).([0-9]+).([0-9]+)/", G5_IP_DISPLAY, $row['lo_ip']);
    }
    $url = get_text($row['lo_url']);
    $data['loc'] = ($url && $is_admin == 'super') ? "<a href=\"{$url}\">{$row['lo_location']}</a>" : $row['lo_location'];
    $list[] = $data;
    $i++;
  }

  $json = json_encode($list, JSON_UNESCAPED_UNICODE);
  echo 'data: '.$json."\n\n";
  ob_end_flush();
  flush();

  if ( connection_aborted() ) exit;

  sleep(10); // seconds to refetch
}