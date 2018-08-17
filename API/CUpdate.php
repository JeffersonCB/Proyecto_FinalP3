<?php

require_once 'CStatement.php';

use Core\CStatement;

class CUpdate extends CStatement {

//http://localhost/API/index.php?KEY=2345&statement=u&Table=TABLA_GRANDE&SKey=Nombre_Paciente&SVal=Warner&WKey=Cedula&WVal=0
    public function __construct($table, $where_value, $where_key, $set_key, $set_val) {

        $this->table = $table;
        $this->where_key = explode(",",$where_key);
        $this->set_key = explode(",",$set_key);
        $this->set_val = explode(",",$set_val);
        $this->where_value = explode(",",$where_value);
//        $this->set_key = $set_key;
//        $this->set_val = $set_val;
//        $this->where_value = $where_value;
//        $this->where_key = $where_key;


        if (is_null($this->table)) {
            echo "no hay tabla";
        }

        $this->parseUpdate();
    }

    private function parseUpdate() {
        $p = [];
        $string = "UPDATE `" .
                $this->table . "`"
        ;

        
        if (!empty($this->sizeof($this->where_key))) {
            
//            $this->validateWhereLenght()
//            $size=$this->validateKeyLenght();
            $size=$this->sizeof($this->where_value);
            $string .= " SET ";
            for($i = 0; $i < $size; $i++) {
                if ($i == 0){
                    $string .= "`" . $this->where_value[$i] . "`='" . $this->where_key[$i] . "'";
                }else{
                    $string .= " , " . "`" . $this->where_value[$i] . "`='" . $this->where_key[$i] . "'";;
                    array_push($p, $this->where_value[$i]);
                }
                
                }

                 $sizei=$this->sizeof($this->set_key);
            $string .= " WHERE ";
            for($i = 0; $i < $sizei; $i++) {
                if ($i == 0){
                    $string .= "`".$this->set_key[$i] . "`=" . $this->set_val[$i];
                }else{
                    $string .= " AND `" . $this->set_key[$i] . "`=" . $this->set_val[$i];
            }}
        }
        var_dump($string);
//       
//        $string = "UPDATE " . $this->table .
//                " SET " . $this->where_value  . "='" .$this->where_key   ."'".
//                " WHERE " .  $this->set_key."=".$this->set_val;
//        echo $string;
        try {
            $rs = \Core\S_DATABASE::execute($string);
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
//        $this->parseResponse($rs);
    }

//    private function parseResponse($rs) {
//        if ($rs->rowCount() <= 0)
//            echo $this->response(310, "No Rows founded", 0, $rs->fetchAll());
//        else
//            echo $this->response(200, "success", $rs->rowCount(), $rs->fetchAll());
//    }
}
