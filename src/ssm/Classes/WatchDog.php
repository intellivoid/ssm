<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace ssm\Classes;

    use ssm\Exceptions\WatchDogAlreadyRunningException;
    use ssm\Utilities\PathFinder;

    class WatchDog
    {
        /**
         * @var string
         */
        private $SystemLockFile;

        /**
         * @var resource|null
         */
        private $SystemLockResource;

        public function __construct()
        {
            $this->SystemLockFile = PathFinder::getMainPath() . DIRECTORY_SEPARATOR . '.lock';
            $this->SystemLockResource = null;
        }

        /**
         * Starts the WatchDog instance by locking its process file
         *
         * @throws WatchDogAlreadyRunningException
         */
        public function run()
        {
            if($this->SystemLockResource !== null)
                throw new WatchDogAlreadyRunningException('The WatchDog is already running');

            if(file_exists($this->SystemLockFile))
            {
                if(is_writable($this->SystemLockFile) == false)
                    throw new WatchDogAlreadyRunningException('Cannot write to SystemLockFile, is another WatchDog instance already running?');


                $this->SystemLockResource = fopen($this->SystemLockFile, 'w+');
                $count = 0;
                $timeout_seconds = 3;
                $got_lock = true;
                while(!flock($this->SystemLockResource, LOCK_EX | LOCK_NB, $would_block))
                {
                    if($would_block && $count++ < $timeout_seconds)
                    {
                        sleep(1);
                    }
                    else
                    {
                        $got_lock = false;
                        break;
                    }
                }
                if($got_lock == false)
                {
                    fclose($this->SystemLockResource);
                    $this->SystemLockResource = null;
                    throw new WatchDogAlreadyRunningException('Cannot lock SystemLockFile, is another WatchDog instance is already running?');
                }

                fclose($this->SystemLockResource);
                $this->SystemLockResource = null;

                @unlink($this->SystemLockFile);
                if(file_exists($this->SystemLockFile))
                    throw new WatchDogAlreadyRunningException('Cannot remove SystemLockFile, is another WatchDog instance is already running?');
            }

            touch($this->SystemLockFile);
            $this->SystemLockResource = fopen($this->SystemLockFile, 'w+');
            flock($this->SystemLockResource, LOCK_EX);
        }

        /**
         * Removes the SystemLockFile while successfully stopping the WatchDog process
         */
        public function stop()
        {
            if($this->SystemLockResource !== null)
            {
                flock($this->SystemLockResource, LOCK_UN);
                fclose($this->SystemLockResource);
                unlink($this->SystemLockFile);
            }
        }

        /**
         * Stops the WatchDog process when destructing
         */
        public function __destruct()
        {
            $this->stop();
        }
    }