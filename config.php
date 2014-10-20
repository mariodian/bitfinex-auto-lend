<?php 

$config = array();

$config['api_key'] = '<your api key>';
$config['api_secret'] = '<your secret key>';

$config['currency'] = 'USD'; // Currency to lend
$config['period'] = 2; // Number of days to lend
$config['minimum_balance'] = 50; // Minimum balance to be able to lend (bitfinex constant)
$config['max_total_swaps'] = 20000; // Max number of total swaps to check for a closest rate *

// * e.g.:
// If there are total of 12000 swaps at 1%/day and 22000 swaps at 1.001%/day
// we don't want to go beyond the rate of 20000 swaps so the closest one of 1%/day 
// is what we're looking for.

?>