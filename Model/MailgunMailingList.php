<?php
/**
 * @copyright 2014, Falk Romano
 * @author Florian Krämer
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('MailgunAppModel', 'Mailgun.Model');

class MailgunMailingList extends MailgunAppModel {

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
		'address' => array(
			'type' => 'string',
			'null' => false,
			'length' => 255,
		),
		'description' => array(
			'type' => 'string',
			'null' => false,
			'length' => 255,
		),
	);

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'address' => array(
			'notEmpty' => array(
				'required' => true,
				'allowEmpty' => false,
				'rule' => array(
					'notEmpty'
				)
			),
			'email' => array(
				'required' => true,
				'allowEmpty' => false,
				'rule' => array(
					'email'
				)
			)
		),
		'description' => array(
			'notEmpty' => array(
				'required' => true,
				'allowEmpty' => false,
				'rule' => array(
					'notEmpty'
				)
			)
		)
	);

/**
 * getEndpointFromModel
 *
 * @param string $method
 * @return string
 */
	public function getMailgunEndpointUrl($method) {
		if ($method === MailgunSource::CREATE) {
			return 'lists';
		}
		if ($method === MailgunSource::READ) {
			return 'lists';
		}
		if ($method === MailgunSource::UPDATE) {
			return false;
		}
		if ($method === MailgunSource::DELETE) {
			return false;
		}
	}

}