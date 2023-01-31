<?php
/**
  * -----------------------------------------------------------------------------
  * 그누보드5 - SSE 현재접속자 스킨
  * id: g5-skin-connect-sse-basic
  * https://github.com/hompy-dev/g5-skin-connect-sse-basic
  * @author <contact@hompy.dev>
  * -----------------------------------------------------------------------------
  */

$connect_skin_url = str_replace('/mobile/', '/', $connect_skin_url);
$connect_skin_path = str_replace('/mobile/', '/', $connect_skin_path);
include_once $connect_skin_path.'/current_connect.skin.php';
