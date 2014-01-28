<?php
/**
 * @copyright 2014, Falk Romano
 * @author Florian Krämer
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('DataSource', 'Model/Datasource');

class MailgunSource extends DataSource {

/**
 * Our default config options. These options will be customized in our
 * ``app/Config/database.php`` and will be merged in the ``__construct()``.
 */
	public $config = array(
		'apiKey' => '',
	);

/**
 * If we want to create() or update() we need to specify the fields
 * available. We use the same array keys as we do with CakeSchema, eg.
 * fixtures and schema migrations.
 */
	protected $_schema = array();

/**
 * listSources() is for caching. You'll likely want to implement caching in
 * your own way with a custom datasource. So just ``return null``.
 */
	public function listSources($data = null) {
		return null;
	}

/**
 * describe() tells the model your schema for ``Model::save()``.
 *
 * You may want a different schema for each model but still use a single
 * datasource. If this is your case then set a ``schema`` property on your
 * models and simply return ``$model->schema`` here instead.
 */
	public function describe($model) {
		return $model->schema();
	}

/**
 * calculate() is for determining how we will count the records and is
 * required to get ``update()`` and ``delete()`` to work.
 *
 * We don't count the records here but return a string to be passed to
 * ``read()`` which will do the actual counting. The easiest way is to just
 * return the string 'COUNT' and check for it in ``read()`` where
 * ``$data['fields'] === 'COUNT'``.
 */
	public function calculate(Model $model, $func, $params = array()) {
		return 'COUNT';
	}

/**
 * Create our HttpSocket and handle any config tweaks.
 *
 * @param array $config
 */
	public function __construct($config) {
		parent::__construct($config);
		$this->Mailgun = $this->getMailgunInstance($config);
		$this->domain = $config['host'];
	}

/**
 * getMailgunInstance
 *
 * @throws RuntimeException
 * @param array $config
 * @return \Mailgun\Mailgun
 */
	public function getMailgunInstance($config = array()) {
		if (empty($config['apiKey'])) {
			throw new RuntimeException(__d('mailgun', 'You have to pass your API key in the password field of the datasource config!'));
		}
		return new \Mailgun\Mailgun($config['apiKey']);
	}

/**
 * Create
 *
 * @param Model $Model
 * @param array $fields
 * @param array $values
 * @throws Exception
 * @throws Mailgun\Connection\Exceptions\MissingEndpoint
 * @return mixed
 */
	public function create(Model $Model, $fields = null, $values = null) {
		try {
			$data = array_combine($fields, $values);
			if (isset($data['_endpoint'])) {
				$endpointUrl = $data['_endpoint'];
			} else {
				$endpointUrl = $this->getEndpointFromModel($Model, 'create');
			}
			//debug($endpointUrl);
			//debug($data);
			//$result = $this->Mailgun->sendMessage($endpointUrl, $data);
			$result = $this->Mailgun->post($endpointUrl, $data);
			debug($result);
			if ($result->http_response_code === 200) {
				return (array)$result->http_response_body;
			}
			return false;
		} catch (\Mailgun\Connection\Exceptions\MissingEndpoint $e) {
			$this->log($e->getMessage(), 'mailgun');
			throw $e;
		} catch (\Exception $e) {
			$this->log($e->getMessage(), 'mailgun');
			throw $e;
		}
		return false;
	}

/**
 * getEndpointFromModel
 *
 * @throws RuntimeException
 * @param Model $Model
 * @param string $method API method name
 * @return string
 */
	public function getEndpointFromModel(Model $Model, $method) {
		$endpointUrl = false;
		if (method_exists($Model, 'getMailgunEndpointUrl')) {
			$endpointUrl = $Model->getMailgunEndpointUrl($method);
		}
		if ($endpointUrl === false) {
			throw new RuntimeException(__d('mailgun', 'The model %s must implement a method getEndpointFromModel()!', $Model->name));
		}
		return $endpointUrl;
	}

}