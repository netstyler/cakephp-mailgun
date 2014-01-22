<?php
class MailgunDomainsController extends MailgunAppController {

/**
 * Index
 *
 * @return void
 */
	public function admin_index() {
		$domains = $this->MailgunDomain->find('all');
		$this->set('domains', $domains);
	}

/**
 * Add
 *
 * @return void
 */
	public function admin_add() {
		if (!$this->request->is('get')) {
			$this->MailgunDomain->create();
			$result = $this->MailgunDomain->save($this->request->data, array('callbacks' => false));
			if (!$result) {
				debug('Failed!');
				debug($this->MailgunDomain->validationErrors);
			} else {
				debug($result);
				$this->Session->setFlash(__('saved'));
			}
		}
		$this->set('spamActions', $this->MailgunDomain->getSpamActions());
	}

	public function admin_remove() {
		$this->MailgunDomain->delete();
	}

}