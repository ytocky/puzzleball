<?php
// Copyright 2015 ytocky
	require_once '../../src/PuzzleBall.php';
	require_once 'rules.php';
	require_once 'message.php';

	header('Content-Type: text/html; charset=UTF-8');

try {
	$colors = array( '#000000', '#CC3030', '#30CC30', '#3030CC', '#666666', '#A0A030', '#30A0A0', '#A030A0' );

	$rule['POST'] = array(
		'name' => Rules::$name,
		'color' => Rules::$color,
		'message' => Rules::$message,
	);

	$rule['COOKIE'] = array(
		'name' => Rules::$name,
		'color' => Rules::$color,
	);

	$ball = new PuzzleBall($rule);

	$name = $ball->cookie('name') ? $ball->cookie('name') : "";
	if ( !is_null($ball->name) ) {
		$name = $ball->name;
	}
	setcookie('name', $name, time()+60*60*24*30,"","",false,true);

	$color = $ball->cookie('color') ? $ball->cookie('color') : 0;
	if ( !is_null($ball->color) ) {
		$color = $ball->color;
	}
	setcookie('color', $color, time()+60*60*24*30,"","",false,true);

	if ( !is_null($ball->message) ) {
		$msg = new Message();
		$msg->add($colors[$color], $name, $ball->message);
	}
} catch (Exception $e) {
	echo "Error.";
	return;
}

function p($text)
{
	echo htmlspecialchars($text, ENT_QUOTES);
}
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Simple Chat</title>
<script>
var max_name = <?php echo Rules::MAX_NAME ?>;
var max_message = <?php echo Rules::MAX_MESSAGE ?>;
function check() {
	var name = document.getElementById("inputName").value;
	if (name.length<1 || name.length > max_name ) {
		alert("Name Length Error (1-"+max_name+")");
		return false;
	}

	var message = document.getElementById("inputMessage").value;
	if (message.length<1 || message.length > max_message ) {
		alert("Message Length Error (1-"+max_message+")");
		return false;
	}

	return true;
}

function colorChange() {
	var sel = document.getElementById("sel");
	var color = sel.options[sel.selectedIndex].style.color;
	sel.style.color = color;
}

function dispNameLength() {
	var msg = document.getElementById("inputName").value;
	var output = document.getElementById("namelength");
	var length = msg.length;
	output.textContent = max_name - length;
}

function dispMessageLength() {
	var msg = document.getElementById("inputMessage").value;
	var output = document.getElementById("msglength");
	var length = msg.length;
	output.textContent = max_message - length;
}

function onload() {
	colorChange();
	dispNameLength();
	dispMessageLength();
}

</script>
</head>
<body onload="onload()">

<div class="input">
<form name="form" method="POST" action="index.php">
Color:<select id="sel" name="color" onchange="colorChange()">
<?php
for ($i=0 ; $i<count($colors); $i++) {
	$selected = ($i==$color) ? 'selected' : '';
	printf('<option style="color:%2$s" value="%1$d" %3$s>%2$s</option>', $i, $colors[$i], $selected);
	echo "\n";
}
?>
</select>
Name:<input type="text" name="name" id="inputName" size="20" value="<?php p($name) ?>" onkeyup="dispNameLength()">(<span id="namelength"></span>)<br>

Message:<input type="text" name="message" id="inputMessage" size="100" value="" onkeyup="dispMessageLength()" autofocus>(<span id="msglength"></span>)
<button type="submit" onClick="return check()">Send</button>
</form>
</div>

<iframe id='board' src="board.php" width="800px" height="600px" sandbox></iframe>

</body>
</html>
