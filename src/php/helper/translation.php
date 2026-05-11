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

        public static function e($key): void {
            echo(self::t($key));
        }

        public function __toString() {
            return "";
        }

    }

    /**
     * Generate a URL with proper language prefix
     * /something for German (default, implicit)
     * /de/something for German (explicit language switch)
     * /en/something for English
     */
    function buildLink($path, $lang = null) {
        // Remove any leading slashes
        $path = ltrim($path, '/');
        
        if ($lang === null) {
            // No explicit language - use session and apply appropriate prefix
            $lang = $_SESSION["lang"] ?? $GLOBALS['lang'] ?? 'de';
            
            if ($lang === 'en') {
                return '/en/' . $path;
            } else {
                return '/' . $path;  // German uses no prefix implicitly
            }
        } else {
            // Explicit language passed (from language switcher) - always use prefix
            if ($lang === 'en') {
                return '/en/' . $path;
            } else {
                return '/de/' . $path;  // German always uses /de/ when explicitly chosen
            }
        }
    }

    /**
     * Get the current base URL depending on language
     * Returns '' for German, '/en' for English
     */
    function getLangPrefix($lang = null) {
        if ($lang === null) {
            // Priority: session language (most reliable) > global lang > default to de
            $lang = $_SESSION["lang"] ?? $GLOBALS['lang'] ?? 'de';
        }
        
        if ($lang === 'en') {
            return '/en';
        } else {
            return '';
        }
    }

    // Language detection from URL parameters and session
    // Priority: $_GET["lang"] (from URL rewrite) > $_SESSION["lang"] > browser language > 'de'
    $lang = 'de';
    
    // Check URL parameter first (set by .htaccess rewrite)
    if (isset($_GET["lang"]) && in_array($_GET["lang"], ['en', 'de'])) {
        $lang = $_GET["lang"];
        $_SESSION["lang"] = $lang;  // Store in session for persistence
    }
    // Check session
    else if (isset($_SESSION["lang"]) && in_array($_SESSION["lang"], ['en', 'de'])) {
        $lang = $_SESSION["lang"];
    }
    // Fall back to browser Accept-Language
    else if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        if (!in_array($lang, ['en', 'de'])) {
            $lang = 'de';
        }
    }
    
    // Ensure session is always set for persistence
    $_SESSION["lang"] = $lang;
    
    T::init($lang);

