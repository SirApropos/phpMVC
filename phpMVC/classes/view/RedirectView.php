<?php
/**
 * User: Apropos (sir.apropos.of.nothing@gmail.com)
 * Date: 3/29/2015
 * Time: 11:04 AM
 */

class RedirectView implements View{
	/**
	 * @var String
	 */
	private $location;

	/**
	 * @var int
	 */
	private $status;

	function __construct($location, $status=307) {
		$this->location = $location;
		$this->status = $status;
	}

	/**
	 * @param HttpResponse $response
	 * @return mixed
	 */
	public function prepareResponse(HttpResponse $response) {
		$response->setHeader("Location",$this->location );
		$response->setResponseCode($this->status);
	}

	/**
	 * @return mixed
	 */
	public function render(){}

} 