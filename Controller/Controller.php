<?php
namespace Controller;

use Library\Auth;
use Library\Redirect;

class Controller{
    public $defaultAction;
    public $currentAction;
    public $output = array();
    public $requireLogin = true;
    public $parameters = [];
    public function __construct(){
    }
    public function run(){
        $this->checkRequireLogin();
        $action = $this->currentAction !='' ? $this->currentAction : $this->defaultAction;
        if(method_exists($this,$action)){
            $this->$action();
        }
        else{
            Redirect::redirect404();
        }
        $this->returnOutput();
    }
    
    private function checkRequireLogin(){
        if($this->requireLogin && (!$this->checkLoggedIn())){
            print 'Bạn không có quyền truy cập!';
            exit;
        }
    }
    private function checkLoggedIn(){
        $token = Auth::getBearerToken();
        if(!empty($token)){
            $dataLogin = Auth::getJwtData($token);
            if(!empty($dataLogin)){
                return true;
            }
        }
        return false;
    }
   
    public function checkParameter($listParameters){
        if(is_array($listParameters) && count($listParameters)>0 ){
            foreach($listParameters as $parameter){
                if(!isset($this->parameters[$parameter])){
                    $this->output = [
                        'status' => STATUS_BAD_REQUEST,
                        'message'=> \Library\Message::getStatusResponse(STATUS_BAD_REQUEST)
                    ];
                    return false;
                }
            }
        }
        return true;
    }
    private function returnOutput(){
        header('Content-Type: application/json');
        print json_encode($this->output);
    }
    public function result($statusCode, $data = array(), $message = false){
        $this->output['status'] =  $statusCode;
        if($message == false){
            $message = \Library\Message::getStatusResponse($statusCode);
        }
        $this->output['message'] = $message;
        $this->output['data'] = $data;
    }
}