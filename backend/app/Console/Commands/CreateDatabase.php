<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PDO;

class CreateDatabase extends Command
{
    protected $signature = 'db:create {name?}';
    protected $description = 'Create a new MySQL database';

    public function handle()
    {
        $database = $this->argument('name') ?: config('database.connections.mysql.database');

        try {
            $pdo = new PDO(
                sprintf(
                    'mysql:host=%s;port=%d;',
                    config('database.connections.mysql.host'),
                    config('database.connections.mysql.port')
                ),
                config('database.connections.mysql.username'),
                config('database.connections.mysql.password')
            );

            $pdo->exec(sprintf(
                'CREATE DATABASE IF NOT EXISTS %s CHARACTER SET %s COLLATE %s;',
                $database,
                config('database.connections.mysql.charset'),
                config('database.connections.mysql.collation')
            ));

            $this->info(sprintf('Successfully created database %s', $database));
        } catch (\Exception $e) {
            $this->error(sprintf('Failed to create database %s', $database));
            $this->error($e->getMessage());
        }
    }
} 