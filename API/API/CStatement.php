<?php

namespace Core;

class CStatement
{

    protected $table = "";
    
    protected $set_key = [];
    protected $set_val = [];
    
    protected $where_key = [];
    protected $where_value = [];
    protected $where_signal = [];
    protected $values=[];


    protected function validateWhereLenght() {
        $sizeOfKey = $this->sizeof($this->where_key);
        $sizeOfVal = $this->sizeof($this->where_value);
        $sizeOfSig = $this->sizeof($this->where_signal);

        if ($sizeOfKey != $sizeOfVal || $sizeOfKey != $sizeOfSig) {
            $this->response(400,
                "el numero de keys y values no es igual, en el where clause. "
                . "WKey Size: " . ($sizeOfKey). ". "
                . "WVal Size: " . ($sizeOfVal) . ". "
                . "WSig Size: " . ($sizeOfSig) ,
                NULL
            );
        }
        return $sizeOfKey;
    }
    
    protected function validateKeyLenght() {
        $sizeOfKey = $this->sizeof($this->set_key);
        $sizeOfVal = $this->sizeof($this->set_val);

        if ($sizeOfKey != $sizeOfVal) {
            $this->response(400,
                "el numero de keys y values no es igual, en el where clause. "
                . "WKey Size: " . ($sizeOfKey). ". "
                . "WVal Size: " . ($sizeOfVal) . ". ",
                NULL
            );
        }
        return $sizeOfKey;
    }
    
    
    protected function parseSignal($encodedSignal) {
        
        switch ($encodedSignal) {
            case "i": return "=";
            case "a": return ">";
            case "m": return "<";
            case "ai": return ">=";
            case "mi": return "<=";
        }
        $this->response(400, "Invalid Signal: " . $encodedSignal, NULL);
    }
    
    // como se llena por explode, el sizeof de PHP siempre devolverá
    // almenos 1 valor. esto lo fixea
    protected function sizeof($array) {
        $i = 0;
        foreach ($array as $val) {
            if (!empty($val))
                $i++;
        }
        return $i;
    }

    protected function response($status, $status_message, $data_lenght, $data) {
        header("HTTP/1.1 " . $status);
        header('Content-Type: application/json');

        $response['status'] = $status;
        $response['status_message'] = $status_message;
        $response['columnas'] = $data_lenght;
        $response['data'] = $data;

        $json_response = json_encode($response, JSON_PRETTY_PRINT);
        echo $json_response;
        exit;
    }
}
