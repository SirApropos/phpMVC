<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/13/13
 * Time: 1:49 PM
 */
class HttpMethodNotAllowedException extends HttpException
{
    function __construct()
    {
        $this->setResponseCode(405);
        $this->message = "Method Not Allowed";
    }}
