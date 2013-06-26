<?php namespace Vjanssens\LaravelSisow;

use Illuminate\Config\Repository;

class Sisow 
{

	/**
    * Illuminate config repository.
    *
    * @var Illuminate\Config\Repository
    */
    protected $config;

    /**
    * Unique MerchantID. Must be set in config.php
    *
    * @var string
    */
	private $merchantId;

	/**
    * Unique MerchantKey. Must be set in config.php
    * 
    * @var string
    */
	private $merchantKey;
	
	/**
    * Instantiate API
    */
	protected $api;

	/**
	* Testmode
	*
	* @var boolean
	*/
	protected $testmode;

	/**
    * Create a new instance of the SisowAPI. Use MerchantID and MerchantKey stored in config file.
    * 
    * @var Illuminate\Config\Repository
    */
	public function __construct( Repository $config ) {

		$this->config = $config;

		$this->merchantId  	= $this->config->get('laravel-sisow::merchantid');
		$this->merchantKey 	= $this->config->get('laravel-sisow::merchantkey');
		$this->testmode 	= $this->config->get('laravel-sisow::testmode');

		$this->api = new SisowAPI($this->merchantId, $this->merchantKey);

	}

	/**
	 * Fetch banks. Will automatically get TEST bank if testmode has been set to TRUE in config.php
	 * May be overwritten with getBanks(true).
	 *
	 * @param  boolean  $testmode
	 * @return string
	 */
	public function getBanks( $testmode = null ) {

		if(isset($testmode)) {
			$this->testmode = $testmode;
		}

		$this->api->DirectoryRequest($output, true, $this->testmode);

		return $output;

	}

	/**
	 * Request a payment URL
	 *
	 * TODO: Allow all Sisow payment options
	 *
	 * @param  array  $args
	 * @return array
	 */
	public function getPaymentURL( $args = NULL ) {

		$this->api->purchaseId        = $args['PurchaseID'];
		$this->api->amount            = $args['Amount'];
		$this->api->issuerId          = $args['IssuerID'];

		$this->api->testmode          = $this->testmode;
		$this->api->description       = (isset($args['Description']) 	? $args['Description'] 		: $this->config->get('laravel-sisow::default.description'));
		$this->api->notifyUrl         = (isset($args['NotifyURL']) 		? $args['NotifyURL'] 		: $this->config->get('laravel-sisow::default.notifyurl'));
		$this->api->returnUrl         = (isset($args['ReturnURL']) 		? $args['ReturnURL'] 		: $this->config->get('laravel-sisow::default.returnurl'));
		$this->api->cancelUrl         = (isset($args['CancelURL']) 		? $args['CancelURL'] 		: $this->config->get('laravel-sisow::default.cancelurl'));

		$this->api->payment           = 'ideal'; 

		if ( $this->api->TransactionRequest() < 0 ) {
			$return = array(
				'status'  => 'error',
				'code'    => $this->api->errorCode,
				'message' => $this->api->errorMessage
				);
		} else {
			$return = array (
				'status' => 'success',
				'url'    => $this->api->issuerUrl
				);
		}

		return $return;

	}

	/**
	 * Get status of transaction. Sisow will return a transaction ID after transaction via getPaymentURL().
	 *
	 * @param  string  $transactionId
	 * @return array
	 */
	public function getStatus( $transactionId = NULL ) {

		if($transactionId == NULL) {
			return $status = array(
				'status'			=> 'Failure',
				'message'			=> 'No transaction ID',
			);
		}

		$this->api->StatusRequest($transactionId);

		if ($this->api->status == 'Success') {
			$status = array(
				'status'			=> $this->api->status,
				'timeStamp'			=> $this->api->timeStamp,
				'amount '			=> $this->api->amount,
				'consumerAccount'	=> $this->api->consumerAccount,
				'consumerName'		=> $this->api->consumerName,
				'consumerCity'		=> $this->api->consumerCity,
				'purchaseId'		=> $this->api->purchaseId,
				'description'		=> $this->api->description,
				'entranceCode'		=> $this->api->entranceCode,
				);
		} else {
			$status = array(
				'status'			=> $this->api->status,
				'timeStamp'			=> $this->api->timeStamp,
				'purchaseId'		=> $this->api->purchaseId,
				);
		}

		return $status;
		
	}
}