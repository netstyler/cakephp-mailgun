<?php
/**
 * @copyright 2014, Falk Romano
 * @author Florian Krämer
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('MailgunAppModel', 'Mailgun.Model');

class MailgunDomain extends MailgunAppModel {

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
			)
		),
		'smtp_password' => array(
			'notEmpty' => array(
				'required' => false,
				'allowEmpty' => false,
				'rule' => array(
					'notEmpty'
				)
			)
		),
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
		),
	);

	public function delete($id = NULL, $cascade = true) {
		return parent::delete('/domains/' . $id);
	}

/**
 * getSpamActions
 *
 * @link http://documentation.mailgun.com/user_manual.html#um-spam-filter
 */
	public function getSpamActions() {
		return array(
			'disabled' => __d('mailgun', 'Disabled'),
			'tag' => __d('mailgun', 'Tag'),
		);
	}

	public function getList() {
		$result = $this->find('all');
		$domains = Hash::extract($result, $this->alias . '.{n}.name');
		return array_combine($domains, array_values($domains));
	}

	public function getMailgunEndpointUrl($method) {
		if ($method === MailgunSource::READ) {
			return 'domains';
		}
	}

}