<?php

    class Env {

        public static function getAppUrl() {
            $app_url = getenv("SR_APP_URL");
            if (!$app_url) $app_url = "https://app.swimresults.de";
            return $app_url;
        }

    }