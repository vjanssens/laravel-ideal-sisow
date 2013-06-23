# Easy iDeal payments via Sisow (Laravel 4 package)

This package is in active development.

## Installation
Paste these files inside /vendor/vjanssens/laravel-sisow

Add your MerchantID and MerchantKey to Sisow.php in the package.

Add the next two lines in app/config/app.php
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

After installation, run `composer dump-autoload`.

## Request banks
```php
$banks = Sisow::getBanks();
echo $banks;
```

## Request a payment URL
```php
$args = array(

	'PurchaseID' 	=> '001', 	// unique ID per purchase
	'Amount'	 	=> '1.00',	// amount in EUR, minimal 0.45
	'IssuerID'	 	=> '99',	// bank ID 

	'Testmode'   	=> true,				
	'Description'	=> 'Payment description',
	'NotifyURL'		=> 'http://www.yourdomain.com/notify',	
	'ReturnURL'		=> 'http://www.yourdomain.com/return',
	'CancelURL'		=> 'http://www.yourdomain.com/cancel',
	'CallbackURL'	=> 'http://www.yourdomain.com/callback'
);

$paymentURL = Sisow::getPaymentURL($args);

dd($paymentURL);

//return Redirect::to($paymentURL['url']);
```

## Get status of transaction (after payment)
```php
$transactionId = Input::get('trxid');

$status = Sisow::getStatus($transactionId);

echo '<pre>';
dd($status);
```