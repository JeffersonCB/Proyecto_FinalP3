<?php
require_once 'CStatement.php';
use Core\CStatement;
class CDelete extends CStatement {
//    http://localhost/API/?KEY=2345&statement=d&Column=Cedula,Nombre_Paciente&Table=TABLA_GRANDE&WKey=Cedula&WSig=i&WVal=90
    public function __construct($cols, $table, $where_key = null, $where_value = null, $where_signal = null) {
        $this->table = $table;
        $this->columns = ($cols == "all" ? "*" : str_replace("%2C", ",", $cols));
        $this->where_key = explode(",", $where_key);
        $this->where_signal = explode(",", $where_signal);
        $this->where_value = explode(",", $where_value);
        if (is_null($this->table)) {
            $this->response(400, "NULL Table", NULL, NULL);
        }
        if (empty($this->table)) {
            $this->response(400, "NULL Table", NULL, NULL);
        } else {
            if (empty(implode(",", $this->where_key))) {
                $this->response(400, "NULL key", NULL, NULL);
            } else {
                if (empty(implode(",", $this->where_value))) {
                    $this->response(400, "NULL values", NULL, NULL);
                } else {
                    if (empty(implode(",", $this->where_signal))) {
                        $this->response(400, "NULL signo", NULL, NULL);
                    } else {
                        $this->parseDelete();
                    }
                }
            }
        }
    }
    private function parseDelete() {
        $p = [];
        $string = "DELETE FROM `" .
                $this->table . "`"
        ;
        if (count($this->where_value) == count($this->where_key)) {
            if (count($this->where_value) == count($this->where_signal)) {
                $size = $this->sizeof($this->where_key);
                $string .= " WHERE ";
                for ($i = 0; $i < $size; $i++) {
                    if ($i == 0)
                        $string .= "`" . $this->where_key[$i] . "`" .
                                $this->parseSignal($this->where_signal[$i]) . "?";
                    else
                        $string .= " AND `" . $this->where_key[$i] . "`" .
                                $this->parseSignal($this->where_signal[$i]) . "?";
                    array_push($p, $this->where_value[$i]);
                }
                $rs = \Core\S_DATABASE::execute($string, $p);
                $this->parseResponse($rs);
            }else {
                echo $this->response(310, "in signal", 0, null);
            }
        } else {
            echo $this->response(310, "in val or key", 0, NULL);
        }
    }
    private function parseResponse($rs) {
        if ($rs == FALSE)
            echo $this->response(310, "No message,no way", 0, $rs);
        else
            echo $this->response(200, "success", 0, $rs);
    }
}
