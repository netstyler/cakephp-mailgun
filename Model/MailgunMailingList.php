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
		),
		'access_level' => array(
			'inList' => array(
				'required' => true,
				'allowEmpty' => false,
				'rule' => array(
					'inList' => array(
						'readonly',
						'members',
						'everyone'
					)
				)
			)
		)
	);

/**
 * getEndpointFromModel
 *
 * @param string $method
 * @param array $data
 * @return string
 */
	public function getMailgunEndpointUrl($method, $data = array()) {
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
