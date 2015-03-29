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

	function __construct($location) {
		$this->location = $location;
	}

	/**
	 * @param HttpResponse $response
	 * @return mixed
	 */
	public function prepareResponse(HttpResponse $response) {
		$response->setHeader("Location",$this->location );
		$response->setResponseCode(301);
	}

	/**
	 * @return mixed
	 */
	public function render(){}

} 