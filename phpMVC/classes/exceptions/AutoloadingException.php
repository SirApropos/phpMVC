<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/13/13
 * Time: 12:06 AM
 */
include "MVCException.php";
class AutoloadingException extends MVCException
{
    public function AutoloadingException($className, $path=null){
        $message = "Could not load class: $className";;
        if($path){
            $message.=" in: $path";
        }
        $this->message = $message;
    }
}
