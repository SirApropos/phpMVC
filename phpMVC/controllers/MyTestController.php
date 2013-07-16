<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/12/13
 * Time: 11:49 PM
 */
class MyTestController extends Controller
{
    private static $mapping = [
        "methods" => [
            "doTest" => [
                "path" => "/test**",
                "allowed_methods" => [HttpMethod::GET, HttpMethod::POST],
            ],
            "doOtherTest" => [
                "path" => "/blah**/test",
                "allowed_methods" => [HttpMethod::GET, HttpMethod::POST],
            ],
            "yetAnotherTest" => [
                "path" => "/asd/{testing}/test",
                "allowed_methods" => [HttpMethod::GET, HttpMethod::POST],
            ]
        ],
        "fields" => [
            "request" => [
                "autowired" => true,
                "type" => "HttpRequest"
            ]
        ]
    ];

    /**
     * @var HttpRequest
     */
    private $request;

    public function doTest(HttpResponse $response, TestModel $model){
        print_r($model);
        return "Hello world: ".$this->request->getPath();
    }

    public function doOtherTest(){
        return new XmlView(self::$mapping);
    }

    public function yetAnotherTest(){
        return new PageView("Test");
    }
}
