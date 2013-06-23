<?php namespace Vjanssens\LaravelSisow;

class Sisow {

	private $merchantId  = '';
	private $merchantKey = '';
	
	protected $api;

	public function __construct() {

		$this->api = new SisowAPI($this->merchantId, $this->merchantKey);

	}

	/**
	 * Fetch banks
	 *
	 * @param  boolean  $testmode
	 * @return string
	 */
	public function getBanks($testmode = false) {

		$this->api->DirectoryRequest($output, true, $testmode);
		return $output;

	}

	/**
	 * Request a payment URL
	 *
	 * TODO: Set default values in config files
	 *
	 * @param  array  $args
	 * @return array
	 */
	public function getPaymentURL( $args = NULL ) {

        $this->api->purchaseId        = $args['PurchaseID'];
        $this->api->amount            = $args['Amount'];
        $this->api->issuerId          = $args['IssuerID'];
		$this->api->testmode          = $args['Testmode'];
		$this->api->description       = $args['Description'];
		$this->api->notifyUrl         = $args['NotifyURL'];
		$this->api->returnUrl         = $args['ReturnURL'];
		$this->api->cancelUrl         = $args['CancelURL'];
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
	 * Get status of transaction. Transaction ID is needed.
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