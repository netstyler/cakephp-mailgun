<?php
App::uses('MailgunAppModel', 'Mailgun.Model');

class MailgunDomain extends MailgunAppModel {

	public $useDbConfig = 'mailgun';

	public $useTable = false;

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
 * @link http://documentation.mailgun.com/user_manual.html#um-spam-filter
 */
	public function getSpamActions() {
		return array(
			'disabled' => __d('mailgun', 'Disabled'),
			'tag' => __d('mailgun', 'Tag'),
		);
	}
}