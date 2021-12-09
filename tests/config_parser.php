<?php

    require 'ppm';
    require 'net.intellivoid.ssm';

    $service_configuration = \ssm\Objects\ServiceConfiguration::fromFile(__DIR__ . DIRECTORY_SEPARATOR . 'example.service');
    var_dump($service_configuration);

    var_dump($service_configuration->toConfiguration());