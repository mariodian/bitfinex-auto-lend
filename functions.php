<?php

function array_debug($array)
{
	echo '<pre>';
	print_r($array);
	echo '</pre>';
}

function message($message)
{
	echo "MESSAGE: $message<br />";
}

function daily_rate($rate)
{
	return round($rate/365, 4);
}

?>