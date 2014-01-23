<?php
App::uses('MailgunAppModel', 'Mailgun.Model');

class MailgunComplaint extends MailgunAppModel {

	protected $_schema = array(
		'address' => array(
			'type' => 'string',
			'null' => false,
			'length' => 255,
		),
	);

	public $validate = array(
		'address' => 'email',
	);

}