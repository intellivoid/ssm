<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace ssm\Objects;

    use ssm\Abstracts\Invoker;
    use ssm\Abstracts\ServiceState;

    class Pid
    {
        /**
         * The name of the service
         *
         * @var string
         */
        public $ServiceName;

        /**
         * The current state of the service
         *
         * @var int|ServiceState
         */
        public $CurrentState;

        /**
         * The Unix Timestamp that indicates when the service has started
         *
         * @var int
         */
        public $StartedOn;

        /**
         * Indicates who started the service
         *
         * @var int|Invoker
         */
        public $StartedBy;

        /**
         * The current process ID that the WatchDog considers the main
         *
         * @var int
         */
        public $CurrentProcessID;

        /**
         * The last report made by the WatchDog
         *
         * @var int
         */
        public $LastTick;

        /**
         * Returns an array representation of the object
         *
         * @return array
         */
        public function toArray(): array
        {
            return [
                0x1001 => $this->ServiceName,
                0x1002 => $this->CurrentState,
                0x1003 => $this->StartedOn,
                0x1004 => $this->StartedBy,
                0x1005 => $this->CurrentProcessID,
                0x1006 => $this->LastTick
            ];
        }
    }