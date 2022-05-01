<?php

declare(strict_types=1);

namespace PayByBank\Infrastructure\Persistence\Drivers;

use MongoDB\Client;
use MongoDB\Collection;
use MongoDB\Database;

class MongoDriver
{
    private readonly string $dbName;

    private readonly Database $db;

    private readonly Client $connection;

    public function __construct()
    {
        $this->dbName = $_ENV['DB'];
        $mongoUri = "mongodb://{$_ENV['DB_USER']}:{$_ENV['DB_USER_PASSWORD']}@{$_ENV['DB_HOST']}:{$_ENV['DB_PORT']}/?authSource={$this->dbName}";
        $this->connection = new Client($mongoUri);
        $this->db = $this->connection->selectDatabase($this->dbName);
    }

    public function selectCollection(string $collectionName): Collection
    {
        return $this->db->selectCollection($collectionName);
    }
}
