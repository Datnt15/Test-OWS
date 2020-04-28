<?php
namespace Controller\UserHelper;

use Library\Message;
use Model\Users;

class Get{
    public static function getListUser($page, $pageSize){
        $listObject = Users::getByPaging($page,$pageSize,'id ASC','');
        return [ 
            'status'    => STATUS_OK, 
            'message'   => Message::getStatusResponse(STATUS_OK),
            'data'      => $listObject
        ];
    }

    public static function detail($id){
        $user = Users::getById($id);
        unset($user->password);
        return [
            'status'    => STATUS_OK,
            'message'   => Message::getStatusResponse(STATUS_OK),
            'data'      => ['user' => $user]
        ];
    }

}