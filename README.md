## Easy iDeal payments via Sisow (discontinued)
### Laravel 4 package

~~This package is in active development.~~
Please use something like https://github.com/thephpleague/omnipay instead.

### Installation

Require the package via Composer in your composer.json.

```json

"require": {
	...
	"vjanssens/laravel-sisow": "dev-master"
},

```

Run the next line to update dependencies (and thus download this package)

`php composer.phar update`

After Composer has succesfully updated the dependencies proceed to add your MerchantID, MerchantKey and all of the default values to 
`/vendor/vjanssens/laravel-sisow/src/config/config.php`

Finally add the next two lines in `/app/config/app.php`
```php
// File: app/config/app.php

'providers' => array(
    ...
    'Vjanssens\LaravelSisow\LaravelSisowServiceProvider',

),

'aliases' => array(
	...
	'Sisow'			  => 'Vjanssens\LaravelSisow\Facades\Sisow',
),
```

The package has been installed and you are ready to accept iDeal payments.

### Request banks
This method will fetch the banks where iDeal transactions can be done.

Best practice is to set testmode inside the `config.php` file but if you need to switch quickly you can pass `getBanks(true)`.

```php
$banks = Sisow::getBanks($testmode);
echo $banks;
```

### Request a payment URL
This method will fetch a URL where the payment will be processed.
Description, NotifyURL, ReturnURL and CancelURL can be set in `config.php`

```php
$args = array(

	'PurchaseID' 	=> '001', 	// unique ID per purchase
	'Amount'	 	=> '1.00',	// amount in EUR, minimal 0.45
	'IssuerID'	 	=> '99',	// bank ID 

	'Description'	=> 'Payment description',					// optional, see config file
	'NotifyURL'		=> 'http://www.yourdomain.com/notify',		// optional, see config file
	'ReturnURL'		=> 'http://www.yourdomain.com/return',		// optional, see config file
	'CancelURL'		=> 'http://www.yourdomain.com/cancel',		// optional, see config file
);

$paymentURL = Sisow::getPaymentURL($args);

echo '<pre>';
dd($paymentURL);

//return Redirect::to($paymentURL['url']);
```

### Get status of transaction (after payment)
After a transaction has been made, Sisow will sent through a unique Transaction ID which can be used
to get the status of a given transaction. (Succes, canceled, expired or failure)

```php
$transactionId = Input::get('trxid');

$status = Sisow::getStatus($transactionId);

echo '<pre>';
dd($status);
```
