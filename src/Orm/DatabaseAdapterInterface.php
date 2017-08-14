<?php

namespace Orm;

interface DatabaseAdapterInterface
{
    /**
     * Connect to MySQL
     *
     * @return \mysqli
     */
    function connect();

    /**
     * @return mixed
     */
    function disconnect();

    /**
     * Execute the specified query
     *
     * @param $query
     * @return array|bool|object
     */
    function query($query);

    /**
     * Fetch a single row from the current result set (as an associative array)
     *
     * @return array|bool|null
     */
    function fetch();

    /**
     * Perform a SELECT statement
     *
     * @param $table
     * @param string $where
     * @param string $fields
     * @param string $order
     * @param null $limit
     * @param null $offset
     * @return array|bool|object
     */
    function select($table, $conditions = '', $fields = '*', $order = '', $limit = null, $offset = null);

    /**
     * Perform an INSERT statement
     *
     * @param $table
     * @param array $data
     * @return int|null|string
     */
    function insert($table, array $data);

    /**
     * Perform an UPDATE statement
     *
     * @param $table
     * @param array $data
     * @param string $where
     * @return int
     */
    function update($table, array $data, $conditions);

    /**
     * Perform a DELETE statement
     *
     * @param $table
     * @param string $where
     * @return int
     */
    function delete($table, $conditions);

    /**
     * Get the insertion ID
     *
     * @return int|null|string
     */
    function getInsertId();

    /**
     * Get the number of rows returned by the current result set
     *
     * @return int
     */
    function countRows();

    /**
     * @return mixed
     */
    function getAffectedRows();
}

