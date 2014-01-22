<?php
App::uses('AppModel', 'Model');

class MailgunAppModel extends AppModel {

/**
 * Database config
 *
 * @var string
 */
	public $useDbConfig = 'mailgun';

/**
 * Table
 *
 * @var boolean|string
 */
	public $useTable = false;

}
