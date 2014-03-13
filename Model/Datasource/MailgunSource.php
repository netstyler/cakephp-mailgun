<?php
/**
 * @copyright 2014, Falk Romano
 * @author Florian Krämer
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('DataSource', 'Model/Datasource');

class MailgunSource extends DataSource {

/**
 * Constants used to check what method is called so that the model can tell
 * the data source the right endpoint
 *
 * @see MailgunSource::getEndpointFromModel()
 */
	const CREATE = 'create';
	const READ = 'read';
	const UPDATE = 'update';
	const DELETE = 'delete';

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
 * Response body
 *
 * @var array
 */
	protected $_responseBody = array();

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
				$endpointUrl = $this->getEndpointFromModel($Model, 'create', $data);
			}

			try {
				$result = $this->Mailgun->post($endpointUrl, $data);
			} catch (\Exception $e) {
				$this->log($e->getMessage(), 'mailgun');
				$this->log($endpointUrl, 'mailgun');
				throw $e;
			}

			if ($result->http_response_code === 200) {
				return $this->responseToArray($result->http_response_body);
			}

			return false;
		} catch (\Mailgun\Connection\Exceptions\MissingEndpoint $e) {
			$this->log($e->getMessage(), 'mailgun');
			$this->log($endpointUrl, 'mailgun');
			throw $e;
		} catch (\Exception $e) {
			$this->log($e->getMessage(), 'mailgun');
			$this->log($endpointUrl, 'mailgun');
			throw $e;
		}
		return false;
	}

/**
 * Read
 *
 * @param Model $model The model being read.
 * @param array $queryData An array of query data used to find the data you want
 * @param integer $recursive Number of levels of association
 * @return mixed
 */
	public function read(Model $Model, $queryData = array(), $recursive = null) {
		$endpointUrl = $this->getEndpointFromModel($Model, self::READ, $queryData);
		try {
			$result = $this->Mailgun->get($endpointUrl);
		} catch (\Exception $e) {
			$this->log($e->getMessage(), 'mailgun');
			$this->log($endpointUrl, 'mailgun');
			throw $e;
		}

		if ($result->http_response_code === 200) {
			$result = $this->responseToArray($result->http_response_body);
			if (isset($result['items'])) {
				$data = array($Model->alias => $result['items']);
				if ($queryData['fields'] === 'COUNT') {
					return $result[0][0]['count'] = $result['total_count'];
				}
				return $data;
			}
			if (isset($result['domain'])) {
				if ($queryData['fields'] === 'COUNT') {
					return array(
						0 => array(
							0 => array(
								'count' => 1
							)
						)
					);
				}
				return array($Model->alias => $result['domain']);
			}
		}

		return false;
	}

/**
 * Issues a RESTful PUT to update a record
 *
 * @param Model $model
 * @param array $fields
 * @param array $values
 * @param mixed $conditions
 * @return array
 */
	public function update(Model $Model, $fields = array(), $values = null, $conditions = null) {
		$data = array_combine($fields, $values);
		$endpointUrl = $this->getEndpointFromModel($Model, 'create', array(
			'data' => $data,
			'fields' => $fields,
			'values' => $values,
			'conditions' => $conditions
		));

		try {
			$result = $this->Mailgun->push($endpointUrl, $data);
		} catch (\Exception $e) {
			$this->log($e->getMessage(), 'mailgun');
			$this->log($endpointUrl, 'mailgun');
			throw $e;
		}

		if ($result->http_response_code === 200) {
			return $this->responseToArray($result->http_response_body);
		}
		return false;
	}

/**
 * Generates and executes an SQL DELETE statement for given id/conditions on given model.
 *
 * @param Model $model
 * @param mixed $conditions
 * @return boolean Success
 */
	public function delete(Model $Model, $conditions = null) {
		$deleteUrl = $this->getEndpointFromModel($Model, self::DELETE, array(
			'conditions' => $conditions,
			'id' => $Model->id
		));

		try {
			$result = $this->Mailgun->delete($deleteUrl);
		} catch (\Exception $e) {
			$this->log($e->getMessage(), 'mailgun');
			$this->log($deleteUrl, 'mailgun');
			throw $e;
		}

		if ($result->http_response_code === 200) {
			$this->_responseBody = $this->responseToArray($result->http_response_body);
			return true;
		}
		return false;
	}

/**
 * getEndpointFromModel
 *
 * @throws RuntimeException
 * @param Model $Model
 * @param string $method API method name
 * @param array $data
 * @return string
 */
	public function getEndpointFromModel(Model $Model, $method, $data = array()) {
		$endpointUrl = false;
		if (method_exists($Model, 'getMailgunEndpointUrl')) {
			$endpointUrl = $Model->getMailgunEndpointUrl($method, $data);
		}
		if ($endpointUrl === false) {
			throw new RuntimeException(__d('mailgun', 'The model %s must implement a method getMailgunEndpointUrl()!', $Model->name));
		}
		return $endpointUrl;
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
 * Converts the json response with objects into an array
 *
 * @param StdClass $response
 * @return array
 */
	public function responseToArray($response) {
		return json_decode(json_encode($response), true);
	}

/**
 * Returns the response body of the API call as array
 *
 * @return array
 */
	public function getResponse() {
		return $this->_responseBody;
	}

}