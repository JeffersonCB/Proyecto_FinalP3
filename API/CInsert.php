<?php

require_once 'CStatement.php';

use Core\CStatement;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class CInsert extends CStatement {

 //   http://localhost/API/index.php?KEY=73461234dgvbsv2e18r5rt&statement=i&Table=BRAYAN_INVENTARIO&Column=NOMBRE_PRODUCTO,CODIGO_PRODUCTO&Values=%22lol%22,%272%27


    public function __construct($table, $cols, $values) {
        echo "hola";
        $this->table = $table;
        $this->columns =$cols;
        $this->where_value =$values;

        if (is_null($this->table)) {
            echo "no hay tabla";
        }

        $this->parseInsert();
    }

    private function parseInsert() {

        $string = "INSERT INTO " . $this->table . " (" . $this->columns  . ")" .
                " VALUES (" . $this->where_value.")" ;
        echo $string;
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
