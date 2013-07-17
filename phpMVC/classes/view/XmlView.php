<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/15/13
 * Time: 12:55 AM
 */

class XmlView implements View{

    private $obj;

    function __construct($obj)
    {
        $this->obj = $obj;
    }


    /**
     * @return mixed
     */
    public function render()
    {
        echo XmlUtils::toXml($this->obj);
    }

    /**
     * @param HttpResponse $response
     * @return mixed
     */
    public function prepareResponse(HttpResponse $response)
    {
        if(is_null($response->getHeaders()->getContentType())){
            $response->getHeaders()->setContentType(ContentType::TEXT_XML);
        }
    }
}