<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/13/13
 * Time: 3:53 PM
 */
class BasicView implements View
{
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
        echo "".$this->obj;
    }

    /**
     * @param HttpResponse $response
     * @return mixed
     */
    public function prepareResponse(HttpResponse $response)
    {
        // TODO: Implement prepareResponse() method.
    }


}
