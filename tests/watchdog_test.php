<?php

    require 'ppm';
    require 'net.intellivoid.ssm';


    $watchdog_service = new \ssm\Classes\WatchDog();

    print('Locking SystemLockFile' . PHP_EOL);
    try
    {
        $watchdog_service->run();
        print('Success' . PHP_EOL);
    }
    catch (\ssm\Exceptions\WatchDogAlreadyRunningException $e)
    {
        print('Failed, ' . $e->getMessage() . PHP_EOL);
    }