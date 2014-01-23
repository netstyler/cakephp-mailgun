<?php
App::uses('MailgunAppModel', 'Mailgun.Model');

class MailgunCampaign extends MailgunAppModel {

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
		'id' => array(
			'type' => 'integer',
			'null' => true,
			'length' => 11,
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
	);
}