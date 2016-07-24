<?php
// Copyright 2015 ytocky
require_once 'message.php';

header('Content-Type: text/html; charset=UTF-8');
//header('Refresh: 10');
$msg = new Message();

date_default_timezone_set('Asia/Tokyo');
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
</head>
<body>
<?php
$items = $msg->get();
if ( count($items) > 0) {
	foreach ($items as $item) {
		$time = date('Y/m/d H:i:s', $item['time']);
		printf('<div><span>%1$s</span><span style="color:%2$s">[%3$s]%4$s</span></div>', $time, $item['color'], $item['name'], $item['message']);
		echo "\n";
	}
}
?>
</body>
</html>
