<?php
echo $this->Form->create();
echo $this->Form->input('from');
echo $this->Form->input('to');
echo $this->Form->input('subject', array());
echo $this->Form->input('message');
echo $this->Form->input('testmode', array(
	'type' => 'radio',
	'options' => array(
		'true' => 'True',
		'false' => 'False',
	),
	'default' => 'false'
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