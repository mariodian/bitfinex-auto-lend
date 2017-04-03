<?php

function debug() {
	echo '<pre>';
	print_r(func_get_args());
	echo '</pre>';
}

function message($message) {
	echo "MESSAGE: $message<br />";
}

function amount($amount) {
	$number = intval(($amount * 100))/100;

	return number_format($number, 2, '.', '');
}

function daily_rate($rate) {
	return round($rate/365, 4);
}

?>