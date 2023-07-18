<?php

    class BlogHelper {

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
            $json = file_get_contents("https://de.gravatar.com/$hash.json");
            $data =  json_decode($json, TRUE);
            if (!isset($data["entry"])) return NULL;
            if (count($data["entry"]) <= 0) return NULL;
            return $data["entry"][0];
        }
    }