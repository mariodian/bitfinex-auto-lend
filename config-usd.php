<?php

include_once('./config.php');

$config['currency'] = 'USD'; // Currency to lend
$config['period'] = 2; // Number of days to lend
$config['remove_after'] = 50; // Minutes after unexecuted offer gets cancelled
$config['max_total_swaps'] = 100000; // Max number of total swaps to check for a closest rate *

// * If there are e.g. total of 89000 swaps at 1%/day and 122000
// swaps at 1.001%/day the script chooses the lower rate, because
// we don't want to go beyond the rate of $config['max_total_swaps'] swaps.
// If the currency doesn't get lent within an hour you may want to lower this value