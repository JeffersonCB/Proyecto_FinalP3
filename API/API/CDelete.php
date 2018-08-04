<?php
require_once 'CStatement.php';
use Core\CStatement;

class CDelete extends CStatement {


    //http://localhost/API/?KEY=73461234dgvbsv2e18r5rt&statement=s&Column=all&Table=TABLA_GRANDE
    
    
    //http://localhost/API/?KEY=73461234dgvbsv2e18r5rt&statement=d&Column=Cedula,Nombre_Paciente&Table=TABLA_GRANDE&WSIG=SIG&WKEY=KEY&WVAL=1

//    http://localhost/API/?KEY=73461234dgvbsv2e18r5rt&statement=d&Column=Cedula,Nombre_Paciente&Table=TABLA_GRANDE&WKey=KEY,5&WVal=1,2&WSig=eee
    
//    http://localhost/API/?KEY=73461234dgvbsv2e18r5rt&statement=d&Column=Cedula,Nombre_Paciente&Table=TABLA_GRANDE&WKey=m,m&WVal=i,i&WSig=i,i
    
//    http://localhost/API/?KEY=73461234dgvbsv2e18r5rt&statement=d&Column=Cedula,Nombre_Paciente&Table=TABLA_GRANDE&WKey=Cedula&WVal=i&WSig=i
    
//    http://localhost/API/?KEY=73461234dgvbsv2e18r5rt&statement=d&Column=Cedula,Nombre_Paciente&Table=TABLA_GRANDE&WKey=Cedula&WSig=i&WVal=8
    
    public function __construct($cols, $table, $where_key =null, 
             $where_value=null,$where_signal=null ) 
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
        
//        if (empty($this->columns))
//            $this->columns = "*";
        
        $this->parseDelete();
    }

    private function parseDelete() {
        $p = [];
//         DELETE FROM `icompon1_progra`.`TABLA_GRANDE` WHERE  `Cedula`=586;
        $string = "DELETE FROM `".
                 $this->table . "`"
                ;

                
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
                        $this->parseSignal($this->where_signal[$i]) .  "?";
                array_push($p, $this->where_value[$i]);
            }
            
        }
//        var_dump($string);
//        
        $rs = \Core\S_DATABASE::execute($string, $p);
//        $this->parseResponse($rs);
    }
    
    private function parseResponse($rs) {
        if ($rs->rowCount() <= 0)
            echo $this->response(310, "No Rows founded", 0, $rs->fetchAll());
        else
            echo $this->response(200, "success", $rs->rowCount(), $rs->fetchAll());
    }

}

