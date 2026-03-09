<?php
namespace App\Core;

use App\Database\Database;
use PDO;

class Model
{

    /** @var PDO */
    protected $db;

    public function __construct()
    {
        // Automatically grab the Singleton PDO connection
        // Whenever a new model (like User or Booking) is instantiated
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * A helper wrapper around PDO prepare and execute for FETCHING
     * 
     * @param string $sql The query string
     * @param array $params The bound parameters
     * @return \PDOStatement
     */
    protected function query($sql, $params = []): \PDOStatement
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * A helper wrapper around PDO for MUTATING (INSERT/UPDATE/DELETE)
     * 
     * @param string $sql
     * @param array $params
     * @return bool
     */
    protected function execute($sql, $params = []): bool
    {
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
}
