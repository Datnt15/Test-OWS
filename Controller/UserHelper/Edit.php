<?php
namespace Controller\UserHelper;

use Library\Message;

class Edit{
 
    public static function edit($user){
        $user->updateAt = date(DATETIME_FORMAT);
        $result = pg_result_status($user->update());
        if($result == 1){
            return [
                'status'    => STATUS_OK,
                'message'   => Message::getStatusResponse(STATUS_OK)
            ];
        }
        return [
            'status'    => STATUS_BAD_REQUEST,
            'message'   => Message::getStatusResponse(STATUS_BAD_REQUEST)
        ];
    }
    

}