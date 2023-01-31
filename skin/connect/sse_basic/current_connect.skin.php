<?php
/**
  * -----------------------------------------------------------------------------
  * 그누보드5 - SSE 현재접속자 스킨
  * id: g5-skin-connect-sse-basic
  * https://github.com/hompy-dev/g5-skin-connect-sse-basic
  * @author <contact@hompy.dev>
  * -----------------------------------------------------------------------------
  */

if (!defined('_GNUBOARD_')) exit;
add_stylesheet('<link rel="stylesheet" href="'.$connect_skin_url.'/style.css">', 0);
add_javascript('<script defer src="'.$connect_skin_url.'/script.js"></script>', 0); // "defer" property!
?>

<div id="current_connect">
  <ul<?php if (G5_IS_MOBILE) echo ' class="mobile"'?> data-sse-url="<?php echo $connect_skin_url?>/sse.php"></ul>
  <p class="empty_li hidden">현재 접속자가 없습니다.</p>
</div>
