Bitfinex Auto Lend
==================

Bitfinex Auto Lend is bitfinex PHP bot that automatically lends your money based on the market prices.

## Options ##

To change options please edit config-{currency}.php

Available currencies to lend: USD, BTC, LTC.

| Option        | Default           | Description  |
| ------------- |:-------------:| -----|
| api_key | - | Your API key generated at https://www.bitfinex.com/account/api |
| api_secret | - | Your API secret generated at https://www.bitfinex.com/account/api |
| currency      | USD | Currency of the money to lend.  |
| period      | 2      |   Number of days to lend money |
| remove_after | 50 | Number of minutes after unexecuted offer will get cancelled |
| minimum_balance | 50      |    Minimum balance in order to start lending (bitfinex constant) |
| max_total_swaps | 20000 | Max number of total swaps to check for a closest rate * |

\* If there are e.g. total of 12000 swaps at 1%/day and 22000 swaps at 1.001%/day the script chooses the lower rate, because we don't want to go beyond the rate of 20000 swaps.

## How to run ##

Set cron to automatically execute the script lend.php at given time. To add cron jobs, open crontab file:

> crontab -e

and append a cron entry. Make sure you set the correct script path.

Save the file when you're done and make sure you can see your job in crontab:

> crontab -l

### Examples ###

| Option        | Description  |
| ------------- | -----|
| \*/30 \* \* \* \* /path/to/script/lend.php  | Run every 30 minutes  |
| 30 \* \* \* \* /path/to/script/lend.php?currency=btc | Run every hour at 30 minutes |
| 0 0 \* \* \* /path/to/script/lend.php?currency=ltc      | Run every day at 12AM |
| 20 13 \* \* 6 /path/to/script/lend.php      | Run every saturday at 1:20PM  |
| 0 8 \* \* 1,2,3,4,5 /path/to/script/lend.php |  Run every work day at 8AM |
