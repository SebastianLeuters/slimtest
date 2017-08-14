<?php

namespace Orm;

use Prophecy\Exception\InvalidArgumentException;

class MysqlDatabaseAdapter implements DatabaseAdapterInterface
{
    /**
     * @var array
     */
    protected $config = array();

    /**
     * @var \mysqli
     */
    protected $connection;

    /**
     * @var array
     */
    protected $result;

    /**
     * MysqlDatabaseAdapter constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        if (count($config) !== 5) {
            throw new InvalidArgumentException('Invalid number of connection parameters.');
        }

        $this->config = $config;
    }

    /**
     * Connect to MySQL
     *
     * @return \mysqli
     */
    public function connect()
    {
        // connect only once
        if (is_null($this->connection)) {
            if (!$this->connection = @mysqli_connect($this->config['host'], $this->config['user'], $this->config['password'], $this->config['database'], $this->config['port'])) {
                throw new \RuntimeException('Error connecting to the server : ' . mysqli_connect_error());
            }
            unset($host, $user, $password, $database);
        }
        return $this->connection;
    }

    /**
     * Execute the specified query
     *
     * @param $query
     * @return array|bool|\mysqli_result
     */
    public function query($query)
    {
        if (!is_string($query) || empty($query)) {
            throw new InvalidArgumentException('The specified query is not valid.');
        }
        // lazy connect to MySQL
        $this->connect();
        if (!$this->result = mysqli_query($this->connection, $query)) {
            throw new \RuntimeException('Error executing the specified query ' . $query . mysqli_error($this->connection));
        }
        return $this->result;
    }

    /**
     * Perform a SELECT statement
     *
     * @param $table
     * @param string $where
     * @param string $fields
     * @param string $order
     * @param null $limit
     * @param null $offset
     * @return array|bool|\mysqli_result
     */
    public function select($table, $where = '', $fields = '*', $order = '', $limit = null, $offset = null)
    {
        $query = 'SELECT ' . $fields . ' FROM ' . $table
            . (($where) ? ' WHERE ' . $where : '')
            . (($limit) ? ' LIMIT ' . $limit : '')
            . (($offset && $limit) ? ' OFFSET ' . $offset : '')
            . (($order) ? ' ORDER BY ' . $order : '');

        return $this->query($query);
    }

    /**
     * Perform an INSERT statement
     *
     * @param $table
     * @param array $data
     * @return int|null|string
     */
    public function insert($table, array $data)
    {
        $fields = implode(',', array_keys($data));
        $values = implode(',', array_map(array($this, 'quoteValue'), array_values($data)));
        $query = 'INSERT INTO ' . $table . ' (' . $fields . ') ' . ' VALUES (' . $values . ')';
        $this->query($query);
        return $this->getInsertId();
    }

    /**
     * Perform an UPDATE statement
     *
     * @param $table
     * @param array $data
     * @param string $where
     * @return int
     */
    public function update($table, array $data, $where = '')
    {
        $set = array();
        foreach ($data as $field => $value) {
            $set[] = $field . '=' . $this->quoteValue($value);
        }
        $set = implode(',', $set);
        $query = 'UPDATE ' . $table . ' SET ' . $set
            . (($where) ? ' WHERE ' . $where : '');
        $this->query($query);
        return $this->getAffectedRows();
    }

    /**
     * Perform a DELETE statement
     *
     * @param $table
     * @param string $where
     * @return int
     */
    public function delete($table, $where = '')
    {
        $query = 'DELETE FROM ' . $table
            . (($where) ? ' WHERE ' . $where : '');
        $this->query($query);
        return $this->getAffectedRows();
    }

    /**
     * Escape the specified value
     *
     * @param $value
     * @return string
     */
    public function quoteValue($value)
    {
        $this->connect();
        if ($value === null) {
            $value = 'NULL';
        } else if (!is_numeric($value)) {
            $value = "'" . mysqli_real_escape_string($this->connection, $value) . "'";
        }
        return $value;
    }

    /**
     * Fetch a single row from the current result set (as an associative array)
     *
     * @return array|bool|null
     */
    public function fetch()
    {
        if ($this->result !== null) {
            if (($row = mysqli_fetch_array($this->result, MYSQLI_ASSOC)) === false) {
                $this->freeResult();
            }
            return $row;
        }
        return false;
    }

    /**
     * Get the insertion ID
     *
     * @return int|null|string
     */
    public function getInsertId()
    {
        return $this->connection !== null
            ? mysqli_insert_id($this->connection) : null;
    }

    /**
     * Get the number of rows returned by the current result set
     *
     * @return int
     */
    public function countRows()
    {
        return $this->result !== null
            ? mysqli_num_rows($this->result) : 0;
    }

    /**
     *  Get the number of affected rows
     *
     * @return int
     */
    public function getAffectedRows()
    {
        return $this->connection !== null
            ? mysqli_affected_rows($this->connection) : 0;
    }

    /**
     * Free up the current result set
     *
     * @return bool
     */
    public function freeResult()
    {
        if ($this->result === null) {
            return false;
        }
        mysqli_free_result($this->result);
        return true;
    }

    /**
     * Close explicitly the database connection
     *
     * @return bool
     */
    public function disconnect()
    {
        if (is_null($this->connection)) {
            return false;
        }
        mysqli_close($this->connection);
        $this->connection = null;
        return true;
    }

    /**
     * Close automatically the database connection when the instance of the class is destroyed
     */
    public function __destruct()
    {
        $this->disconnect();
    }
}