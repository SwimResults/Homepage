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

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, "https://de.gravatar.com/$hash.json");

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

            $json = curl_exec($ch);

            curl_close($ch);


            $data =  json_decode($json, TRUE);
            if (!isset($data["entry"])) return NULL;
            if (count($data["entry"]) <= 0) return NULL;
            $author = $data["entry"][0];
            self::$cache[$hash] = $author;
            return $author;
        }
    }