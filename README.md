WebMoney API PHP Library
========================

XML-interfaces supported
------------------------
- [X2](http://wiki.wmtransfer.com/projects/webmoney/wiki/Interface_X2): transferring funds from one purse to another
- [X3](https://wiki.wmtransfer.com/projects/webmoney/wiki/Interface_X3): receiving transaction history, checking transaction status
- [X8](http://wiki.wmtransfer.com/projects/webmoney/wiki/Interface_X8): retrieving information about purse ownership, searching for system user by his/her identifier or purse
- [X9](https://wiki.wmtransfer.com/projects/webmoney/wiki/Interface_X9): retrieving information about purse balance
- [X11](http://wiki.wmtransfer.com/projects/webmoney/wiki/Interface_X11): retrieving information from client’s passport by WM-identifier
- [X14](http://wiki.wmtransfer.com/projects/webmoney/wiki/Interface_X14): fee-free refund
- [X17](http://wiki.wmtransfer.com/projects/webmoney/wiki/Interface_X17): operations with arbitration contracts
- [X18](http://wiki.wmtransfer.com/projects/webmoney/wiki/Interface_X18): getting transaction details via merchant.webmoney
- [X19](http://wiki.wmtransfer.com/projects/webmoney/wiki/Interface_X19): verifying personal information for the owner of a WM identifier

Megastock interfaces supported
------------------------------
- Interface for [adding Payment Integrator's merchants](http://www.megastock.ru/Doc/AddIntMerchant.aspx?lang=en)
- Interface for [check status of merchant Payment Integrator](http://www.megastock.ru/Doc/AddIntMerchant.aspx)

Requirements
------------
The library requires PHP 5.3 compiled with [cURL extension](http://www.php.net/manual/en/book.curl.php) (but you can override cURL dependencies).

To use signing with the WM Keeper Classic keys you have to compile PHP with [BCMath](http://www.php.net/manual/en/book.bc.php) and [GMP](http://www.php.net/manual/en/book.gmp.php) support.

Example
-------
```php
require_once(__DIR__ . '/vendor/autoload.php'); // Require autoload file generated by composer

use baibaratsky\WebMoney;

$webMoney = new WebMoney\WebMoney(new WebMoney\Request\Requester\CurlRequester);

$x9request = new WebMoney\Api\X\X9\Request;
$x9request->setSignerWmid('your WMID');
$x9request->setRequestedWmid('requested WMID');
$x9request->sign(new WebMoney\Request\RequestSigner('wmid', 'key', 'password'));

if ($x9request->validate()) {
    $x9response = $webMoney->request($x9request);

    if ($x9response->getReturnCode() === 0) {
        echo $x9response->getPurseByName('Z000000000000')->getAmount();
    }
}
```
