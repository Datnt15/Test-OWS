<?php
namespace Library;

use Model\Users;

class Str{
    public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    public function isJson($dataString){
        $data = json_decode($dataString);
        return (json_last_error() == JSON_ERROR_NONE);
    }
    public function getTreeData($StrTree) {
        $TreeData = $StrTree;
        if (is_string($StrTree)) {
            $TreeData = json_decode($StrTree, true);
        }
        if (isset($TreeData[0])) {
            return json_encode($this->getTreeNode($TreeData[0]),JSON_UNESCAPED_UNICODE);
        }
        return json_encode(array(),JSON_UNESCAPED_UNICODE);
    
    }
    private function getTreeNode($Node) {
        $Tree = array();
        $CurrentData = $Node['data']['property-data'];
        $Childs = array();
        if (count($Node['children']) > 0) {
            foreach ($Node['children'] as $child) {
                $ChildNode = $this->getTreeNode($child);
                array_push($Childs, $ChildNode);
            }
        }
        return array('data' => $CurrentData, 'children' => $Childs);
    
    }
    public function jsonEncode($v){
        return json_encode($v,JSON_UNESCAPED_UNICODE);
    }

    public function getCurrentUserId() {
        if (isset($_SESSION['user_Id'])) {
            return $_SESSION['user_Id'];
        }
        return 0;
    }
    public function convertSqltimeToVntime($time_string){
        $time = explode(' ', $time_string);
        $date = explode('-', $time[0]);
        $time = $date[2].'-'.$date[1].'-'.$date[0].(isset($time[1])?(' '.$time[1]):'');
        return $time;
    }

    public function getUserFullName($UserId) {
        if (isset($GLOBALS['UserInfo_' . $UserId])) {
            return $GLOBALS['UserInfo_' . $UserId]->FullName;
        } else {
            $userObject = Users::getById($UserId);
            if ($userObject != false) {
                $GLOBALS['UserInfo_' . $UserId] = $userObject; //memory cache
                return $userObject->FullName;
            }
            return '';
        }
    
    }

    public function getVar($Array,$Key){
        if(isset($Array[$Key]))
        {
            return addslashes(strip_tags($Array[$Key]));
        }
        if (isset($Array[strtolower($Key)])){
            return addslashes(strip_tags($Array[strtolower($Key)]));
        }
        return false;
    }
    
    public function getOrder($defaultField,$sort)	{
		if (!isset($sort) || empty($sort)) {
			return $defaultField . " desc";
		}
		return $sort['column']['Name'] . " " . $sort['type'];
	}
	public function isDate($date){
        $timestamp = strtotime($date);
		return $timestamp;
	}
    public function getArrayFromUnclearData($data){
        $array = [];
        if(is_array($data)){
            $array = $data;
        }
        else if(is_string($data)){
            $array = json_decode($data,true);
        }
        return $array;
    }
}