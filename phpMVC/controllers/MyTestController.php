<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/12/13
 * Time: 11:49 PM
 */
class MyTestController extends Controller
{
    private static $mapping = [
        [
            "path" => "/test**",
            "allowed_methods" => [HttpMethod::GET, HttpMethod::POST],
            "method" => [
                "name" => "doTest"
            ]
        ],
        [
            "path" => "/blah**/test",
            "allowed_methods" => [HttpMethod::GET, HttpMethod::POST],
            "method" => [
                "name" => "doOtherTest"
            ]
        ],
        [
            "path" => "/asd/{testing}/test",
            "allowed_methods" => [HttpMethod::GET, HttpMethod::POST],
            "method" => [
                "name" => "yetAnotherTest"
            ]
        ],
    ];

    public function doTest(HttpRequest $request, HttpResponse $response, TestModel $model){
        print_r($model);
        return "Hello world: ".$request->getPath();
    }

    public function doOtherTest(){
        return new XmlView(self::$mapping);
    }

    public function yetAnotherTest(){
        return new PageView("Test");
    }
}
