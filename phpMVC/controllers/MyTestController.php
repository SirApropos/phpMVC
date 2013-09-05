<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/12/13
 * Time: 11:49 PM
 */
class MyTestController implements Controller
{
    private static $mapping = [
        "methods" => [
            "doTest" => [
                "path" => "/test**",
                "allowed_methods" => [HttpMethod::GET, HttpMethod::POST]
            ],
            "doOtherTest" => [
                "path" => "/blah/*/test",
                "allowed_methods" => [HttpMethod::GET, HttpMethod::POST],
            ],
            "someOtherTest" => [
                "path" => "/asd/{testing}/test",
                "allowed_methods" => [HttpMethod::GET, HttpMethod::POST],
            ],
            "yetAnotherTest" => [
                "path" => "/asd/{testing}/{myVar}/test",
                "allowed_methods" => [HttpMethod::GET, HttpMethod::POST],
            ]
        ],
        "fields" => [
            "request" => [
                "autowired" => true,
                "type" => "HttpRequest"
            ],
            "response" => [
                "autowired" => true,
                "type" => "HttpResponse"
            ]
        ]
    ];

    /**
     * @var HttpRequest
     */
    private $request;

    /**
     * @var HttpResponse
     */
    private $response;

    public function doTest(){
	    $ds = new MysqliDatasource("localhost","phpMVC","Pj42MPNADDb99auF","phpMVC");
	    $result = $ds->queryForObject("TestAccountModel","SELECT * FROM `accounts` WHERE `username`=:username AND `id`=:id", array("username" => "Caiyern", "id" => 1));
	    $result = $ds->query("SELECT * FROM `accounts` WHERE `id`=1");
	    print_r($result);
	    $myArr = array("username" => "Test");
	    $ds->insert("accounts", $myArr);
    }

    public function doOtherTest(){
//        $query = SimpleQueryFactory::getInstance()->createQuery();
//        $query->select("*")->from("myTable")->where("myColumn")->equals("blah")->also("myOtherColumn")->equals("test");
    }

    public function yetAnotherTest($testing, $myVar){
        echo $this->request->getPath();
        return new PageView("Test.psp", ["title" => "Test Page", "blah" => $myVar, "testing" => $testing, "arr" => ["Test","Hello","Blah"]]);
    }
}
