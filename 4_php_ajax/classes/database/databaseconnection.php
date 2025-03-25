<?php

namespace database;

use config\userConfig;
use controller\garbleController;

class databaseConnection
{
    private static $instance = null;
    private $conn;
    private $garbler;

    // =============================================================================================
    private function __construct()
    {
        $creds = userConfig::getUsersCreds();
        $this->garbler = new garbleController();
        try 
        {
            $this->conn = new \PDO(
                "mysql:host={$creds['db_host']};dbname={$creds['db_name']}",
                $creds['db_user'],
                $this->garbler->garble($creds['db_pass']),
                [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
            );
        } catch (\PDOException $e) 
        {
            die("Connection failed: " . $e->getMessage());
        }
    }

    // ===================================== singleton time ========================================
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new databaseConnection();
        }
        return self::$instance;
    }

    // =============================================================================================
    public function getConnection()
    {
        return $this->conn;
    }
}