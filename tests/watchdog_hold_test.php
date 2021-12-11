<?php

    require 'ppm';
    require 'net.intellivoid.ssm';


    $watchdog_service = new \ssm\Classes\WatchDog();

    print('Locking SystemLockFile' . PHP_EOL);
    try
    {
        $watchdog_service->run();
        print('Success' . PHP_EOL);
        print('Holding for 10 seconds' . PHP_EOL);
        sleep(10);
        print('Done.' . PHP_EOL);
    }
    catch (\ssm\Exceptions\WatchDogAlreadyRunningException $e)
    {
        print('Failed, ' . $e->getMessage() . PHP_EOL);
    }