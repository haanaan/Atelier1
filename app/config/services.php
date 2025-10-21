<?php
return [
    // Connexion PDO
    'charlyoutils_db' => static function ($c): PDO {
        $dbConfig = $c->get('settings')['charly_db'];
        $driver  = $dbConfig['driver'] ?? 'pgsql';
        $host    = $dbConfig['host'] ?? 'charyoutils.db';
        $dbname  = $dbConfig['dbname'] ?? 'charlyoutils';
        $user    = $dbConfig['username'] ?? 'charlyoutils';
        $pass    = $dbConfig['password'] ?? 'charlyoutils';
        $charset = $dbConfig['charset'] ?? 'utf8mb4';

        $dsn = $driver === 'mysql'
            ? "mysql:host={$host};dbname={$dbname};charset={$charset}"
            : "pgsql:host={$host};dbname={$dbname}";

        return new PDO($dsn, $user, $pass);
    },
];