<?php
App::uses('MailgunAppModel', 'Mailgun.Model');

class MailgunBounce extends MailgunAppModel {

	protected $_schema = array(
		'address' => array(
			'type' => 'string',
			'null' => false,
			'length' => 255,
		),
		'code' => array(
			'type' => 'integer',
			'null' => false,
			'length' => 4,
			'default' => 550
		),
		'error' => array(
			'type' => 'text',
			'null' => true,
		),
	);

	public $validate = array(
		'address' => 'email',
		'code' => array(
			'notEmpty' => array(
				'required' => false,
				'allowEmpty' => false,
				'rule' => array(
					'notEmpty'
				)
			)
		),
	);

}