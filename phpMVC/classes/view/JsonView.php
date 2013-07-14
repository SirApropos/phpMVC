<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/13/13
 * Time: 3:47 PM
 */
class JsonView implements View
{
    use JsonUtils;

    private $obj;

    /**
     * @param $obj
     */
    function __construct($obj){
        $this->obj = $obj;
    }
    /**
     * @return void
     */
    public function render()
    {
        echo $this->toJson($this->obj);
    }
}
