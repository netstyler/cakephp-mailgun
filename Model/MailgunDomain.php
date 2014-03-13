<?php
/**
 * @copyright 2014, Falk Romano
 * @author Florian Krämer
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('MailgunAppModel', 'Mailgun.Model');

class MailgunDomain extends MailgunAppModel {

/**
 * Primary Key
 *
 * @param string
 */
	public $primaryKey = 'name';

/**
 * Schema
 *
 * @var array
 */
	protected $_schema = array(
		'name' => array(
			'type' => 'string',
			'null' => false,
			'length' => 255,
		),
		'smtp_password' => array(
			'type' => 'string',
			'null' => false,
			'length' => 255,
		),
		'spam_action' => array(
			'type' => 'string',
			'null' => true,
			'length' => 32,
		),
		'wildcard' => array(
			'type' => 'string',
			'null' => true,
			'length' => 32,
		),
	);

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'notEmpty' => array(
				'required' => false,
				'allowEmpty' => false,
				'rule' => array(
					'notEmpty'
				)
			),
			'validDomain' => array(
				'required' => false,
				'allowEmpty' => false,
				'rule' => array(
					'validateDomainName'
				),
				'message' => 'Invalid domain name'
			),
		),
		/*
		'smtp_password' => array(
			'notEmpty' => array(
				'required' => false,
				'allowEmpty' => false,
				'rule' => array(
					'notEmpty'
				)
			)
		),
		*/
		'spam_action' => array(
			'validAction' => array(
				'required' => false,
				'allowEmpty' => false,
				'rule' => array(
					'inList',
					array(
						'disabled',
						'tag'
					)
				)
			)
		),
		'wildcard' => array(
			'validAction' => array(
				'required' => false,
				'allowEmpty' => false,
				'rule' => array(
					'inList',
					array(
						'true',
						'false'
					)
				)
			)
		)
	);

/**
 * getSpamActions
 *
 * @link http://documentation.mailgun.com/user_manual.html#um-spam-filter
 * @return array
 */
	public function getSpamActions() {
		return array(
			'disabled' => __d('mailgun', 'Disabled'),
			'tag' => __d('mailgun', 'Tag'),
		);
	}

/**
 * getList
 *
 * @return array
 */
	public function getList() {
		$result = $this->find('all');
		$domains = Hash::extract($result, $this->alias . '.{n}.name');
		return array_combine($domains, array_values($domains));
	}

/**
 * getMailgunEndpointUrl
 *
 * @param string CRUD method name
 * @param array $data
 * @return string
 */
	public function getMailgunEndpointUrl($method, $data = array()) {
		if ($method === MailgunSource::CREATE) {
			return 'domains';
		}
		if ($method === MailgunSource::READ) {
			if (isset($data['conditions'][$this->alias . '.' . $this->primaryKey])) {
				return 'domains/' . $data['conditions'][$this->alias . '.' . $this->primaryKey];
			}
			return 'domains';
		}
		if ($method === MailgunSource::UPDATE) {
			return 'domains/' . $this->id;
		}
		if ($method === MailgunSource::DELETE) {
			return 'domains/' . $this->id;
		}
	}

}
