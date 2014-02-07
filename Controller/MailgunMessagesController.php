<?php
App::uses('MailgunAppController', 'Mailgun.Controller');
App::uses('CakeEmail', 'Network/Email');

class MailgunMessagesController extends MailgunAppController {

	public function admin_index() {
		try {

		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage());
		}
	}

	protected function _getMailInstance() {
		return new CakeEmail(array(
			'transport' => 'Mailgun.MailgunApi',
			'domain' => 'mg.world-architects.com',
			'testmode' => false
		));
	}
}