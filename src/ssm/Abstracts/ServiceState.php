<?php

    namespace ssm\Abstracts;

    abstract class ServiceState
    {
        /**
         * Indicates that the service has been stopped by the user or isn't running
         */
        const Stopped = 0xf1900;

        /**
         * Indicates that the service is currently starting up
         */
        const Starting = 0xf1901;

        /**
         * Indicates that the service is currently running
         */
        const Running = 0xf1902;

        /**
         * Indicates that the service is not running due and failed to start due to an error
         */
        const Failed = 0xf1903;

        /**
         * Indicates that the service is currently in the process of restarting
         */
        const Restarting = 0xf1905;
    }