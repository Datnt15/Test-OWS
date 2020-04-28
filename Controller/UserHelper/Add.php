<?php
namespace Controller\UserHelper;

use Library\Message;

class Add{
    public static function add($user, $group){
        $user->createAt = date(DATETIME_FORMAT);
        $user->updateAt = $user->createAt;
        $user->status = 1;
        $fetchAssoc = pg_fetch_assoc($user->insert(true));
        if(isset($fetchAssoc['id'])){
            $user->id = $fetchAssoc['id'];
            return [
                'status'  => STATUS_OK,
                'message' => Message::getStatusResponse(STATUS_OK),
            ];
        }
        else{
            return [
                'status'  => STATUS_SERVER_ERROR,
                'message' => Message::getStatusResponse(STATUS_SERVER_ERROR),
            ];
        }
    }
}