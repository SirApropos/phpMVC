<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/13/13
 * Time: 3:47 PM
 */
class JsonView implements View
{
	private $response;

	private $config;

	/**
	 * @param $response
	 */
	function __construct($response, MappingConfiguration $config=null){
		$this->response = $response;
		$this->config = $config;
	}

	/**
	 * @return mixed
	 */
	public function render()
	{
		/**
		 * @var JsonMapper $mapper;
		 */
		$mapper = IOCContainer::getInstance()->resolve("JsonMapper");
		echo $mapper->write($this->response, $this->config);
	}

	/**
	 * @param HttpResponse $response
	 * @return mixed
	 */
	public function prepareResponse(HttpResponse $response)
	{
		if(is_null($response->getHeaders()->getContentType())){
			$response->getHeaders()->setContentType(ContentType::APPLICATION_JSON);
		}
	}

}
