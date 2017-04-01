<?php

include_once('./config.php');

$config['currency'] = 'USD'; // Currency to lend
$config['period'] = 2; // Number of days to lend
$config['remove_after'] = 50; // Minutes after unexecuted offer gets cancelled
$config['max_total_swaps'] = 20000; // Max number of total swaps to check for a closest rate *

// * If there are e.g. total of 12000 swaps at 1%/day and 22000
// swaps at 1.001%/day the script chooses the lower rate, because
// we don't want to go beyond the rate of 20000 swaps.

?>