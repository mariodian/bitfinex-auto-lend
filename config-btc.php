<?php

include_once('./config.php');

$config['currency'] = 'BTC'; // Currency to lend
$config['period'] = 2; // Number of days to lend
$config['remove_after'] = 50; // Max number of total swaps to check for a closest rate *
$config['max_total_swaps'] = 50; // Minutes after unexecuted offer gets cancelled

// * If there are e.g. total of 40 swaps at 1%/day and 60
// swaps at 1.001%/day the script chooses the lower rate, because
// we don't want to go beyond the rate of 50 swaps.

?>