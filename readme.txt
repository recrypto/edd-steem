=== EDD Steem ===
Contributors: recrypto
Donate link: https://steemit.com/@recrypto/
Tags: easydigitaldownloads, easy-digital-downloads, edd, payment method, steem, sbd
Requires at least: 4.1
Tested up to: 4.7.5
Stable tag: 1.0.3
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

EDD Steem lets you accept Steem payments directly to your Easy Digital Downloads shop (Currencies: STEEM, SBD).

== Description ==

EDD Steem lets you accept Steem payments directly to your Easy Digital Downloads shop (Currencies: STEEM, SBD).

= What is Steem? =
[Steem](https://steem.io/) is a blockchain-based social media platform where anyone can earn rewards. An example platform built on top of Steem block chain is [Steemit](steemit.com/).

[youtube https://www.youtube.com/watch?v=xZmpCAqD7hs]

= What is Cryptocurrency? =
A cryptocurrency (or crypto currency) is a digital asset designed to work as a medium of exchange using cryptography to secure the transactions and to control the creation of additional units of the currency. [Wikipedia](https://en.wikipedia.org/wiki/Cryptocurrency)

= Advantages =
You will _NOT_ require any Steem keys for this plugin to work. You just have to provide your Steem username and you're good to go.

= Limitations =
- Currently supports different fiat currencies such as: AUD, BGN, BRL, CAD, CHF, CNY, CZK, DKK, GBP, HKD, HRK, HUF, IDR, ILS, INR, JPY, KRW, MXN, MYR, NOK, NZD, PHP, PLN, RON, RUB, SEK, SGD, THB, TRY, ZAR, EUR
- If none of the fiat currency listed above, it will default 1:1 conversion rate.

= How does it confirm Steem Transfers? =
It uses WordPress CRON every 5 minutes to call Easy Digital Downloads orders that uses payment method as Steem and calls an API via Steemful (Another application I'm building around WordPress ecosystem) powered by SteemSQL.


== Installation ==

= Minimum Requirements =

* PHP version 5.2.4 or greater (PHP 5.6 or greater is recommended)
* MySQL version 5.0 or greater (MySQL 5.6 or greater is recommended)
* Some payment gateways require fsockopen support (for IPN access)
* Requires Easy Digital Downloads 2.5.0 requires WordPress 4.1+

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don't need to leave your web browser. To do an automatic install of EDD Steem, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.

In the search field type "EDD Steem" and click Search Plugins. Once you've found our eCommerce plugin you can view details about it such as the point release, rating and description. Most importantly of course, you can install it by simply clicking "Install Now".

= Manual installation =

The manual installation method involves downloading our eCommerce plugin and uploading it to your webserver via your favourite FTP application. The WordPress codex contains [instructions on how to do this here](https://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

= Updating =

Automatic updates should work like a charm; as always though, ensure you backup your site just in case.

== Frequently Asked Questions ==

= Where can I get support or talk to other users? =

If you get stuck, you can ask for help in the [EDD Steem Plugin Forum](https://wordpress.org/support/plugin/edd-steem).

= Where can I report bugs or contribute to the project? =

Bugs can be reported either in our support forum or preferably on the [EDD Steem GitHub repository](https://github.com/recrypto/edd-steem/issues).

= How can I contribute? =

Yes you can! Join in on our [GitHub repository](https://github.com/recrypto/edd-steem/) :)


== Screenshots ==

1. Selecting a Payment Method (Frontend)
2. Steem Payment Details (Frontend)
3. Easy Digital Downloads Settings (Backend)


== Changelog ==

= 1.0.1 - 2017-06-11 =
* Initial version in WordPress Plugin Repository


== Upgrade Notice ==

= 1.0.1 - 2017-06-11 =
* Initial version in WordPress Plugin Repository