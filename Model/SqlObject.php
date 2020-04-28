<?php

use Model\Model;
class SqlObject extends Model{
    public $listForeignKey;

    public function __construct($data = []){
        $this->listForeignKey=[];
        $keys=get_object_vars($this);
        foreach ($keys as $key => $_) {
            if($key!='listForeignKey'){
                $columnNameInDatabase = static::getColumnNameInDataBase($key); 
                if(isset($data[$columnNameInDatabase])){
                    $this->$key = isset($data[$columnNameInDatabase]) ? $data[$columnNameInDatabase] : '';
                }
                else if(isset($data[$key])){
                    $this->$key = $data[$key];
                }
            }
        }
    }
    public function encode(){
        $arrayKey=get_object_vars($this);
        foreach($arrayKey as $key=>$value){
            if(is_string($value)){
                $this->$key=addslashes($value);
            }
        }
    }
    public function getForeignKey($SourceField){
        if(isset($this->listForeignKey)){
            foreach($this->listForeignKey as $fk){
                if($fk->SourceField==$SourceField) return $fk;
            }
        }
        return null;
    }
}