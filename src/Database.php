<?php
class Database
{
    private static $host     = 'localhost';
    private static $dbname   = 'g_a';     
    private static $user     = 'root';      
    private static $password = 'peresoso888';
    private static $charset  = 'utf8mb4';

    public static function getConnection()
    {
        $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$dbname . ";charset=" . self::$charset;

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

