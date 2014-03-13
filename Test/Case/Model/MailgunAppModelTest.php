<?php
App::uses('MailgunAppModel', 'Mailgun.Model');

class TestMailgunAppModel extends MailgunAppModel {

	public $useTable = false;
}

class MailgunAppModelTest extends CakeTestCase {

/**
 * setUp
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Model = ClassRegistry::init('MailgunAppModel');
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
	public function testValidateDomainName() {
		$data = array('name' => 'foobar');
		$result = $this->Model->validateDomainName($data);
		$this->assertFalse($result);

		$data = array('name' => 'foobar.de-');
		$result = $this->Model->validateDomainName($data);
		$this->assertFalse($result);

		$data = array('name' => '-foobar.de');
		$result = $this->Model->validateDomainName($data);
		$this->assertFalse($result);

		// Domains that must work:

		$data = array('name' => 'foo-bar.de');
		$result = $this->Model->validateDomainName($data);
		$this->assertTrue($result);

		$data = array('name' => 'foo-bar.museum');
		$result = $this->Model->validateDomainName($data);
		$this->assertTrue($result);
	}
}