<?php

    class BlogHelper {

        public static function getBlogPosts() {
            $data = file_get_contents("data/blogposts.json");
            return json_decode($data, TRUE);
        }
    }