<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/13/13
 * Time: 3:45 PM
 */
interface View
{
	/**
	 * @param HttpResponse $response
	 * @return mixed
	 */
	public function prepareResponse(HttpResponse $response);

	/**
	 * @return mixed
	 */
	public function render();
}
