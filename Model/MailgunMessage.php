<?php
/**
 * @copyright 2014, Falk Romano
 * @author Florian Krämer
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('MailgunAppModel', 'Mailgun.Model');

/**
 * Mailgun Message Model
 */
class MailgunMessage extends MailgunAppModel {

/**
 * Schema
 *
 * @var array
 */
	protected $_schema = array(
		'_endpoint' => array(
			'type' => 'string',
			'length' => 255,
			'null' => true
		),
		'domain' => array(
			'type' => 'string',
			'null' => false,
			'length' => 255,
		),
		'from' => array(
			'type' => 'string',
			'null' => false,
			'length' => 255,
		),
		'to' => array(
			'type' => 'string',
			'null' => false,
			'length' => 255,
		),
		'subject' => array(
			'type' => 'string',
			'null' => false,
			'length' => 255,
		),
		'text' => array(
			'type' => 'text',
			'null' => true,
		),
		'html' => array(
			'type' => 'text',
			'null' => true,
		),
		'attachment' => array(
			'type' => 'boolean',
			'null' => true,
			'default' => null
		),
		'o:tracking' => array(
			'type' => 'boolean',
			'null' => false,
			'default' => 0,
		),
		'o:testmode' => array(
			'type' => 'boolean',
			'null' => false,
			'default' => 0,
		),
	);

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'domain' => array(
			'notEmpty' => array(
				'required' => true,
				'allowEmpty' => false,
				'rule' => array(
					'notEmpty'
				)
			)
		),
		'from' => array(
			'notEmpty' => array(
				'required' => true,
				'allowEmpty' => false,
				'rule' => array(
					'notEmpty'
				)
			)
		),
		'to' => array(
			'notEmpty' => array(
				'required' => true,
				'allowEmpty' => false,
				'rule' => array(
					'notEmpty'
				)
			)
		),
		'subject' => array(
			'notEmpty' => array(
				'required' => true,
				'allowEmpty' => true,
				'rule' => array(
					'notEmpty'
				)
			)
		),
	);

	public function beforeSave($options = array()) {
		if (isset($this->data[$this->alias]['testmode'])) {
			$this->data[$this->alias]['o:testmode'] = 'testmode';
		}
		//if (isset($this->data[$this->alias]['domain'])) {
			//$this->data[$this->alias]['_endpoint'] = $this->data[$this->alias]['domain'];
		//}
		return true;
	}

}