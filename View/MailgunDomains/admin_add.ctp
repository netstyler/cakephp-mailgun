<?php
echo $this->Form->create();
echo $this->Form->input('name', array(
	'default' => 'test'
));
echo $this->Form->input('smtp_password', array(
	'default' => 'test'
));
echo $this->Form->input('spam_action', array(
	'type' => 'radio',
	'options' => $spamActions,
	'default' => 'disabled'
));
echo '<p>' . __d('mailgun', 'Disabled or tag Disable, no spam filtering will occur for inbound messages. Tag, messages will be tagged wtih a spam header.') . '</p>';

echo $this->Form->input('wildcard', array(
	'type' => 'radio',
	'options' => array(
		'true' => 'True',
		'false' => 'False',
	),
	'default' => 'false'
));
echo '<p>' . __d('mailgun', 'Determines whether the domain will accept email for sub-domains.') . '</p>';

echo $this->Form->submit(__d('mailgun', 'Submit'));
echo $this->Form->end();