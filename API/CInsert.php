<?php
require_once 'CStatement.php';
use Core\CStatement;
class CInsert extends CStatement {
// http://localhost/API/index.php?KEY=2345&statement=i&Table=TABLA_GRANDE&Column=Nombre_Paciente,Fecha_Nacimiento&Values=101,101

    public function __construct($table, $cols, $values) {
        $this->table = $table;
        $this->cols = explode(",", $cols);
        $this->values = explode(",", $values);
        
        if (empty($this->table)) {
            $this->response(400, "NULL Table", NULL, NULL);
        } else {
            if (empty(implode(",", $this->cols))) {
                $this->response(400, "NULL column", NULL, NULL);
            } else {
                if (empty(implode(",", $this->values))) {
                    $this->response(400, "NULL values", NULL, NULL);
                } else {
                    $this->parseInsert();
                }
            }
        }
    }
    
    
    private function parseInsert() {
             $string = "INSERT INTO " . $this->table . " (" . implode(",", $this->cols) . ")" .
                    " VALUES ('" . implode("','", $this->values) . "');";
        $rs = \Core\S_DATABASE::execute($string);
        $this->parseResponse($rs);
    }

    private function parseResponse($rs) {
        if ($rs==false)
            echo $this->response(310, "no message, no way", 0, $rs);
        else
            echo $this->response(200, "success", 0, $rs);
    }

}