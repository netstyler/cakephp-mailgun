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
		if (!empty($id)) {
			$this->id = $id;
		}

		$id = $this->id;

		$event = new CakeEvent('Model.beforeDelete', $this, array($cascade));
		list($event->break, $event->breakOn) = array(true, array(false, null));
		$this->getEventManager()->dispatch($event);
		if ($event->isStopped()) {
			return false;
		}

		$this->id = $id;

		if (!empty($this->belongsTo)) {
			foreach ($this->belongsTo as $assoc) {
				if (empty($assoc['counterCache'])) {
					continue;
				}

				$keys = $this->find('first', array(
					'fields' => $this->_collectForeignKeys(),
					'conditions' => array($this->alias . '.' . $this->primaryKey => $id),
					'recursive' => -1,
					'callbacks' => false
				));
				break;
			}
		}

		if (!$this->getDataSource()->delete($this, array($this->alias . '.' . $this->primaryKey => $id))) {
			return false;
		}

		if (!empty($keys[$this->alias])) {
			$this->updateCounterCache($keys[$this->alias]);
		}

		$this->getEventManager()->dispatch(new CakeEvent('Model.afterDelete', $this));
		$this->_clearCache();
		$this->id = false;

		return true;
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

}
