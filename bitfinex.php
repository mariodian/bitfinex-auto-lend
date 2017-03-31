<?php

/**
* Bitfinex PHP API
*
* Access all features of https://www.bitfinex.com trading platform
*
* @package  Bitfinex
* @author   Mario Dian (http://mariodian.com)
* @license  https://creativecommons.org/publicdomain/zero/1.0/ CC0 1.0 Universal
* @version  0.1.2
* @link     https://github.com/mariodian/bitfinex-api-php
*/

class Bitfinex {
  const CONNECT_TIMEOUT = 60;
  const API_URL = 'https://api.bitfinex.com';

  private $api_key = '';
  private $api_secret = '';
  private $api_version = '';

  /**
  * @param string $api_key       Your API key obtained from https://www.bitfinex.com/account/api
  * @param string $api_secret    Your API secret obtained from https://www.bitfinex.com/account/api
  * @param string $api_version   Bitfinex API version
  */
  public function __construct($api_key, $api_secret, $api_version = 'v1') {
    $this->api_key = $api_key;
    $this->api_secret = $api_secret;
    $this->api_version = $api_version;
  }

  /**
  * Public endpoints
  * =================================================================
  */

  /**
  * Get Book
  *
  * Get the full order book.
  *
  * @param string $symbol    The name of the symbol (see `/symbols`).
  * @param int $limit_bids   Limit the number of bids returned. May be 0
  *                          in which case the array of bids is empty.
  * @param int $limit_asks   Limit the number of asks returned. May be 0
  *                          in which case the array of asks is empty.
  * @param int $group        If 1, orders are grouped by price in the
  *                          orderbook. If 0, orders are not grouped and
  *                          sorted individually
  * @return mixed
  */
  public function get_book($symbol = 'BTCUSD', $data = array()) {
  	$request = $this->endpoint('book', $symbol);

  	return $this->send_public_request($request, $data);
  }

  /**
  * Get Lendbook
  *
  * Get the full margin funding book.
  *
  * @param string $currency      USD/BTC/LTC/ETH
  * @param int $limit_bids       Limit the number of funding bids returned.
  *                              May be 0 in which case the array of bids is
  *                              empty.
  * @param int $limit_asks       Limit the number of funding offers returned.
  *                              May be 0 in which case the array of asks is
  *                              empty.
  * @return mixed
  */
  public function get_lendbook($currency = 'USD', $data = array()) {
  	$request = $this->endpoint('lendbook', $currency);

  	return $this->send_public_request($request, $data);
  }

  /**
  * Get Lends
  *
  * Get a list of the most recent funding data for the given currency: total
  * amount provided and Flash Return Rate (in % by 365 days) over time.
  *
  * @param string $currency      USD/BTC/LTC/ETH
  * @param time $timestamp       Only show data at or after this timestamp.
  * @param int $limit_lends      Limit the amount of funding data returned.
  *                              Must be >= 1
  * @return mixed
  */
  public function get_lends($currency = 'USD', $data = array()) {
  	$request = $this->endpoint('lends', $currency);

  	return $this->send_public_request($request, $data);
  }

  /**
  * Get Stats
  *
  * Various statistics about the requested pair.
  *
  * @param string $symbol    The name of the symbol (see `/symbols`).
  * @return mixed
  */
  public function get_stats($symbol = 'BTCUSD') {
  	$request = $this->endpoint('stats', $symbol);

  	return $this->send_public_request($request);
  }

  /**
  * Get Symbols
  *
  * Get a list of valid symbol IDs.
  *
  * @return mixed
  */
  public function get_symbols() {
  	$request = $this->endpoint('symbols');

  	return $this->send_public_request($request);
  }

  /**
  * Get Symbols Details
  *
  * Get a list of valid symbol IDs and the pair details.
  *
  * @return mixed
  */
  public function get_symbols_details() {
  	$request = $this->endpoint('symbols_details');

  	return $this->send_public_request($request);
  }

  /**
  * Get Ticker
  *
  * Gives innermost bid and asks and information on the most recent trade, as
  * well as high, low and volume of the last 24 hours.
  *
  * @param string $symbol    The name of the symbol (see `/symbols`).
  * @return mixed
  */
  public function get_ticker($symbol = 'BTCUSD') {
  	$request = $this->endpoint('pubticker', $symbol);

  	return $this->send_public_request($request);
  }

  /**
  * Get Trades
  *
  * Get a list of the most recent trades for the given symbol.
  *
  * @param string $symbol        The name of the symbol (see `/symbols`).
  * @param time $timestamp       Only show trades at or after this timestamp.
  * @param int $limit_trades     Limit the number of trades returned. Must
  *                              be >= 1.
  * @return mixed
  */
  public function get_trades($symbol = 'BTCUSD', $data = array()) {
  	$request = $this->endpoint('trades', $symbol);

  	return $this->send_public_request($request, $data);
  }

  /**
  * Authenticated endpoints
  * =================================================================
  */

  /**
  * Get Account Infos
  *
  * Return information about your account (trading fees).
  *
  * @return mixed
  */
  public function get_account_infos() {
  	$request = $this->endpoint('account_infos');

  	$data = array(
      'request' => $request
  	);

  	return $this->send_auth_request($data);
  }

  /**
  * Get Summary
  *
  * Returns a 30-day summary of your trading volume and return on margin
  * funding
  *
  * @return mixed
  */
  public function get_summary() {
  	$request = $this->endpoint('summary');

  	$data = array(
      'request' => $request
  	);

  	return $this->send_auth_request($data);
  }

  /**
  * New Deposit
  *
  * Return your deposit address to make a new deposit.
  *
  * @param string $method        Method of deposit (methods accepted:
  *                              “bitcoin”, “litecoin”, “ethereum”,
  *                              “mastercoin” (tethers)).
  * @param string $wallet_name   Wallet to deposit in (accepted: “trading”,
  *                              “exchange”, “deposit”). Your wallet needs to
  *                              already exist
  * @param int $renew            (optional) Default is 0. If set to 1, will
  *                              return a new unused deposit address
  * @return mixed
  */
  public function new_deposit($method, $wallet_name, $renew = 0) {
  	$request = $this->endpoint('deposit', 'new');

  	$data = array(
      'request'     => $request,
      'method'      => $method,
      'wallet_name' => $wallet_name,
      'renew'       => $renew
  	);

  	return $this->send_auth_request($data);
  }

  /**
  * New Order
  *
  * Submit a new order.
  *
  * @param string $symbol         The name of the symbol (see `/symbols`).
  * @param decimal $amount        Order size: how much to buy or sell.
  * @param price $price           Price to buy or sell at. Must be positive.
  *                               Use random number for market orders.
  * @param string $exchange       “bitfinex”
  * @param string $side           Either “buy” or “sell”.
  * @param string $type           Either “market” / “limit” / “stop” /
  *                               “trailing-stop” / “fill-or-kill” /
  *                               “exchange market” / “exchange limit” /
  *                               “exchange stop” / “exchange trailing-stop” /
  *                               “exchange fill-or-kill”.
  *                               (type starting by “exchange ” are exchange
  *                               orders, others are margin trading orders)
  * @param bool $is_hidden        true if the order should be hidden. Default
  *                               is false.
  * @param bool $is_postonly      true if the order should be post only.
  *                               Default is false. Only relevant for limit
  *                               orders.
  * @param bool $ocoorder         Set an additional STOP OCO order that will
  *                               be linked with the current order.
  * @param price $buy_price_oco   If ocoorder is true, this field represent
  *                               the price of the OCO stop order to place
  * @return mixed
  */
  public function new_order($symbol, $amount, $price, $exchange, $side, $type, $is_hidden = FALSE, $is_postonly = FALSE, $ocoorder = FALSE, $buy_price_oco = NULL) {
  	$request = $this->endpoint('order', 'new');

  	$data = array(
      'request'     => $request,
      'symbol'      => $symbol,
      'amount'      => $this->num_to_string($amount),
      'price'       => $price,
      'exchange'    => $exchange,
      'side'        => $side,
      'type'        => $type,
      'is_hidden'   => $is_hidden,
      'is_postonly' => $is_postonly,
      'ocoorder'    => $ocoorder
  	);

    if ($ocoorder) {
      $data['buy_price_oco'] = $buy_price_oco;
    }

  	return $this->send_auth_request($data);
  }

  /**
  * New Multi Order
  *
  * Submit several new orders at once.
  *
  * @param string $symbol     The name of the symbol (see `/symbols`).
  * @param decimal $amount    Order size: how much to buy or sell.
  * @param price $price       Price to buy or sell at. May omit if a market
  *                           order.
  * @param string $exchange   “bitfinex”, “bitstamp”, “all” (for no routing).
  * @param string $side       Either “buy” or “sell”.
  * @param string $type       Either “market” / “limit” / “stop” /
  *                           “trailing-stop” / “fill-or-kill”.
  * @return mixed
  */
  public function new_multi_order($symbol, $amount, $price, $exchange, $side, $type) {
  	$request = $this->endpoint('order', 'new/multi');

  	$data = array(
      'request'   => $request,
      'symbol'    => $symbol,
      'amount'    => $this->num_to_string($amount),
      'price'     => $price,
      'exchange'  => $exchange,
      'side'      => $side,
      'type'      => $type
  	);

  	return $this->send_auth_request($data);
  }

  /**
  * Cancel Order
  *
  * Cancel an order.
  *
  * @param int $order_id  The order ID given by `/order/new`.
  * @return mixed
  */
  public function cancel_order($order_id) {
  	$request = $this->endpoint('order', 'cancel');

  	$data = array(
      'request'   => $request,
      'order_id'  => $order_id
  	);

  	return $this->send_auth_request($data);
  }

  /**
  * Cancel Multi Orders
  *
  * Cancel multiples orders at once.
  *
  * @param array $order_ids   An array of the order IDs given by `/order/new`
  *                           or `/order/new/multi`
  * @return mixed
  */
  public function cancel_multi_orders($order_ids) {
  	$request = $this->endpoint('order', 'cancel/multi');

  	$data = array(
      'request'   => $request,
      'order_ids' => $order_ids
  	);

  	return $this->send_auth_request($data);
  }

  /**
  * Cancel All Orders
  *
  * Cancel multiples orders at once.
  *
  * @return mixed
  */
  public function cancel_all_orders() {
  	$request = $this->endpoint('order', 'cancel/all');

  	$data = array(
      'request' => $request
  	);

  	return $this->send_auth_request($data);
  }

  /**
  * Replace Order
  *
  * Replace an orders with a new one.
  *
  * @param int $order_id          The order ID (to be replaced) given by
  *                               `/order/new`.
  * @param string $symbol         The name of the symbol (see `/symbols`).
  * @param decimal $amount        Order size: how much to buy or sell.
  * @param price $price           Price to buy or sell at. May omit if a
  *                               market order.
  * @param string $exchange       “bitfinex”, “bitstamp”, “all” (for no
  *                               routing).
  * @param string $side           Either “buy” or “sell”.
  * @param string $type           Either “market” / “limit” / “stop” /
  *                               “trailing-stop” / “fill-or-kill” /
  *                               “exchange market” / “exchange limit” /
  *                               “exchange stop” / “exchange trailing-stop” /
  *                               “exchange fill-or-kill”. (type starting by
  *                               “exchange ” are exchange orders, others are
  *                               margin trading orders)
  * @param bool $is_hidden        true if the order should be hidden. Default
  *                               is false.
  * @param bool $use_remaining    True if the new order should use the
  *                               remaining amount of the original order.
  *                               Default is false
  * @return mixed
  */
  public function replace_order($order_id, $symbol, $amount, $price, $exchange, $side, $type, $is_hidden = FALSE, $use_remaining = FALSE) {
  	$request = $this->endpoint('order', 'cancel/replace');

  	$data = array(
      'request'       => $request,
      'order_id'      => $order_id,
      'symbol'        => $symbol,
      'amount'        => $this->num_to_string($amount),
      'price'         => $price,
      'exchange'      => $exchange,
      'side'          => $side,
      'type'          => $type,
      'is_hidden'     => $is_hidden,
      'use_remaining' => $use_remaining
  	);

  	return $this->send_auth_request($data);
  }

  /**
  * Get Order
  *
  * Get the status of an order. Is it active? Was it cancelled? To what extent
  * has it been executed? etc.
  *
  * @param int $order_id      The order ID given by `/order/new`.
  * @return mixed
  */
  public function get_order($order_id) {
  	$request = $this->endpoint('order', 'status');

  	$data = array(
      'request'   => $request,
      'order_id'  => $order_id
  	);

  	return $this->send_auth_request($data);
  }

  /**
  * Get Orders
  *
  * View your active orders.
  *
  * @return mixed
  */
  public function get_orders() {
  	$request = $this->endpoint('orders');

  	$data = array(
      'request' => $request
  	);

  	return $this->send_auth_request($data);
  }

  /**
  * Get Positions
  *
  * View your active positions.
  *
  * @return mixed
  */
  public function get_positions() {
  	$request = $this->endpoint('positions');

  	$data = array(
      'request' => $request
  	);

  	return $this->send_auth_request($data);
  }

  /**
  * Claim Position
  *
  * A position can be claimed if:
  *
  * It is a long position: The amount in the last unit of the position pair
  * that you have in your trading wallet AND/OR the realized profit of the
  * position is greater or equal to the purchase amount of the position (base
  * price * position amount) and the funds which need to be returned. For
  * example, for a long BTCUSD position, you can claim the position if the
  * amount of USD you have in the trading wallet is greater than the base
  * price * the position amount and the funds used.
  *
  * It is a short position: The amount in the first unit of the position pair
  * that you have in your trading wallet is greater or equal to the amount of
  * the position and the margin funding used.
  *
  * @param int $position_id       The position ID given by `/positions`.
  * @param decimal $amount        The partial amount you wish to claim
  * @return mixed
  */
  public function claim_position($position_id, $amount) {
  	$request = $this->endpoint('position', 'claim');

  	$data = array(
      'request'     => $request,
      'position_id' => $position_id,
      'amount'      => $this->num_to_string($amount),
  	);

  	return $this->send_auth_request($data);
  }

  /**
  * Get History
  *
  * View all of your balance ledger entries.
  *
  * @param string $currency   USD/BTC/LTC/ETH
  * @param int $limit         Optional. Limit the number of entries to return.
  *                           Default is 500.
  * @param string $wallet     Optional. Return only entries that took place in
  *                           this wallet. Accepted inputs are: “trading”,
  *                           “exchange”, “deposit”.
  * @param time $since        Optional. Return only the history after this
  *                           timestamp.
  * @param time $until        Optional. Return only the history before this
  *                           timestamp.
  * @return mixed
  */
  public function get_history($currency, $wallet, $limit = 500, $since = NULL, $until = NULL) {
  	$request = $this->endpoint('history');

  	$data = array(
      'request'   => $request,
      'currency'  => $currency,
      'wallet'    => $wallet,
      'limit'     => $limit
  	);

  	if ($since) {
      $data['since'] = $since;
  	}

  	if ($until) {
      $data['until'] = $until;
  	}

  	return $this->send_auth_request($data);
  }

  /**
  * Get History Movements
  *
  * View your past deposits/withdrawals.
  *
  * @param string $currency   USD/BTC/LTC/ETH
  * @param string $method     Optional. The method of the deposit/withdrawal
  *                           (can be “bitcoin”, “litecoin”, “darkcoin”,
  *                           “wire”).
  * @param int $limit         Optional. Limit the number of entries to return.
  *                           Default is 500.
  * @param time $since        Optional. Return only the history after this
  *                           timestamp.
  * @param time $until        Optional. Return only the history before this
  *                           timestamp.
  * @return mixed
  */
  public function get_history_movements($currency = 'USD', $method = 'bitcoin', $limit = 500, $since = NULL, $until = NULL) {
  	$request = $this->endpoint('history', 'movements');

  	$data = array(
      'request'   => $request,
      'currency'  => $currency,
      'method'    => $method,
      'limit'     => $limit
  	);

  	if ($since) {
  		$data['since'] = $since;
  	}

  	if ($until) {
  		$data['until'] = $until;
  	}

  	return $this->send_auth_request($data);
  }

  /**
  * Get My Trades
  *
  * View your past trades.
  *
  * @param string $symbol         The name of the symbol (see `/symbols`).
  * @param int $limit_trades      Optional. Limit the number of trades
  *                               returned. Default is 50.
  * @param time $timestamp        Trades made before this timestamp won’t be
  *                               returned.
  * @param time $until            Optional. Trades made after this timestamp
  *                               won’t be returned.
  * @param int $reverse           Optional. Return trades in reverse order
  *                               (the oldest comes first). Default is
  *                               returning newest trades first.
  * @return mixed
  */
  public function get_my_trades($symbol = 'BTCUSD', $limit_trades = 50, $timestamp = NULL, $until = NULL, $reverse = 0) {
  	$request = $this->endpoint('mytrades');

  	$data = array(
      'request'       => $request,
      'symbol'        => $symbol,
      'limit_trades'  => $limit_trades,
      'reverse'       => $reverse
  	);

    if ($timestamp) {
      $data['timestamp'] = $timestamp;
  	}

  	if ($until) {
  		$data['until'] = $until;
  	}

  	return $this->send_auth_request($data);
  }

  /**
  * New Offer
  *
  * Submit a new offer.
  *
  * @param string $currency       USD/BTC/LTC/ETH
  * @param price $amount          Offer size: how much to lend or borrow.
  * @param decimal $rate          Rate to lend or borrow at. In percentage per
  *                               365 days. (Set to 0 for FRR).
  * @param int $period            Number of days of the funding contract (in days)
  * @param string $direction      Either “lend” or “loan”.
  * @return mixed
  */
  public function new_offer($currency = 'BTC', $amount, $rate, $period, $direction = 'lend') {
  	$request = $this->endpoint('offer', 'new');

  	$data = array(
      'request'   => $request,
      'currency'  => $currency,
      'amount'    => $this->num_to_string($amount),
      'rate'      => $this->num_to_string($rate),
      'period'    => $period,
      'direction' => $direction
  	);

  	return $this->send_auth_request($data);
  }

  /**
  * Cancel Offer
  *
  * Cancel an offer.
  *
  * @param int $offer_id      The offer ID given by `/offer/new`.
  * @return mixed
  */
  public function cancel_offer($offer_id) {
  	$request = $this->endpoint('offer', 'cancel');

  	$data = array(
      'request'   => $request,
      'offer_id'  => $offer_id
  	);

  	return $this->send_auth_request($data);
  }

  /**
  * Get Offer
  *
  * Get the status of an offer. Is it active? Was it cancelled? To what extent
  * has it been executed? etc.
  *
  * @param int $offer_id      The offer ID given by `/offer/new`.
  * @return mixed
  */
  public function get_offer($offer_id) {
  	$request = $this->endpoint('offer', 'status');

  	$data = array(
      'request'   => $request,
      'offer_id'  => $offer_id
  	);

  	return $this->send_auth_request($data);
  }

  /**
  * Get Credits
  *
  * View your funds currently taken (active credits).
  *
  * @return mixed
  */
  public function get_credits() {
  	$request = $this->endpoint('credits');

  	$data = array(
      'request' => $request
  	);

  	return $this->send_auth_request($data);
  }

  /**
  * Get Offers
  *
  * View your active offers.
  *
  * @return mixed
  */
  public function get_offers() {
  	$request = $this->endpoint('offers');

  	$data = array(
      'request' => $request
  	);

  	return $this->send_auth_request($data);
  }

  /**
  * Get Taken Funds
  *
  * View your funding currently borrowed and used in a margin position.
  *
  * @return mixed
  */
  public function get_taken_funds() {
  	$request = $this->endpoint('taken_funds');

  	$data = array(
      'request' => $request
  	);

  	return $this->send_auth_request($data);
  }

  /**
  * Get Unused Taken Funds
  *
  * View your funding currently borrowed and not used (available for a new
  * margin position).
  *
  * @return mixed
  */
  public function get_unused_taken_funds() {
  	$request = $this->endpoint('unused_taken_funds');

  	$data = array(
      'request' => $request
  	);

  	return $this->send_auth_request($data);
  }

  /**
  * Get Total Taken Funds
  *
  * View the total of your active funding used in your position(s).
  *
  * @return mixed
  */
  public function get_total_taken_funds() {
  	$request = $this->endpoint('total_taken_funds');

  	$data = array(
      'request' => $request
  	);

  	return $this->send_auth_request($data);
  }

  /**
  * Close Funding
  *
  * Allow you to close an unused or used taken fund.
  *
  * @param int $swap_id       The ID given by `/taken_funds` or
  *                           `/unused_taken_funds`.
  * @return mixed
  */
  public function close_funding($swap_id) {
  	$request = $this->endpoint('funding', 'close');

  	$data = array(
      'request' => $request,
      'swap_id' => $swap_id
  	);

  	return $this->send_auth_request($data);
  }

  /**
  * Get Balances
  *
  * See your balances.
  *
  * @return mixed
  */
  public function get_balances() {
  	$request = $this->endpoint('balances');

  	$data = array(
      'request' => $request
  	);

  	return $this->send_auth_request($data);
  }

  /**
  * Get Margin Infos
  *
  * See your trading wallet information for margin trading.
  *
  * @return mixed
  */
  public function get_margin_infos() {
  	$request = $this->endpoint('margin_infos');

  	$data = array(
      'request' => $request
  	);

  	return $this->send_auth_request($data);
  }

  /**
  * Transfer
  *
  * Allow you to move available balances between your wallets.
  *
  * @param string $currency       USD/BTC/LTC/ETH
  * @param price $amount          Amount to transfer.
  * @param string $walletfrom     Wallet to transfer from.
  * @param string $walletto       Wallet to transfer to.
  *
  * @return mixed
  */
  public function transfer($currency, $amount, $walletfrom, $walletto) {
  	$request = $this->endpoint('transfer');

  	$data = array(
      'request'     => $request,
      'currency'    => $currency,
      'amount'      => $this->num_to_string($amount),
      'walletfrom'  => $walletfrom,
      'walletto'    => $walletto
  	);

  	return $this->send_auth_request($data);
  }

  /**
  * Withdraw
  *
  * Submit a new order.
  *
  * @param string $withdraw_type          can be “bitcoin”, “litecoin” or
  *                                       “ethereum” or “tether” or “wire”.
  * @param string $walletselected         The wallet to withdraw from, can be
  *                                       “trading”, “exchange”, or “deposit”.
  * @param string $amount                 Amount to withdraw.
  * @param string $address                Destination address for withdrawal.
  * @param int $expressWire               Optional. “1” to submit an express
  *                                       wire withdrawal, “0” or omit for a
  *                                       normal withdrawal
  * @param string $account_name           Account name
  * @param string $account_number         Account number
  * @param string $bank_name              Bank name
  * @param string $bank_address           Bank address
  * @param string $bank_city              Bank city
  * @param string $bank_country           Bank country
  * @param string $detail_payment         Optional. Message to beneficiary
  * @param string $im_bank_name           Optional. Intermediary bank name
  * @param string $im_bank_address        Optional. Intermediary bank address
  * @param string $im_bank_city           Optional. Intermediary bank city
  * @param string $im_bank_country        Optional. Intermediary bank country
  * @param string $im_bank_account        Optional. Intermediary bank account
  * @param string $im_bank_swift          Optional. Intermediary bank SWIFT
  * @return mixed
  */
  public function withdraw($withdraw_type, $walletselected, $amount, $address = '', $expressWire = 0, $bank_data = array()) {
  	$request = $this->endpoint('withdraw');

  	$data = array(
      'request'         => $request,
      'withdraw_type'   => $withdraw_type,
      'walletselected'  => $walletselected,
      'amount'          => $this->num_to_string($amount),
  	);

  	switch ($withdraw_type) {
      case 'bitcoin':
      case 'litecoin':
      case 'ethereum':
      case 'tether':
        $data['address'] = $address;

        break;
      case 'wire':
        $data['expressWire'] = $expressWire;

        $data['account_name']   = $bank_data['account_name'];
        $data['account_number'] = $bank_data['account_number'];
        $data['bank_name']      = $bank_data['bank_name'];
        $data['bank_address']   = $bank_data['bank_address'];
        $data['bank_city']      = $bank_data['bank_city'];
        $data['bank_country']   = $bank_data['bank_country'];
        $data['detail_payment'] = array_key_exists('detail_payment', $bank_data) ? $bank_data['detail_payment'] : '';

        $data['intermediary_bank_name']     = array_key_exists('im_bank_name', $bank_data) ? $bank_data['im_bank_name'] : '';
        $data['intermediary_bank_address']  = array_key_exists('im_bank_address', $bank_data) ? $bank_data['im_bank_address'] : '';
        $data['intermediary_bank_city']     = array_key_exists('im_bank_city', $bank_data) ? $bank_data['im_bank_city'] : '';
        $data['intermediary_bank_country']  = array_key_exists('im_bank_country', $bank_data) ? $bank_data['im_bank_country'] : '';
        $data['intermediary_bank_account']  = array_key_exists('im_bank_account', $bank_data) ? $bank_data['im_bank_account'] : '';
        $data['intermediary_bank_swift']    = array_key_exists('im_bank_swift', $bank_data) ? $bank_data['im_bank_swift'] : '';

        break;
  	}

  	return $this->send_auth_request($data);
  }

  /**
  * Get Key Info
  *
  * Check the permissions of the key being used to generate this request
  *
  * @return mixed
  */
  public function get_key_info() {
  	$request = $this->endpoint('key_info');

  	$data = array(
      'request' => $request
  	);

  	return $this->send_auth_request($data);
  }

  /**
  * Endpoint
  *
  * Construct an endpoint URL
  *
  * @param string $method
  * @param mixed $params
  * @return string
  */
  private function endpoint($method, $params = NULL) {
  	$parameters = '';

  	if ($params !== NULL) {
      $parameters = '/';

      if (is_array($params)) {
        $parameters .= implode('/', $params);
      } else {
        $parameters .= $params;
      }
  	}

  	return "/{$this->api_version}/$method$parameters";
  }

  /**
  * Prepare Header
  *
  * Add data to header for authentication purpose
  *
  * @param array $data
  * @return json
  */
  private function prepare_header($data)
  {
  	$data['nonce'] = (string) number_format(round(microtime(true) * 100000), 0, '.', '');

  	$payload = base64_encode(json_encode($data));
  	$signature = hash_hmac('sha384', $payload, $this->api_secret);

  	return array(
      'X-BFX-APIKEY: ' . $this->api_key,
      'X-BFX-PAYLOAD: ' . $payload,
      'X-BFX-SIGNATURE: ' . $signature
  	);
  }

  /**
  * Curl Error
  *
  * Output curl error if possible
  *
  * @param array $data
  * @return json
  */
  private function curl_error($ch) {
  	if ($errno = curl_errno($ch)) {
      $error_message = curl_strerror($errno);
      echo "cURL error ({$errno}):\n {$error_message}";

      return FALSE;
  	}

  	return TRUE;
  }

  /**
  * Is Bitfinex Error
  *
  * Check whether bitfinex API returned an error message
  *
  * @param array $ch 	Curl resource
  * @return bool
  */
  private function is_bitfinex_error($ch) {
  	$http_code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);

  	if ($http_code !== 200) {
      return TRUE;
  	}

  	return FALSE;
  }

  /**
  * Output
  *
  * Prepare API output
  *
  * @param json $result
  * @param bool $is_error
  * @return array
  */
  private function output($result, $is_error = FALSE) {
  	$out_array = json_decode($result, TRUE);

  	if ($is_error) {
      $out_array['error'] = TRUE;
  	}

  	return $out_array;
  }

  /**
  * Send Signed Request
  *
  * Send a signed HTTP request
  *
  * @param array $data
  * @return mixed
  */
  private function send_auth_request($data) {
  	$ch = curl_init();
  	$url = self::API_URL . $data['request'];

  	$headers = $this->prepare_header($data);

  	curl_setopt_array($ch, array(
      CURLOPT_URL             => $url,
      CURLOPT_POST            => TRUE,
      CURLOPT_RETURNTRANSFER  => TRUE,
      CURLOPT_HTTPHEADER      => $headers,
      CURLOPT_SSL_VERIFYPEER  => TRUE,
      CURLOPT_CONNECTTIMEOUT  => self::CONNECT_TIMEOUT,
      CURLOPT_POSTFIELDS      => ''
  	));

  	if( !$result = curl_exec($ch) ) {
      return $this->curl_error($ch);
  	} else {
      return $this->output($result, $this->is_bitfinex_error($ch));
  	}
  }

  /**
  * Send Unsigned Request
  *
  * Send an unsigned HTTP request
  *
  * @param string $request
  * @param array $params
  * @return mixed
  */
  private function send_public_request($request, $params = NULL) {
  	$ch = curl_init();
  	$query = '';

  	if (count($params)) {
      $query = '?' . http_build_query($params);
  	}

  	$url = self::API_URL . $request . $query;

  	curl_setopt_array($ch, array(
      CURLOPT_URL             => $url,
      CURLOPT_RETURNTRANSFER  => TRUE,
      CURLOPT_SSL_VERIFYPEER  => TRUE,
      CURLOPT_CONNECTTIMEOUT  => self::CONNECT_TIMEOUT,
  	));

  	if( !$result = curl_exec($ch) ) {
      return $this->curl_error($ch);
  	} else {
      return $this->output($result, $this->is_bitfinex_error($ch));
  	}
  }

  function num_to_string($num) {
  	return number_format($num, 2, '.', '');
  }
}

?>
