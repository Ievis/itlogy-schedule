<?php

namespace App\Components\Db;

use PDO;

class QueryBuilder
{
    public PDO $pdo;
    public string $query = '';
    public array $parameters = [];
    public string $table = '';
    public string $alias;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function select(array $cols = ['*'])
    {
        $query = 'SELECT ' . implode(',', $cols) . ' FROM ' . $this->table . ' ' . $this->alias;
        $this->appendQuery($query);

        return $this;
    }

    public function table(string $table, string $alias = '')
    {
        $this->table = $table;
        $this->alias = $alias;

        return $this;
    }

    public function join(string $table, string $alias, string $condition)
    {
        $query = ' JOIN ' . $table . ' ' . $alias . ' ON ' . $condition;
        $this->appendQuery($query);

        return $this;
    }

    public function where(string $column, string $operand, mixed $value)
    {
        $operator = str_contains($this->query, 'WHERE')
            ? ' AND '
            : ' WHERE ';
        $query = $operator . $column . ' ' . $operand . ' ' . ':' . $column;
        $this->appendQuery($query);
        $this->collectParameter($column, $value);

        return $this;
    }

    public function get(int $mode = PDO::FETCH_ASSOC)
    {
        $statement = $this->flush();

        return $statement->fetchAll($mode);
    }

    public function query(string $query)
    {
        $this->query = $query;

        return $this;
    }

    public function insert(array $data)
    {
        $query = 'INSERT INTO ' . $this->table . PHP_EOL
            . '('
            . implode(', ', array_keys($data))
            . ')' . PHP_EOL
            . 'VALUES ' . PHP_EOL
            . '('
            . ':' . implode(', :', array_keys($data))
            . ')';
        $this->appendQuery($query);
        $this->collectParameters($data);

        return $this;
    }

    public function union($sub_query)
    {
        $query = ' UNION ' .
            '(' .
            $sub_query
            . ')';
        $this->appendQuery($query);

        return $this;
    }

    public function limit(int $limit)
    {
        $query = ' LIMIT ' . $limit;
        $this->appendQuery($query);

        return $this;
    }

    public function offset(int $offset)
    {
        $query = ' OFFSET ' . $offset;
        $this->appendQuery($query);

        return $this;
    }

    public function flush()
    {
        $statement = $this->pdo->prepare($this->query);
        $statement->execute($this->parameters);
        $this->flushBuilderData();

        return $statement;
    }

    public function flushBuilderData()
    {
        $this->table = '';
        $this->query = '';
        $this->parameters = [];
    }

    public function getSQL()
    {
        return $this->query;
    }

    private function appendQuery(string $query)
    {
        $this->query = $this->query . $query;
    }

    private function collectParameter(string $parameter_name, mixed $parameter)
    {
        $this->parameters[':' . $parameter_name] = $parameter;
    }

    private function collectParameters(array $data)
    {
        foreach ($data as $parameter_name => $parameter) {
            $this->collectParameter($parameter_name, $parameter);
        }
    }

    public function executeDDL(string $ddl)
    {
        $this->pdo->query($ddl);
        try {
            $this->pdo->commit();
        } catch (\Exception $e) {

        }
    }
}