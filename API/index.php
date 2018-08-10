<?php

require_once './config.php';
require_once './database.php';
require_once './CSelect.php';
require_once './CInsert.php';
require_once './CUpdate.php';
require_once './CDelete.php';

$keyGEN = "2345";

if (!isset($_GET["KEY"]) || strcmp($_GET["KEY"], $keyGEN) != 0) {
    echo "no way counter";    
    exit;
}
switch ($_GET["statement"]) {
    case "s":
        $select =  new CSelect (
                    (isset($_GET["Column"]) ? $_GET["Column"] : null), 
                    (isset($_GET["Table"]) ? $_GET["Table"] : null), 
                    (isset($_GET["WKey"]) ? $_GET["WKey"] : null),
                    (isset($_GET["WSig"]) ? $_GET["WSig"] : null), 
                    (isset($_GET["WVal"]) ? $_GET["WVal"] : null)
                );
        break;
    case "i":
        $insert =  new CInsert (
                    (isset($_GET["Table"]) ? $_GET["Table"] : null),
                    (isset($_GET["Column"]) ? $_GET["Column"] : null),                 
                    (isset($_GET["Values"]) ? $_GET["Values"] : null)
                );        
        break;
    case "u":
        $update =  new CUpdate (
                    (isset($_GET["Table"]) ? $_GET["Table"] : null),
                    (isset($_GET["SKey"]) ? $_GET["SKey"] : null),
                    (isset($_GET["SVal"]) ? $_GET["SVal"] : null),
                    (isset($_GET["WKey"]) ? $_GET["WKey"] : null), 
                    (isset($_GET["WVal"]) ? $_GET["WVal"] : null)
                );
        break;
    
 case "d":
        $delete =  new CDelete (
                
                    (isset($_GET["Column"]) ? $_GET["Column"] : null), 
                    (isset($_GET["Table"]) ? $_GET["Table"] : null), 
                    (isset($_GET["WKey"]) ? $_GET["WKey"] : null),
                    (isset($_GET["WVal"]) ? $_GET["WVal"] : null),
                    (isset($_GET["WSig"]) ? $_GET["WSig"] : null)
                    
//                    (isset($_GET["Table"]) ? $_GET["Table"] : null), 
//                    (isset($_GET["WKey"]) ? $_GET["WKey"] : null),
//                    (isset($_GET["WSig"]) ? $_GET["WSig"] : null), 
//                    (isset($_GET["WVal"]) ? $_GET["WVal"] : null)
                );        
        break;
    
//    case "m":
//        $mail = new CEmail(
//                (isset($_GET["File"]) ? $_GET["File"] : null),                 
//                (isset($_GET["Number"]) ? $_GET["Number"] : null),
//                (isset($_GET["Email"]) ? $_GET["Email"] : null)
//            );
//        break;

}