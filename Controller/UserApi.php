<?php
namespace Controller;

use Library\Auth;
use Library\Message;
use Library\Str;
use Model\Users;

class UserApi extends Controller{
    function __construct()
    {
        parent::__construct();
        $this->defaultAction = 'defaultResult';
        $this->requireLogin = false;
    }
    public function defaultResult(){
        $this->result(STATUS_NOT_FOUND);
    }
    function login(){
        if(isset($this->parameters['email'])){
            $email = trim(addslashes(strip_tags($this->parameters['email'])));
            $listUser = Users::getByTop(1,"email='$email'");
            if(count($listUser)>0){
                $profile = $listUser[0]->getProfile();
                $this->output['profile'] = $profile;
                $this->output['access_token'] = Auth::getJwtToken($profile);
                
                $this->output['status'] = STATUS_OK;
            }
            else{
                $this->output['status'] = STATUS_NOT_FOUND;
                $this->output['message'] = Message::getStatusResponse(STATUS_NOT_FOUND);
            }
        }
        else{
            $this->output['status'] = STATUS_BAD_REQUEST;
            $this->output['message'] = Message::getStatusResponse(STATUS_BAD_REQUEST);
        }
    }

    public function addUser(){
        if($this->checkParameter(['fullName','email','password'])){
            $email = addslashes($this->parameters['email']);
            if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
                $this->result(STATUS_BAD_REQUEST, false, 'invalid email!');
                return;
            }
            $validPassword = $this->validatePassword($this->parameters['password']);
            if($validPassword != false){
                $this->result(STATUS_BAD_REQUEST, false, $validPassword);
                return;
            }
            if(!Users::checkExistEmail($email)){
                $group = Str::getVar($this->parameters,'group');
                $user = new Users();
                $this->initObject($user);
                $this->output = UserHelper\Add::add($user, $group);
            }
            else{
                $this->result(STATUS_BAD_REQUEST, false, 'email already exist!');
            }
        }
    }
    public function validatePassword($password){
        $message = false;
        if(!empty($password)) {
            if (strlen($password) < 8) {
                $message = "Yêu cầu mật khẩu lớn hơn 8 kí tự!";
            }
            elseif(!preg_match("#[0-9]+#",$password)) {
                $message = "Mật khẩu phải có ít nhất 1 số!";
            }
            elseif(!preg_match("#[A-Z]+#",$password)) {
                $message = "Mật khẩu phải chứa ít nhất 1 chữ hoa!";
            }
            elseif(!preg_match("#[a-z]+#",$password)) {
                $message = "Mật khẩu phải chứa ít nhất 1 chữ thường!";
            }
        }
        else{
            $message = "Mật khẩu không được bỏ trống";
        }
        return $message;
    }
    public function getDetailUser(){
        if($this->checkParameter(['id'])){
            $id = addslashes($this->parameters['id']);
            $this->output = UserHelper\Get::detail($id);
        }
    }
    public function editUser(){
        if($this->checkParameter(['id'])){
            $id = addslashes($this->parameters['id']);
            $user = Users::getById($id);
            if($user == false){
                $this->result(STATUS_NOT_FOUND);
                return;
            }
            else{
                $this->initObject($user);
                $this->output = UserHelper\Edit::edit($user);
            }
        }
    }
    private function initObject(&$user){
        foreach($this->parameters as $key => $value){
            if(property_exists($user,$key)){
                $user->$key = addslashes(strip_tags($value));
            }
        }
        $user->password = Auth::Hash($user->password);
    }
    public function lockUser(){
        if($this->checkParameter(['id','status'])){
            $id = addslashes($this->parameters['id']);
            $user = Users::getById($id);
            if($user == false){
                $this->result(STATUS_NOT_FOUND);
                return;
            }   
            else{
                $this->initObject($user);
                $this->output = UserHelper\Edit::edit($user);
            }
        }
    }
    public function showListUser(){
            $page = isset($this->parameters['page']) ? intval($this->parameters['page']) : 1;
            $pageSize = isset($this->parameters['pageSize']) ? intval($this->parameters['pageSize']) : 50;
            $filter = Str::getVar($this->parameters,'filter');
            $search = Str::getVar($this->parameters,'search');
            $this->output = UserHelper\Get::getListUser($page, $pageSize, $filter, $search);
    }
}