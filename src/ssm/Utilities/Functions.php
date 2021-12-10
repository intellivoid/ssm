<?php

    namespace ssm\Utilities;

    class Functions
    {
        /**
         * @param $haystack
         * @param $needle
         * @param false $ignoreCase
         * @return false|int
         */
        public static function str_contains($haystack, $needle, bool $ignoreCase=false)
        {
            if ($ignoreCase)
            {
                $haystack = strtolower($haystack);
                $needle   = strtolower($needle);
            }

            $needlePos = strpos($haystack, $needle);
            return ($needlePos === false ? false : ($needlePos+1));
        }
    }