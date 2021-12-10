<?php

    namespace ssm\Utilities;

    class PathFinder
    {
        /**
         * The main PPM data path
         *
         * @param bool $create
         * @return string
         */
        public static function getMainPath(bool $create=false): string
        {
            $path = realpath(DIRECTORY_SEPARATOR . 'etc');
            $path .= DIRECTORY_SEPARATOR . 'ssm';

            if($create)
            {
                if(file_exists($path) == false)
                {
                    mkdir($path);
                }
            }

            return $path;
        }

        /**
         * Gets the directory where all PID lock files are housed in
         *
         * @param bool $create
         * @return string
         */
        public static function getPidLockPath(bool $create=false): string
        {
            if($create)
            {
                if(file_exists(self::getMainPath($create) . DIRECTORY_SEPARATOR . '.proc') == false)
                {
                    mkdir(self::getMainPath($create) . DIRECTORY_SEPARATOR . '.proc');
                }
            }

            return self::getMainPath($create) . DIRECTORY_SEPARATOR . '.proc';
        }

        /**
         * Gets the directory where all ctl queries are written to
         *
         * @param bool $create
         * @return string
         */
        public static function getCtlRunPath(bool $create=false): string
        {
            if($create)
            {
                if(file_exists(self::getMainPath($create) . DIRECTORY_SEPARATOR . '.run') == false)
                {
                    mkdir(self::getMainPath($create) . DIRECTORY_SEPARATOR . '.run');
                }
            }

            return self::getMainPath($create) . DIRECTORY_SEPARATOR . '.run';
        }
    }