<?php


    class T {
        private static mixed $translation_data;

        public static function init(): void
        {
            $translation_file = file_get_contents("lang/i18n/de.json");
            self::$translation_data = json_decode($translation_file, TRUE);
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
            return $key;
        }

        public function __toString() {
            return "";
        }

    }

    T::init();
