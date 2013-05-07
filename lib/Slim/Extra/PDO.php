<?php

namespace Slim\Extra;

class PDO {
    private static $instance;

    public static function getInstance() {
        if (isset(self::$instance) === true) {
            return self::$instance;
        }

        $app = \Slim\Slim::getInstance();
        $config = (object) $app->config('db');
        self::$instance = new \PDO(
            sprintf(
                '%s:host=%s; port=%d; dbname=%s; charset=utf8;'
                , @$config->driver ?: 'mysql'
                , @$config->host ?: 'localhost'
                , @$config->port ?: 3306
                , @$config->dbname
            ),
            @$config->username,
            @$config->password, array(
                \PDO::MYSQL_ATTR_INIT_COMMAND
                    => 'SET CHARACTER SET `utf8`')
        );

        self::$instance->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        self::$instance->exec('SET time_zone = "+09:00"');
        self::$instance->exec('SET innodb_lock_wait_timeout = 180');

        return self::$instance;
    }
}
