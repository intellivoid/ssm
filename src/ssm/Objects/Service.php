<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace ssm\Objects;

    class Service
    {
        /**
         * A description of the service
         *
         * @var string
         */
        public $Description;

        /**
         * Indicates if the service should automatically start when ssm starts
         *
         * @var bool
         */
        public $AutomaticallyStart = false;

        /**
         * Indicates if process logging is enabled, when enabled any stdout or stderr of any
         * lunch conditions will be included in the service logging
         *
         * @var bool
         */
        public $EnableProcessLogging = true;

        /**
         * Indicates if watchdog logging is enabled, when enabled any logging events from ssm's watchdog
         * will be included in the service logging
         *
         * @var bool
         */
        public $EnableWatchdogLogging = true;

        /**
         * The service log file location where all the entries are written to
         *
         * @var string
         */
        public $LogFile = '/var/log/$service.log';

        /**
         * The maximum size for a log file before ssm starts deleting old entries
         *
         * @var string
         */
        public $MaxLogSize = '28M';

        /**
         * Returns an array representation of the object
         *
         * @return array
         */
        public function toArray(): array
        {
            return [
                'Description' => $this->Description,
                'AutomaticallyStart' => ($this->AutomaticallyStart == true ? 'true' : 'false'),
                'EnableProcessLogging' => ($this->EnableProcessLogging == true ? 'true' : 'false'),
                'EnableWatchdogLogging' => ($this->EnableWatchdogLogging == true ? 'true' : 'false'),
                'LogFile' => $this->LogFile,
                'MaxLogSize' => $this->MaxLogSize,
            ];
        }

        public static function fromArray(array $data): Service
        {
            $ServiceObject = new Service();

            if(isset($data['Description']))
                $ServiceObject->Description = $data['Description'];

            if(isset($data['AutomaticallyStart']))
                $ServiceObject->AutomaticallyStart = (bool)$data['AutomaticallyStart'];

            if(isset($data['EnableProcessLogging']))
                $ServiceObject->EnableProcessLogging = (bool)$data['EnableProcessLogging'];

            if(isset($data['EnableWatchdogLogging']))
                $ServiceObject->EnableWatchdogLogging = (bool)$data['EnableWatchdogLogging'];

            if(isset($data['LogFile']))
                $ServiceObject->LogFile = $data['LogFile'];

            if(isset($data['MaxLogSize']))
                $ServiceObject->MaxLogSize = $data['MaxLogSize'];

            return $ServiceObject;
        }
    }