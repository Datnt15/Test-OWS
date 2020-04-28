<?php
namespace Model;
use SqlObject;
class Users extends SqlObject
{
    public $id,
        $fullName,
        $email,
        $password,
        $phone,
        $status = 1,
        $note,
        $permission,
        $avatar,
        $createAt,
        $updateAt;
    public static $mappingFromDatabase = [
        'id'            =>  [ 'name' => 'id',           'type' => 'number', 'auto_increment' => true],
        'fullName'      =>  [ 'name' => 'full_name',     'type' => 'string'],
        'email'         =>  [ 'name' => 'email',        'type' => 'string'],
        'password'      =>  [ 'name' => 'password',     'type' => 'string'],
        'phone'         =>  [ 'name' => 'phone',        'type' => 'number'],
        'status'        =>  [ 'name' => 'status',       'type' => 'number'],
        'note'          =>  [ 'name' => 'note',         'type' => 'string'],
        'permission'    =>  [ 'name' => 'permission',   'type' => 'string'],
        'avatar'        =>  [ 'name' => 'avatar',       'type' => 'string'],
        'createAt'      =>  [ 'name' => 'create_at',   'type' => 'datetime'],
        'updateAt'      =>  [ 'name' => 'update_at',   'type' => 'datetime'],
    ];
    public function __construct($data=[]){
        parent::__construct($data);
    }
    public static function getTableName(){
        return 'users';
    }
    public static function getColumnNameInDataBase($fieldName, $returnArray = false){
        if(isset(self::$mappingFromDatabase[$fieldName]['name'])){
            if($returnArray){
                return self::$mappingFromDatabase[$fieldName];
            }
            else{
                return self::$mappingFromDatabase[$fieldName]['name'];
            }
        }
        return false;
    }
    public function getProfile(){
        $profile = [
            'id'        => $this->id,
            'email'     => $this->email,
            'full_Name' => $this->fullName
        ];
        return $profile;
    }

    public static function checkExistEmail($email){
        $listUsers = self::getByTop("","email = '$email'");
        if (count($listUsers) > 0) {
            return true;
        }
        return false;
    }

    
}