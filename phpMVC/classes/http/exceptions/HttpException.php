<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/13/13
 * Time: 3:49 PM
 */
class HttpException extends MVCException
{
    private $responseCode = 500;

    protected  function setResponseCode($responseCode)
    {
        $this->responseCode = $responseCode;
    }

    public function getResponseCode()
    {
        return $this->responseCode;
    }
}
