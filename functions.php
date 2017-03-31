<?php

function debug() {
	echo '<pre>';
	print_r(func_get_args());
	echo '</pre>';
}

function message($message) {
	echo "MESSAGE: $message<br />";
}

function daily_rate($rate) {
	return round($rate/365, 4);
}

?>