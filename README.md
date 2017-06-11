# EDD Steem (Easy Digital Downloads Steem)
Accept Steem payments directly to your Easy Digital Downloads shop!

## Supported Steem Currencies
- Steem (STEEM)
- Steem Backed Dollars (SBD)

## Limitation
- Currently supports different fiat currencies such as: AUD, BGN, BRL, CAD, CHF, CNY, CZK, DKK, GBP, HKD, HRK, HUF, IDR, ILS, INR, JPY, KRW, MXN, MYR, NOK, NZD, PHP, PLN, RON, RUB, SEK, SGD, THB, TRY, ZAR, EUR
- If none of the fiat currency listed above, it will default 1:1 conversion rate.

## How does it confirm Steem Transfers?
It uses WordPress CRON every 5 minutes to call Easy Digital Downloads orders that uses payment method as Steem and calls an API via Steemful (Another application I'm building around WordPress ecosystem) powered by SteemSQL.

## Note
You will <strong>NOT</strong> require any Steem keys for this plugin to work. You just have to provide your Steem username and you're good to go.

## Screenshots

![Screenshot #1](https://imgoat.com/uploads/7693cfc748/22570.png)

![Screenshot #2](https://imgoat.com/uploads/7693cfc748/22572.png)

Payment Gateway Settings (Backend)
![Screenshot #3](https://imgoat.com/uploads/7693cfc748/22571.png)

## Links
- [WordPress Plugin Repository](https://wordpress.org/extend/plugins/edd-steem)

## Thanks
- [@arcange](https://steemit.com/@arcange) for [SteemSQL](https://steemsql.com)
- [@furion](https://steemit.com/@furion) for SteemData (Fallback when SteemSQL doesn't yet index Steem blockchain)

## Support
Please support me by following me on Steem [@recrypto](https://steemit.com/@recrypto) or if you feel like donating, that would really help a lot on my future Steem developments around WordPress ecosystem. :)

Steem: @recrypto
