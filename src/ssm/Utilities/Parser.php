<?php

    namespace ssm\Utilities;

    class Parser
    {
        /**
         * Parses an argument string
         *
         * @param string $input
         * @return array
         */
        public static function parseArgumentsString(string $input): array
        {
            return self::separate($input, ':');
        }

        /**
         * Custom seperator with escape handler
         *
         * @param $string
         * @param string $separator
         * @param string $escape
         * @return array
         */
        public static function separate($string, $separator = '|', $escape = '\\'): array
        {
            $segments = [];
            $string = (string) $string;
            do
            {
                $segment = '';
                do
                {
                    $segment_length = strcspn($string, "$separator$escape");
                    if ($segment_length)
                    {
                        $segment .= substr($string, 0, $segment_length);
                    }

                    if (strlen($string) <= $segment_length)
                    {
                        $string = null;
                        break;
                    }

                    if ($escaped = $string[$segment_length] == $escape)
                    {
                        $segment .= (string)substr($string, ++$segment_length, 1);
                    }

                    $string = (string) substr($string, ++$segment_length);
                } while ($escaped);
                $segments[] = $segment;
            } while ($string !== null);
            return $segments;
        }
    }