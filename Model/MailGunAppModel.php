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
 * Removes record for given ID. If no ID is given, the current ID is used. Returns true on success.
 *
 * @param integer|string $id ID of record to delete
 * @param boolean $cascade Set to true to delete records that depend on this record
 * @return boolean True on success
 * @link http://book.cakephp.org/2.0/en/models/deleting-data.html
 */
	public function delete($id = null, $cascade = true) {
		return parent::delete($id, $cascade = false);
	}

/**
 * Cascades model deletes through HABTM join keys.
 *
 * Overriden because these API models can't work with HABTM
 *
 * @param string $id ID of record that was deleted
 * @return void
 */
	protected function _deleteLinks($id) {
		return;
	}

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

/**
 * Validates a domain name
 *
 * @link http://www.php.net/preg_match
 * @param array $ccheck
 * @return boolean
 */
	public function validDomainName($check) {
		$value = array_values($check);
		$value = $value[0];

		$result = preg_match('/^[a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9]\.[a-zA-Z]{2,}$/', $value);
		if (is_int($result)) {
			return (bool)$result;
		}
		return false;
	}

}
