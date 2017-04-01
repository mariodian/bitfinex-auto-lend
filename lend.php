<?php

$currency = @$_GET['currency'] ? htmlspecialchars($_GET['currency']) : 'usd';

include_once("./config-$currency.php");
include_once('./functions.php');
include_once('./bitfinex.php');

$bfx = new Bitfinex($config['api_key'], $config['api_secret']);

$current_offers = $bfx->get_offers();

// Something is wrong most likely API key
if (array_key_exists('message', $current_offers)) {
	die($current_offers['message']);
}

// Remove offers that weren't executed for too long
foreach ($current_offers as $item) {
	$id = $item['id'];
	$timestamp = (int) $item['timestamp'];
	$current_timestamp = time();
	$diff_minutes = round(($current_timestamp - $timestamp) / 60);

	if ($config['remove_after'] <= $diff_minutes) {
		message("Removing offer # $id");

		$bfx->cancel_offer($id);
	}
}

$balances = $bfx->get_balances();
$available_balance = 0;

if ($balances) {
	foreach ($balances as $item) {
		if ($item['type'] == 'deposit' && $item['currency'] == strtolower($config['currency'])) {
			$available_balance = floatval($item['available']);

			break;
		}
	}
}

if ($currency !== 'usd') {
	$ticker = $bfx->get_ticker("{$currency}usd");
	$minimum_balance = $ticker['last_price'] * $available_balance;
} else {
	$minimum_balance = $config['minimum_balance'];
}

// Is there enough balance to lend?
if ($available_balance >= $minimum_balance) {
	message("Lending availabe balance of $available_balance");

	$lendbook = $bfx->get_lendbook($config['currency']);
	$offers = $lendbook['asks'];
	$bids = $lendbook['bids'];

	$total_amount 	= 0;
	$next_rate 		= 0;
	$next_amount 	= 0;
	$check_next 	= FALSE;
	$lowest_rate 	= count($bids) ? $bids[0]['rate'] : 0;

	// Find the right rate
	foreach ($offers as $item) {
		// Save next closest item
		if ($check_next) {
			$next_rate 		= $item['rate'];
			$next_amount 	= $item['amount'];
			$check_next 	= FALSE;
		}

		$total_amount += floatval($item['amount']);

		// Possible closest rate to what we want to lend (never go lower than the highest bid rate)
		if ($next_rate < $lowest_rate || $total_amount <= $config['max_total_swaps']) {
			$rate = $item['rate'];
			$check_next = TRUE;
		}
	}

	// Current rate is too low, move closer to the next rate
	if($next_amount <= $config['max_total_swaps']) {
		$rate = $next_rate - 0.01;
	}

	$result = $bfx->new_offer($config['currency'], $available_balance, $rate, $config['period'], 'lend');

	// Successfully lent
	if (array_key_exists('id', $result)) {
		$daily_rate = daily_rate($rate);

		message("$available_balance {$config['currency']} lent for {$config['period']} days at daily rate of $daily_rate%. Offer id {$result['id']}.");
	} else {
		// Something went wrong
		message($result);
	}
}
else {
	message("Balance of $available_balance {$config['currency']} is not enough to lend.");
}

?>
