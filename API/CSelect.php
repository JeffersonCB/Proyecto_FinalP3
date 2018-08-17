<?php
require_once 'CStatement.php';

use Core\CStatement;

class CSelect extends CStatement {
    //http://localhost/API/?KEY=73461234dgvbsv2e18r5rt&statement=s&Column=all&Table=TABLA_GRANDE
    //http://localhost/API/?KEY=2345=s&Column=Cedula,Nombre_Paciente&Table=TABLA_GRANDE
    public function __construct($cols, $table, $where_key = NULL, 
            $where_signal = NULL, $where_value = NULL) 
    {
        $this->table = $table;
        $this->columns = ($cols == "all" ? "*" : str_replace("%2C", ",", $cols));
        $this->where_key = explode(",",$where_key);
        $this->where_signal = explode(",",$where_signal);
        $this->where_value = explode(",",$where_value);
        
        if (is_null($this->table))
        {
            $this->response(400, "NULL Table", NULL, NULL);
        }
        
        if (empty($this->columns))
            $this->columns = "*";
        
        $this->parseSelect();
    }
    private function parseSelect() {
        $p = [];
        $string = "SELECT " . $this->columns .
                " FROM `" . $this->table . "`";

        if (!empty($this->sizeof($this->where_key))) 
        {
            $size = $this->validateWhereLenght();
    
            $string .= " WHERE ";
            for ($i = 0; $i < $size; $i++) 
            {
                if ($i == 0)
                    $string .= "`" . $this->where_key[$i] . "`".
                        $this->parseSignal($this->where_signal[$i]) ."?";
                else
                    $string .= " AND `" . $this->where_key[$i] . "`". 
                        $this->parseSignal($this->where_signal[$i]) ."?";
                array_push($p, $this->where_value[$i]);
            }
            
        }
        $rs = \Core\S_DATABASE::execute($string, $p);
        $this->parseResponse($rs);
    }
    private function parseResponse($rs) {
        if ($rs->rowCount() <= 0)
            echo $this->response(310, "No Rows founded", 0, $rs->fetchAll());
        else
            echo $this->response(200, "success", $rs->rowCount(), $rs->fetchAll());
    }

}
