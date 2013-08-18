<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 8/17/13
 * Time: 11:39 PM
 */

class InvalidGrantException extends HttpException{
    public function __construct($message){
        parent::__construct($message);
        $this->setResponseCode(403);
    }
}