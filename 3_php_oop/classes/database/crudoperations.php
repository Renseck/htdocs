<?php 

namespace database;

class crudOperations
{
    private $db;
    private $table;

    // =============================================================================================
    public function __construct($table)
    {
        $this->db = databaseConnection::getInstance()->getConnection();
        $this->table = $table;
    }

    // =============================================================================================
    /**
     * Create a new record in the database
     * @param array $data Associative array of column names and values
     * @return int|bool Last inserted ID or false on failure
     */
    public function create(array $data)
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));

        $sql = "INSERT INTO $this->table ({$columns}) VALUES ({$placeholders})";

        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute($data);

        return $result ? $this->db->lastInsertId() : false;
    }

    // =============================================================================================
    /**
     * Read records from the database
     * @param string $fields Fields to select (default: *)
     * @param array $conditions Optional WHERE conditions
     * @param string $order Optional ORDER BY clause
     * @param int $limit Optional LIMIT
     * @param int $offset Optional OFFSET
     * @return array Results
     */
    public function read(string $fields = "*", array $conditions = [], string $order = "", int $limit = 0, int $offset = 0)
    {
        $sql = "SELECT {$fields} FROM {$this->table}";

        $params = [];

        // WHERE clauses
        if (!empty($conditions))
        {
            $sql .= " WHERE ";
            $whereConditions = [];   

            foreach ($conditions as $key => $value) {
                $whereConditions[] = "{$key} = :{$key}";
                $params[":{$key}"] = $value;
            }

            $sql .= implode(" AND ", $whereConditions);
        }

        // ORDER BY clause
        if (!empty($order))
        {
            $sql .= " ORDER BY {$order}";
        }

        // LIMIT and OFFSET
        if ($limit > 0)
        {
            $sql .= " LIMIT {$limit}";

            if ($offset > 0)
            {
                $sql .= " OFFSET {$offset}";
            }
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // =============================================================================================
    /**
     * Read a single record by ID or custom condition
     * @param mixed $id ID value or array of conditions
     * @param string $fields Fields to select
     * @return array|false Result row or false if not found
     */
    public function readOne($id, string $fields = "*")
    {
        $conditions = is_array($id) ? $id : ['id' => $id];
        $result = $this->read($fields, $conditions);

        return !empty($result) ? $result[0] : false;
    }

    // =============================================================================================
    /**
     * Update records in the database
     * @param array $data Data to update
     * @param array $conditions WHERE conditions
     * @return int|bool Number of affected rows or false on failure
     */
    public function update(array $data, array $conditions)
    {
        $sql = "UPDATE {$this->table} SET ";

        $updates = [];
        $params = [];

        // SET clause
        foreach ($data as $key => $value)
        {
            $updateKey = "upd_{$key}";
            $updates[] = "{$key} = :{$updateKey}";
            $params[":{$updateKey}"] = $value;
        }

        // WHERE clause

        if (!empty($conditions))
        {
            $sql .= " WHERE ";
            $whereConditions = [];

            foreach ($conditions as $key => $values)
            {
                $whereKey = "where_{$key}";
                $whereConditions[] = "{$key} = :{$whereKey}";
                $params[":{$whereKey}"] = $values;
            }

            $sql .= implode(" AND ", $whereConditions);
        }

        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute($params);

        return $result ? $stmt->rowCount() : false;
    }

    // =============================================================================================
    /**
     * Delete records from the database
     * @param array $conditions WHERE conditions
     * @return int|bool Number of affected rows or false on failure
     */
    public function delete(array $conditions)
    {
        $sql = "DELETE FROM {$this->table}";

        $params = [];

        // WHERE clause
        if (!empty($conditions))
        {   
            $sql .= " WHERE ";
            $whereConditions = [];

            foreach ($conditions as $key => $value) {
                $whereConditions[] = "{$key} = :{$key}";
                $params[":{$key}"] = $value;
            }

            $sql .= implode(" AND ", $whereConditions);
        }

        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute($params);

        return $result ? $stmt->rowCount() : false;
    }

    // =============================================================================================
    public function customQuery(string $sql, array $params = [])
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}