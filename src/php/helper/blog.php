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
    }