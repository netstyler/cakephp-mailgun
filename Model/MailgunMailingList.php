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
 * @return array
 */
	public function getMailgunEndpointUrl($method) {
		if ($method === MailgunSource::READ) {
			return 'lists';
		}
	}

/**
 * Add
 *
 * @param string $address
 * @param string $description
 * @return boolean
 */
	public function add($address, $description) {
		$this->create();
		return $this->save(array(
			$this->alias => array(
				'address' => $address,
				'description' => $description
			)
		));
	}

}