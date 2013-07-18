<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/17/13
 * Time: 12:03 AM
 */

class NotImplementedException extends HttpException{

    function __construct()
    {
        $this->setResponseCode(501);
        $this->message = "501 - Not Implemented";
    }
}