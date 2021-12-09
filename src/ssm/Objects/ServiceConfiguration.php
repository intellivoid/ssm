<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace ssm\Objects;

    use ssm\Classes\IniReader;
    use ssm\Classes\IniWriter;
    use ssm\Exceptions\ConfigurationException;

    class ServiceConfiguration
    {
        /**
         * The main execution point for the service
         *
         * @var LunchCondition|null
         */
        public $Start;

        /**
         * The lunch condition before starting the main execution point
         *
         * @var LunchCondition|null
         */
        public $StartPre;

        /**
         * The lunch condition after starting the main execution point
         *
         * @var LunchCondition|null
         */
        public $StartPost;

        /**
         * The lunch condition to execute when the service stops
         *
         * @var LunchCondition|null
         */
        public $Stop;

        /**
         * The lunch condition to execute before running the stop condition
         *
         * @var LunchCondition|null
         */
        public $StopPre;

        /**
         * The lunch condition to execute after running the stop condition
         *
         * @var LunchCondition|null
         */
        public $StopPost;

        /**
         * The lunch condition to execute when the service has successfully loaded
         *
         * @var LunchCondition|null
         */
        public $Reload;

        /**
         * The lunch condition to execute before the service reloads
         *
         * @var LunchCondition|null
         */
        public $ReloadPre;

        /**
         * The lunch condition to execute after the service successfully reloads
         *
         * @var LunchCondition|null
         */
        public $ReloadPost;

        /**
         * The lunch condition to execute after the service fails
         *
         * @var LunchCondition|null
         */
        public $OnFailure;

        /**
         * The lunch condition to execute after the service has successfully executed and exited
         *
         * @var LunchCondition|null
         */
        public $OnSuccess;

        /**
         * Constructs object from a INI file
         *
         * @param string $file
         * @return ServiceConfiguration
         * @throws ConfigurationException
         */
        public static function fromFile(string $file): ServiceConfiguration
        {
            $ini_reader = new IniReader();
            return ServiceConfiguration::fromArray($ini_reader->readFile($file));
        }

        /**
         * @return string
         * @throws ConfigurationException
         */
        public function toConfiguration(): string
        {
            $ini_writer = new IniWriter();
            return $ini_writer->writeToString($this->toArray());
        }

        /**
         * Returns an array representation of the object
         *
         * @return array
         */
        public function toArray(): array
        {
            $return_results = [];

            if($this->Start !== null)
                $return_results['start'] = $this->Start->toArray();

            if($this->StartPre !== null)
                $return_results['start_pre'] = $this->StartPre->toArray();

            if($this->StartPost !== null)
                $return_results['start_post'] = $this->StartPost->toArray();

            if($this->Stop !== null)
                $return_results['stop'] = $this->Stop->toArray();

            if($this->StopPre !== null)
                $return_results['stop_pre'] = $this->StopPre->toArray();

            if($this->StopPost !== null)
                $return_results['stop_post'] = $this->StopPost->toArray();

            if($this->Reload !== null)
                $return_results['reload'] = $this->Reload->toArray();

            if($this->ReloadPre !== null)
                $return_results['reload_pre'] = $this->ReloadPre->toArray();

            if($this->ReloadPost !== null)
                $return_results['reload_post'] = $this->ReloadPost->toArray();

            if($this->OnFailure !== null)
                $return_results['on_failure'] = $this->OnFailure->toArray();

            if($this->OnSuccess !== null)
                $return_results['on_success'] = $this->OnSuccess->toArray();

            return $return_results;
        }

        /**
         * Constructs object from an array representation
         *
         * @param array $data
         * @return ServiceConfiguration
         */
        public static function fromArray(array $data): ServiceConfiguration
        {
            $ServiceConfigurationObject = new ServiceConfiguration();

            if(isset($data['start']))
                $ServiceConfigurationObject->Start = LunchCondition::fromArray($data['start']);

            if(isset($data['start_pre']))
                $ServiceConfigurationObject->StartPre = LunchCondition::fromArray($data['start_pre']);

            if(isset($data['start_post']))
                $ServiceConfigurationObject->StartPost = LunchCondition::fromArray($data['start_post']);

            if(isset($data['stop']))
                $ServiceConfigurationObject->Stop = LunchCondition::fromArray($data['stop']);

            if(isset($data['stop_pre']))
                $ServiceConfigurationObject->StopPre = LunchCondition::fromArray($data['stop_pre']);

            if(isset($data['stop_post']))
                $ServiceConfigurationObject->StopPost = LunchCondition::fromArray($data['stop_post']);

            if(isset($data['reload']))
                $ServiceConfigurationObject->Reload = LunchCondition::fromArray($data['reload']);

            if(isset($data['reload_pre']))
                $ServiceConfigurationObject->ReloadPre = LunchCondition::fromArray($data['reload_pre']);

            if(isset($data['reload_post']))
                $ServiceConfigurationObject->Reload = LunchCondition::fromArray($data['reload_post']);

            if(isset($data['on_failure']))
                $ServiceConfigurationObject->OnFailure = LunchCondition::fromArray($data['on_failure']);

            if(isset($data['on_success']))
                $ServiceConfigurationObject->OnSuccess = LunchCondition::fromArray($data['on_success']);

            return $ServiceConfigurationObject;
        }
    }