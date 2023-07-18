<?php

    class BlogHelper {

        public static array $cache = array();

        public static function getBlogPosts() {

            $pdo = DatabaseHelper::getPDO();

            $result = array();

            $sql = "SELECT * FROM blog ORDER BY created_at DESC";
            foreach ($pdo->query($sql) as $row) {
                $result[] = $row;
            }

            return $result;
//
//            $data = file_get_contents("data/blogposts.json");
//            return json_decode($data, TRUE);
        }

        public static function getRandomBlogPosts($n, $e = 0) {

            $pdo = DatabaseHelper::getPDO();
            $result = array();
            $sql = "SELECT * FROM blog WHERE id <> ".$e." ORDER BY RAND()";
            foreach ($pdo->query($sql) as $row) {
                if ($n <= 0) break;
                if ($row["published_at"]) {
                    $pub = strtotime($row["published_at"]);
                    if ($pub > time()) continue;
                }
                $result[] = $row;
                $n--;
            }

            return $result;
//
//            $data = file_get_contents("data/blogposts.json");
//            return json_decode($data, TRUE);
        }

        public static function getBlogPostById($id) {
            $pdo = DatabaseHelper::getPDO();

            $sql = "SELECT * FROM blog WHERE id = ".$id;
            foreach ($pdo->query($sql) as $row) {
                return $row;
            }
            return NULL;
        }

        public static function getRandomBlogPost() {
            $pdo = DatabaseHelper::getPDO();

            $sql = "SELECT * FROM blog ORDER BY RAND()";
            foreach ($pdo->query($sql) as $row) {
                return $row;
            }
            return NULL;
        }

        public static function getAuthorInfo($mail) {
            $hash = md5(strtolower(trim($mail)));
            if (array_key_exists($hash, self::$cache)) {
                return self::$cache[$hash];
            }
            $json = file_get_contents("https://de.gravatar.com/$hash.json");
            $data =  json_decode($json, TRUE);
            if (!isset($data["entry"])) return NULL;
            if (count($data["entry"]) <= 0) return NULL;
            $author = $data["entry"][0];
            self::$cache[$hash] = $author;
            return $author;
        }
    }