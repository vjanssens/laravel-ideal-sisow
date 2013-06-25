## Easy iDeal payments via Sisow
### Laravel 4 package

This package is in active development.

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

After Composer has succesfully updated the dependencies proceed to add your MerchantID and MerchantKey to 
`/vendor/vjanssens/laravel-sisow/src/Vjanssens/LaravelSisow/Sisow.php`

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

The package has been isntalled and you are ready to accept iDeal payments.

### Request banks
```php
$banks = Sisow::getBanks();
echo $banks;
```

### Request a payment URL
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

echo '<pre>';
dd($paymentURL);

//return Redirect::to($paymentURL['url']);
```

### Get status of transaction (after payment)
```php
$transactionId = Input::get('trxid');

$status = Sisow::getStatus($transactionId);

echo '<pre>';
dd($status);
```