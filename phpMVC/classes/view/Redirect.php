<?php
/**
 * User: Apropos (sir.apropos.of.nothing@gmail.com)
 * Date: 10/26/2014
 * Time: 8:16 PM
 */

class Redirect implements  View{

	private $location;

	function __construct($location) {
		$this->location = $location;
	}
	/**
	 * @param HttpResponse $response
	 * @return mixed
	 */
	public function prepareResponse(HttpResponse $response) {
		$response->setResponseCode(302);
		$response->getHeaders()->addValue("Location", $this->location);
	}

	/**
	 * @return mixed
	 */
	public function render() {}

} 