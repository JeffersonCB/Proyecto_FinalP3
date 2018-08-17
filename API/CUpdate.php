<?php
require_once 'CStatement.php';
use Core\CStatement;
class CUpdate extends CStatement {
//http://localhost/API/index.php?KEY=2345&statement=u&Table=TABLA_GRANDE&SKey=Nombre_Paciente&SVal=Warner&WKey=Cedula&WVal=0
//localhost/API/index.php?KEY=2345&statement=u&Table=TABLA_GRANDE&SKey=Nombre_Paciente,Edad,Fecha_Nacimiento,Provincia&SVal=guido,guido,guido,guido&WKey=Cedula,Nombre_Paciente&WVal=11,11
    public function __construct($table, $where_value, $where_key, $set_key, $set_val) {
        $this->table = $table;
        $this->where_key = explode(",", $where_key);
        $this->set_key = explode(",", $set_key);
        $this->set_val = explode(",", $set_val);
        $this->where_value = explode(",", $where_value);
//        $this->set_key = $set_key;
//        $this->set_val = $set_val;
//        $this->where_value = $where_value;
//        $this->where_key = $where_key;
        if (empty($this->table)) {
//            this "No hay tabla. Ingrese una.";
            $this->response(400, "NULL Table", NULL, NULL);
        } else {
            if (empty(implode(",", $this->where_key))) {
                $this->response(400, "NULL column", NULL, NULL);
            } else {
                if (empty(implode(",", $this->where_value))) {
                    $this->response(400, "NULL values", NULL, NULL);
                } else {

                    if (empty(implode(",", $this->set_key))) {
                        $this->response(400, "NULL setk", NULL, NULL);
                    } else {
                        if (empty(implode(",", $this->set_val))) {
                            $this->response(400, "NULL setv", NULL, NULL);
                        } else {
                            $this->parseUpdate();
                        }
                    }
                }
            }
        }

        $this->parseUpdate();
    }
    private function parseUpdate() {
        $p = [];
        $string = "UPDATE `" .
                $this->table . "`"
        ;
        if (!empty($this->sizeof($this->where_key))) {

            try {
                if (count($this->where_value) == count($this->where_key)) {
                    $size = $this->sizeof($this->where_value);
                    $string .= " SET ";
                    for ($i = 0; $i < $size; $i++) {

                        if ($i == 0) {
                            $string .= "`" . $this->where_value[$i] . "`='" . $this->where_key[$i] . "'";
                        } else {
                            $string .= " , " . "`" . $this->where_value[$i] . "`='" . $this->where_key[$i] . "'";

                            array_push($p, $this->where_value[$i]);
                        }
                    }
                } else {
                    $this->response(400, "NULL tamaño", NULL, NULL);
                }
            } catch (Exception $exc) {
                $this->response(400, "NULL tamaño", NULL, NULL);
            }
            try {
                if (count($this->set_key) == count($this->set_val)) {
                    $sizei = $this->sizeof($this->set_key);
                    $string .= " WHERE ";
                    for ($i = 0; $i < $sizei; $i++) {
                        if ($i == 0) {
                            $string .= "`" . $this->set_key[$i] . "`=" . $this->set_val[$i];
                        } else {
                            $string .= " AND `" . $this->set_key[$i] . "`=" . $this->set_val[$i];
                        }
                    }
                } else {

                    $this->response(400, "NULL tamaño", NULL, NULL);
                }
            } catch (Exception $exc) {
                $this->response(400, "NULL tamaño", NULL, NULL);
            }
        }
        $rs = \Core\S_DATABASE::execute($string);
        $this->parseResponse($rs);
    }
    private function parseResponse($rs) {
        if ($rs == FALSE) {
            echo $this->response(310, "No message,no way", 0, $rs);
        } else {
            echo $this->response(200, "success", 0, $rs);
        }
    }

}
