<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Nathan
 * Date: 7/12/13
 * Time: 11:41 PM
 * To change this template use File | Settings | File Templates.
 */
interface ControllerFactory
{
	function getController(HttpRequest $request);
}
