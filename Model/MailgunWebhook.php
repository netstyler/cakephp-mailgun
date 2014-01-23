<?php
App::uses('MailgunAppModel', 'Mailgun.Model');

class MailgunWebhook extends MailgunAppModel {

	protected $_schema = array(
		'id' => array(
			'type' => 'string',
			'null' => false,
			'length' => 255,
		),
		'url' => array(
			'type' => 'string',
			'null' => false,
			'length' => 255,
		),
	);

	public $validate = array(
		'id' => array(
			'notEmpty' => array(
				'required' => false,
				'allowEmpty' => false,
				'rule' => array(
					'notEmpty'
				)
			)
		),
		'url' => array(
			'rule' => array('url', true)
		)
	);
}