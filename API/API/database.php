<?php

namespace Core;

use PDO;

/**
 * MODELO REALACIONAL DE SEGURIDAD BASE DE DATOS
 * NANO SOFT (C) OBORO CP 15|11|2k15 - 3o/o3/2k17
 * rev. 2018 16/04 SINGLETON 
 * 	- persistant conection
 *  - Users as instances
 *  - execute as query
 *  - Migración PDO::
 * */
class S_DATABASE
{

    private static $PDO = NULL;
    private static $result;
    public static $stmt;
    private static $EndSQL;
    private static $lastQuery;

    /**
     * Constructor y el clone ocupa ser vació
     * por efectos de control del Singleton.
     * @private
     */
    public function __construct()
    {
        
    }

    private function __clone()
    {
        
    }

    /**
     * Crea la conexión a la DB al entrar a la página
     * 16/08/2017 Security Update
     * Crea la instancia SQL a Main o Forum, dependiendo de la necesidad.
     * Esto ya que el foro no puede estar en la misma DB del In-Game.
     * Misma debe estar en la DB del WebHost.
     * */
    private static function getConnection()
    {
        if (is_null(self::$PDO))
        {
            try
            {
                $dns = 'mysql:host=' . S_CONFIG::getConfig('DBHost') .
                        ';dbname=' . S_CONFIG::getConfig('DBase') .
                        ';charset=utf8';

                self::$PDO = new PDO($dns, S_CONFIG::getConfig('DBUser'), S_CONFIG::getConfig('DBPswd'));
            } catch (PDOException $e)
            {
                echo self::ExceptionLog($e->getMessage());
                die();
            }
        }
        return self::$PDO;
    }

    /**
     * Cierra conxiones existentes a la base de datos
     * al dejar la página
     * @private
     * */
    public function __destruct()
    {
        if (!is_null(self::$PDO))
        {
            self::EndSQL();
        }
    }

    public static function getColumns()
    {
        if (is_null(self::$lastQuery))
        {
            echo 'No se puede llamar getColumns() antes de una consulta';
            return;
        }
        
        $table = explode(" ", explode("FROM ", self::$lastQuery)[1])[0];
        $cols = explode(",", explode("FROM", explode("SELECT ", self::$lastQuery)[1])[0]);
        
        
        if (trim($cols[0]) === "*") {       
            $rs = self::execute('SELECT * FROM '. $table .' LIMIT 0');
            
            for ($i = 0; $i < $rs->columnCount(); $i++)
            {
                $col = $rs->getColumnMeta($i);
                $columns[] = $col['name'];
            }
            return $columns;
        }
        else
        {
            return $cols;
        }
    }

    /**
     * [ISAAC] Nanosoft (c) 31/03/2017
     * Ejecuta de manera rápida y segura una consulta con un conjunto de parámetros;
     * En donde ya no hay que preocuparse de los injects a su vez esta función genera
     * lo que Query y MRES hacían en Oboro 3.0 y posterior.
     * $args = array();
     * 	- Para enviar parametros a una consulta se utiliza así:
     *  	$param = [<parametro1>,<parametro2>,<parametro3>,...]
     *    
     *    [VAR_DUMP Vars]
     *    	- $DB->stmt:
     *     		->affected_rows: INSERT UPDATE DELETE Row(s)
     *       
     *      - $DB->result(boolean):
     *      	will return TRUE if a INSERT/UPDATE/DELETE is without errors in $query
     *       	will return FALSE if a INSERT/UPDATE/DELETE have some errors in $query
     *     $result:
     *     		->num_rows: the correct way to know if a SELECT has data(rows);
     *     	
     * */
    public static function execute($query, $args = array(), $fetchmode = PDO::FETCH_ASSOC)
    {
        self::$stmt = self::getConnection()->prepare(trim($query));
        if (self::$stmt)
        {
            $rawStatement = explode(" ", $query);
            $statement = strtolower($rawStatement[0]);
            self::$stmt->setFetchMode($fetchmode);
            self::$stmt->execute($args);

            if ($statement === 'select')
            {
                self::$lastQuery = $query;
                return self::$stmt;
            } elseif ($statement === 'insert' || $statement === 'update' || $statement === 'delete')
            {
                return self::$stmt->rowCount();
            } else
            {
                return NULL;
            }
        }

        return self::$stmt ? self::stmt : FALSE;
    }
    
    public static function fetch($resultSet, $fetchmode = PDO::FETCH_ASSOC) {
        return ($resultSet->fetchAll($fetchmode));
    }

    /**
     * Destructor de Conexiones abiertas a la DB
     */
    public static function EndSQL()
    {
        self::$PDO = null;
    }

    public static function free()
    {
        self::$stmt->closeCursor();
        return;
    }

    public static function num_rows()
    {
        $result = self::execute("SELECT FOUND_ROWS()", []);
        return (!empty($result) ? $result->fetchColumn() : FALSE);
    }

    public static function ShowColumns($table)
    {
        $result = self::execute("SHOW COLUMNS FROM `" . $table . "`", []);
        $storeArray = Array();
        while ($row = $result->fetch(PDO::FETCH_NUM))
        {
            array_push($storeArray, $row[0]);
        }

        $this->free($result);
        return $storeArray;
    }

    private function ExceptionLog($message, $sql = "")
    {
        $exception = 'Unhandled Exception. <br />';
        $exception .= $message;
        $exception .= "<br /> You can find the error back in the log.";

        if (!empty($sql))
        {
            $message .= "\r\nRaw SQL : " . $sql;
        }
        $this->log->write($message, $this->DBName . md5($this->DBPassword));
        //Prevent search engines to crawl
        header("HTTP/1.1 500 Internal Server Error");
        header("Status: 500 Internal Server Error");
        return $exception;
    }

}
