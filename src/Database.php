<?php
class Database
{
    private static $host     = '127.0.0.1';
    private static $port     = '3308';
    private static $dbname   = 'g_a';     
    private static $user     = 'root';      
    private static $password = '';
    private static $charset  = 'utf8mb4';

    public static function getConnection()
    {
        $dsn = "mysql:host=" . self::$host . 
               ";port=" . self::$port . 
               ";dbname=" . self::$dbname . 
               ";charset=" . self::$charset;

        try {
            $pdo = new PDO($dsn, self::$user, self::$password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } 
        catch (PDOException $e) {
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }
}
