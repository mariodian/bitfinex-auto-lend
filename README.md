Bitfinex PHP bot that automatically lends your money based on the market prices.

## Options ##

To change options please edit config.php

| Option        | Default           | Description  |
| ------------- |:-------------:| -----:|
| api_key | - | Your API key generated at https://www.bitfinex.com/account/api |
| api_secret | - | Your API secret generated at https://www.bitfinex.com/account/api |
| currency      | USD | Currency of the money to lend |
| period      | 2      |   Number of days to lend money |
| minimum_balance | 50      |    Minimum balance in order to start lending (bitfinex constant) |
| max_total_swaps | 20000 | Max number of total swaps to check for a closest rate * |

\* If there are e.g. total of 12000 swaps at 1%/day and 22000 swaps at 1.001%/day the script chooses the lower rate, because we don't want to go beyond the rate of 20000 swaps.
