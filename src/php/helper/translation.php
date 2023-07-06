<?php

    class T {
        private static mixed $translation_data;
        private static mixed $fallback_data;

        public static function init($lang): void
        {
            $translation_file1 = file_get_contents("lang/i18n/de.json");
            self::$fallback_data = json_decode($translation_file1, TRUE);
            self::$translation_data = self::$fallback_data;
            if (file_exists("lang/i18n/".$lang.".json")) {
                $translation_file = file_get_contents("lang/i18n/".$lang.".json");
                self::$translation_data = json_decode($translation_file, TRUE);
            }

        }

        public static function t($key): string {
            $key_split = explode(".", $key);
            $data = self::$translation_data;
            foreach ($key_split as $split) {
                if (!array_key_exists($split, $data)) {
                    $data = FALSE;
                    break;
                }
                $data = $data[$split];
            }
            if ($data) return $data;

            $data = self::$fallback_data;
            foreach ($key_split as $split) {
                if (!array_key_exists($split, $data)) {
                    $data = FALSE;
                    break;
                }
                $data = $data[$split];
            }
            if ($data) return $data;

            return $key;
        }

        public function __toString() {
            return "";
        }

    }

    $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    if (isset($_SESSION["lang"]))
        $lang = $_SESSION["lang"];
    T::init($lang);
