<?php
App::uses('MailgunDomain', 'Mailgun.Model');
App::uses('MailgunSource', 'Mailgun.Model/Datasource');

class MailgunAppModelTest extends CakeTestCase {

/**
 * setUp
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Model = ClassRegistry::init('Mailgun.MailgunDomain');
	}

/**
 * tearDown
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();
		unset($this->Model);
		ClassRegistry::flush();
	}

/**
 * testValidateDomainName
 *
 * @return void
 */
	public function testGetMailgunEndpointUrl() {
		// test CREATE
		$result = $this->Model->getMailgunEndpointUrl(MailgunSource::CREATE, array());
		$this->assertEquals($result, 'domains');

		// test READ (single)
		$result = $this->Model->getMailgunEndpointUrl(MailgunSource::READ, array(
			'conditions' => array(
				'MailgunDomain.name' => 'test.com'
			)
		));
		$this->assertEquals($result, 'domains/test.com');

		// test READ (many)
		$result = $this->Model->getMailgunEndpointUrl(MailgunSource::READ, array());
		$this->assertEquals($result, 'domains');

		// test UPDATE
		$this->Model->id = 'test.com';
		$result = $this->Model->getMailgunEndpointUrl(MailgunSource::DELETE, array());
		$this->assertEquals($result, 'domains/test.com');

		// test DELETE
		$this->Model->id = 'test.com';
		$result = $this->Model->getMailgunEndpointUrl(MailgunSource::DELETE, array());
		$this->assertEquals($result, 'domains/test.com');
	}

}