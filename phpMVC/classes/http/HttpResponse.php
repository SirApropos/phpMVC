<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/13/13
 * Time: 2:10 PM
 */
class HttpResponse
{
	/**
	 * @var View
	 */
	private $view;

	/**
	 * @var int
	 */
	private $responseCode = 200;

	/**
	 * @var HttpHeaders
	 */
	private $headers;

	function __construct(){
		$this->headers = new HttpHeaders();
	}

	/**
	 * @param \HttpHeaders $headers
	 */
	public function setHeaders(HttpHeaders $headers)
	{
		$this->headers = $headers;
	}

	/**
	 * @return \HttpHeaders
	 */
	public function getHeaders()
	{
		return $this->headers;
	}

	/**
	 * @param int $responseCode
	 */
	public function setResponseCode($responseCode)
	{
		$this->responseCode = $responseCode;
	}

	/**
	 * @return int
	 */
	public function getResponseCode()
	{
		return $this->responseCode;
	}

	/**
	 * @param View $view
	 */
	public function setView(View $view)
	{
		$this->view = $view;
	}

	/**
	 * @return View
	 */
	public function getView()
	{
		return $this->view;
	}

	/**
	 *  @return void
	 */
	public function send(){
		if($this->view){
			$this->view->prepareResponse($this);
			$this->sendHeaders();
			$this->view->render();
		}else{
			$this->sendHeaders();
		}
	}

	protected function sendHeaders(){
		http_response_code($this->responseCode);
		foreach($this->headers->getAllHeaders() as $key => $headers){
			if(!is_array($headers)){
				$headers = [$headers];
			}
			foreach($headers as $header){
				header($key.": ".$header);
			}
		}
	}
}
