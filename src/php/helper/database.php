<?php

    class DatabaseHelper {

        private static $pdo;

        public static function init_database()
        {
            $db_host = getenv("SR_HOMEPAGE_DB_HOST");
            $db_port = getenv("SR_HOMEPAGE_DB_PORT");
            $db_name = getenv("SR_HOMEPAGE_DB_NAME");
            $db_user = getenv("SR_HOMEPAGE_DB_USER");
            $db_password = getenv("SR_HOMEPAGE_DB_PASSWORD");

            if (!$db_host) die("SR_HOMEPAGE_DB_HOST is not set");
            if (!$db_name) die("SR_HOMEPAGE_DB_NAME is not set");
            if (!$db_port) $db_port = 3306;

            self::$pdo = new PDO('mysql:host='.$db_host.':'.$db_port.';dbname='.$db_name, $db_user, $db_password);
        }

        public static function getPDO() {
            return self::$pdo;
        }


    }

    DatabaseHelper::init_database();