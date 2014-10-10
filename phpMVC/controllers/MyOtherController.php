<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/12/13
 * Time: 11:49 PM
 */
class MyOtherController implements Controller
{
    private static $mapping = [
        "methods" => [
            "doTest" => [
                "path" => "/blah**",
                "allowed_methods" => [HttpMethod::GET, HttpMethod::POST]
            ]
        ]
    ];

    public function doTest(TestAccountModel $model){
	   return new JsonView($model);
    }

}
