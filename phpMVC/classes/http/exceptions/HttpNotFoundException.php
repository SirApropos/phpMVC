<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/13/13
 * Time: 12:35 AM
 */
class HttpNotFoundException extends HttpException
{
	public function HttpNotFoundException(){
		parent::__construct("Not Found", 404);
	}
}
