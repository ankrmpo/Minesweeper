<?php
class DB
{
    private static $db = null;

    final private function __construct() { }
    final private function __clone() { }
    
    public static function getConnection()
    {
        if(DB::$db == null)
        {
            $user = 'student';
            $pass = 'pass.mysql';
            DB::$db = new PDO('mysql:host=rp2.studenti.math.hr;dbname=bistrovic;charset=utf8', $user, $pass);
        }

        return DB::$db;
    }
}
?>