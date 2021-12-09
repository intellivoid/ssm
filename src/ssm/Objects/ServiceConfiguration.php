<?php

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

            return $ServiceConfigurationObject;
        }
    }