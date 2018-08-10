<?php

require_once 'CStatement.php';

use Core\CStatement;
class CInsert extends CStatement {
// http://localhost/API/index.php?KEY=2345&statement=i&Table=TABLA_GRANDE&Column=Nombre_Paciente,Fecha_Nacimiento&Values=101,101
    public function __construct($table, $cols, $values) {
        $this->table = $table;
        $this->cols = explode(",", $cols);
        $this->values = explode(",", $values);
        if (is_null($this->table)) {
            echo "no hay tabla";
        }
        $this->parseInsert();
    }
    private function parseInsert() {
        if ($this->validateWhereLenght($this->cols)) {
            echo "Las columnas no tienen el mismo numero de datos";
           
        } else {
             $string = "INSERT INTO " . $this->table . " (" . implode(",", $this->cols) . ")" .
                    " VALUES ('" . implode("','", $this->values) . "');";
             echo "<br />";
        echo $string;
        }

        $rs = \Core\S_DATABASE::execute($string);
//        $this->parseResponse($rs);
    }
//
//    private function parseResponse($rs) {
//        if ($rs->execute)
//            echo $this->response(310, "No Rows founded", 0, $rs->fetchAll());
//        else
//            echo $this->response(200, "success", $rs->rowCount(), $rs->fetchAll());
//    }
//}
}