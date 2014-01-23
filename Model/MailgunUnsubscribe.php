<?php
App::uses('MailgunAppModel', 'Mailgun.Model');

class MailgunUnsubscribe extends MailgunAppModel {

	protected $_schema = array(
		'address' => array(
			'type' => 'string',
			'null' => false,
			'length' => 255,
		),
		'tag' => array(
			'type' => 'string',
			'null' => false,
			'length' => 255,
		),
	);

	public $validate = array(
		'address' => 'email',
	);

}