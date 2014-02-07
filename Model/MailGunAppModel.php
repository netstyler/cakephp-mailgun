<?php
App::uses('AppModel', 'Model');

class MailgunAppModel extends AppModel {

/**
 * Database config
 *
 * @var string
 */
	public $useDbConfig = 'mailgun';

/**
 * Endpoint URLs for different API methods the model can call
 *
 * @var array
 */
	public $mailgunEndpointUrls = array();

/**
 *
 */
	protected $_mailgunEndpointUrl = null;

/**
 * Table
 *
 * @var boolean|string
 */
	public $useTable = false;

/**
 * getMailgunEndpointUrl
 *
 * @param string $method
 * @return string
 */
	public function getMailgunEndpointUrl($method) {
		if (isset($this->mailgunEndpointUrls[$method])) {
			return $this->mailgunEndpointUrls[$method];
		}
		if (!empty($this->_mailgunEndpointUrl)) {
			return $this->_mailgunEndpointUrl;
		}
		return false;
	}

/**
 * setMailgunEndpointUrl
 *
 * @param string $url
 * @return void
 */
	public function setMailgunEndpointUrl($url) {
		$this->_mailgunEndpointUrl = $url;
	}

/**
 *
 */
	public function responseToArray($result) {
		return json_decode(json_encode($result), true);
	}

}
