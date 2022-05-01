<?php

declare(strict_types=1);

namespace PayByBank\Infrastructure\Persistence\Adapters;

use MongoDB\Client;
use MongoDB\Collection;
use MongoDB\Database;

class MongoAdapter
{
    private readonly Database $db;

    private readonly Client $connection;

    public function __construct(string $db, string $host, string $dbUser, string $dbPassword, string $dbPort)
    {
        $mongoUri = "mongodb://{$dbUser}:{$dbPassword}@{$host}:{$dbPort}/?authSource={$db}";
        $this->connection = new Client($mongoUri);
        $this->db = $this->connection->selectDatabase($db);
    }


    public function selectCollection(string $collectionName): Collection
    {
        return $this->db->selectCollection($collectionName);
    }
}
