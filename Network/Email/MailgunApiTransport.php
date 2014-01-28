<?php
/**
 * @copyright 2014, Falk Romano
 * @author Florian Krämer
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('AbstractTransport', 'Network/Email');

class MailgunApiTransport extends AbstractTransport {

/**
 * CakeEmail
 *
 * @var CakeEmail
 */
	protected $_cakeEmail;

/**
 * Configurations
 *
 * @var array
 */
	protected $_config = array(
		'model' => 'Mailgun.MailgunMessage',
		'testmode' => true,
		'domain' => null,
	);

/**
 * Send mail
 *
 * @param CakeEmail $email
 * @return array
 */
	public function send(CakeEmail $email) {
		$this->config($email->config());
		$this->_cakeEmail = $email;
		$this->_send();
	}

/**
 * Formats the Email addresses
 *
 * @param string|array $address
 * @return array
 */
	protected function _formatAddress($address) {
		if (is_string($address)) {
			return $address;
		}

		$return = array();
		foreach ($address as $email => $alias) {
			if ($email === $alias) {
				$return[] = $email;
			} else {
				$encoded = $alias;
				/*
				$encoded = $this->_encode($alias);
				if ($encoded === $alias && preg_match('/[^a-z0-9 ]/i', $encoded)) {
					$encoded = '"' . str_replace('"', '\"', $encoded) . '"';
				}
				*/
				$return[] = sprintf('%s <%s>', $encoded, $email);
			}
		}
		return implode(',', $return);
	}

/**
 * Send
 *
 * @return boolean
 */
	protected function _send() {
		if (empty($this->_config['domain'])) {
			throw new RuntimeException(__d('mailgun', 'Mailgun API requires the config option domain to be set!'));
		}

		$Model = ClassRegistry::init($this->_config['model']);

		$message = array(
			$Model->alias => array(
				'domain' => $this->_cakeEmail->domain(),
				'from' => $this->_formatAddress($this->_cakeEmail->from()),
				'to' => $this->_formatAddress($this->_cakeEmail->to()),
				'cc' => $this->_cakeEmail->cc(),
				'bcc' => $this->_cakeEmail->bcc(),
				'subject' => $this->_cakeEmail->subject(),
				//'o:testmode' => $this->_config['testmode']
			)
		);

		$format = $this->_cakeEmail->emailFormat();
		if ($format === CakeEmail::MESSAGE_HTML) {
			$message[$Model->alias]['html'] = $this->_cakeEmail->message(CakeEmail::MESSAGE_HTML);
		} elseif ($format === CakeEmail::MESSAGE_TEXT) {
			$message[$Model->alias]['text'] = $this->_cakeEmail->message(CakeEmail::MESSAGE_TEXT);
		} else {
			$message[$Model->alias]['html'] = $this->_cakeEmail->message(CakeEmail::MESSAGE_HTML);
			$message[$Model->alias]['text'] = $this->_cakeEmail->message(CakeEmail::MESSAGE_TEXT);
		}

		$attachments = $this->_cakeEmail->attachments();
		if (!empty($attachments)) {
			foreach ($attachments as $attachment) {
				$message[$Model->alias]['attachment'][] = '@' . $attachment['file'];
			}
		}

		$message[$Model->alias]['_endpoint'] = $this->_cakeEmail->domain() . '/messages';
		return $Model->save($message);
	}

}