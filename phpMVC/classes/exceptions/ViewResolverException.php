<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/13/13
 * Time: 4:21 PM
 */
class ViewResolverException extends MVCException
{
	/**
	 * @param string $message
	 */
	public function __construct($message){
		$this->message = $message;
	}
}
