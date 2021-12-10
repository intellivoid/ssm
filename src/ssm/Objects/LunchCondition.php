<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace ssm\Objects;

    use ssm\Abstracts\RestartCondition;
    use ssm\Abstracts\Time;
    use ssm\Utilities\Parser;

    class LaunchCondition
    {
        /**
         * The main execution point of the lunch condition
         *
         * @var string|null
         */
        public $Exec = null;

        /**
         * Command-line Arguments to pass on to the execution point, separated by `:`
         * to escape the separator use `\:`, in this example the following arguments
         * are passed: `GIT_API_KEY=Hello!` & `TEST_PARTY=netkas:netkas`
         *
         * @var array|null
         */
        public $Arguments = null;

        /**
         * Environment variables to apply to the execution point, separated by :, to escape
         * the separator use \:, in this example the following arguments are passed:
         * GIT_API_KEY=Hello! & TEST_PARTY=netkas:netkas
         *
         * @var array|null
         */
        public $EnvironmentVariables = null;

        /**
         * The working directory that the process should be running in if configured
         *
         * @var string|null
         */
        public $CurrentWorkingDirectory = null;

        /**
         * Configures whether the executed process shall be restarted when the process exits, is killed,
         * or a timeout reached. Takes one of the no, on-success, on-failure, unless-stopped or always.
         * no Means the process will not automatically restart for any reason whatsoever. on-failure will
         * only restart if the process's lunch condition indicates failure. unless-stopped will always
         * restart the process unless stopped manually this is only applicable to the start lunch condition.
         * always will always restart the process, this is only applicable to the start lunch condition.
         *
         * @var string
         */
        public $Restart = RestartCondition::OnFailure;

        /**
         * If Restart is configured to always or on-failure, this indicates how many times can the process
         * restart due to failure conditions before it is considered failed and stops the service.
         *
         * @var string|int
         */
        public $MaxFailureRestarts = Time::Infinity;

        /**
         * If Restart is configured to always or on-success or and if MaxFailureRestarts is set to infinity
         * this indicates how many times the process can restart due to failure and or success conditions
         * before it is considered failed and stops the service.
         *
         * @var string|int
         */
        public $MaxAutomaticRestarts = Time::Infinity;

        /**
         * ONLY APPLICABLE TO start's lunch condition. Indicates the time to wait for a start-up, this is
         * conditioned by other lunch parameters such as start_pre where if the preconditions are not
         * completed within the configured time (seconds) then the service will be considered failed
         * and shutdown again.
         *
         * @var string|int
         */
        public $TimeoutStartSeconds = Time::Infinity;

        /**
         * ONLY APPLICABLE TO start's lunch condition. Indicates the time to wait for the shutdown, this
         * is conditioned by other lunch parameters such as stop_pre & stop_post and including the main
         * shutdown process of start, where if all conditions fails to exit or finish executing during the
         * configured time (seconds) then the service will be considered failed and forced to shut down.
         *
         * @var string|int
         */
        public $TimeoutStopSeconds = Time::Infinity;

        /**
         * Configured the maximum time for a service to run, if this is used and the process has been
         * active for longer than the specified time then it will be terminated and put into a failure
         * state, this value's unit is in seconds.
         *
         * @var string|int
         */
        public $RuntimeMaxSeconds = Time::Infinity;

        /**
         * Takes a list of exit status codes that, when returned by the executed process, will be
         * considered a successful termination, in addition to the normal successful exit status 0.
         * These exit codes are separated by spaces and only accepts integers, anything else will be ignored.
         *
         * @var int[]
         */
        public $SuccessExitStatus = [0, 75, 250];

        /**
         * Takes a list of exit status codes that, when returned by the executed process will be considered an
         * error, in addition to the exit code 1. These exit codes are separated by spaces and only
         * accepts integers, anything else will be ignored.
         *
         * @var int[]
         */
        public $ErrorExitStatus = [1];

        /**
         * Takes a list of exit status codes that, when returned by the executed process will prevent automatic
         * service restarts, regardless of the restart setting configured with Restart, these exit codes are
         * separated by spaces and only accepts integers, anything else will be ignored.
         *
         * @var int[]|null
         */
        public $RestartPreventExitStatus = null;

        /**
         * Takes a list of exit status definitions that, when returned by the executed process will force automatic
         * service restarts regardless of the restart setting configured with Restart, these exit codes are
         * separated by spaces and only accepts integers, anything else will be ignored.
         *
         * @var int[]|null
         */
        public $RestartForceExitStatus = null;

        /**
         * Indicates if the standard output from this executed process should be logged or ignored, this only accepts
         * true or false as a value. Logging is configured globally with in logging section of the service's
         * configuration file.
         *
         * @var bool
         */
        public $LogStdout = true;

        /**
         * Indicates if the standard error output from this executed process should be logged or ignored, this only
         * accepts true or false as a value. Logging is configured globally with in logging section of the service's
         * configuration file.
         *
         * @var bool
         */
        public $LogStderr = true;

        /**
         * Returns an array representation of the object
         *
         * @return array
         */
        public function toArray(): array
        {
            $results = [
                'Exec' => $this->Exec,
                'Args' => ($this->Arguments == null ? null : implode(':', $this->Arguments)),
                'Env' => ($this->EnvironmentVariables == null ? null : implode(':', $this->EnvironmentVariables)),
                'Cwd' => $this->CurrentWorkingDirectory,
                'Restart' => $this->Restart,
                'MaxFailureRestarts' => $this->MaxFailureRestarts,
                'MaxAutomaticRestarts' => $this->MaxAutomaticRestarts,
                'TimeoutStartSec' => $this->TimeoutStartSeconds,
                'TimeoutStopSec' => $this->TimeoutStopSeconds,
                'RuntimeMaxSec' => $this->RuntimeMaxSeconds,
                'SuccessExitStatus' => ($this->SuccessExitStatus == null ? null : implode(' ', $this->SuccessExitStatus)),
                'ErrorExitStatus' => ($this->ErrorExitStatus == null ? null : implode(' ', $this->ErrorExitStatus)),
                'RestartPreventExitStatus' => ($this->RestartPreventExitStatus == null ? null : implode(' ', $this->RestartPreventExitStatus)),
                'RestartForceExitStatus' => ($this->RestartForceExitStatus == null ? null : implode(' ', $this->RestartForceExitStatus)),
                'LogStdout' => ($this->LogStdout == true ? 'true' : 'false'),
                'LogStderr' => ($this->LogStderr == true ? 'true' : 'false')
            ];

            $return_results = [];
            foreach($results as $option => $value)
            {
                if($value !== null)
                    $return_results[$option] = $value;
            }

            return $return_results;
        }

        /**
         * Constructs object from an array representation
         *
         * @param array $data
         * @return LaunchCondition
         */
        public static function fromArray(array $data): LaunchCondition
        {
            $LaunchConditionObject = new LaunchCondition();

            if(isset($data['Exec']))
                $LaunchConditionObject->Exec = $data['Exec'];

            if(isset($data['Args']))
                $LaunchConditionObject->Arguments = Parser::parseArgumentsString($data['Args']);

            if(isset($data['Env']))
                $LaunchConditionObject->EnvironmentVariables = Parser::parseArgumentsString($data['Env']);

            if(isset($data['Cwd']))
                $LaunchConditionObject->CurrentWorkingDirectory = $data['Cwd'];

            if(isset($data['Restart']))
            {
                switch(strtolower($data['Restart']))
                {
                    case RestartCondition::OnFailure:
                    case RestartCondition::No:
                    case RestartCondition::Always:
                    case RestartCondition::UnlessStopped:
                    case RestartCondition::OnSuccess:
                        $LaunchConditionObject->Restart = strtolower($data['Restart']);
                        break;

                    default:
                        $LaunchConditionObject->Restart = RestartCondition::No;
                }
            }

            if(isset($data['MaxFailureRestarts']))
                $LaunchConditionObject->MaxFailureRestarts = (int)$data['MaxFailureRestarts'];

            if(isset($data['MaxAutomaticRestarts']))
                $LaunchConditionObject->MaxAutomaticRestarts = (int)$data['MaxAutomaticRestarts'];

            if(isset($data['TimeoutStartSec']))
                $LaunchConditionObject->TimeoutStartSeconds = (int)$data['TimeoutStartSec'];

            if(isset($data['TimeoutStopSec']))
                $LaunchConditionObject->TimeoutStopSeconds = (int)$data['TimeoutStopSec'];

            if(isset($data['RuntimeMaxSec']))
                $LaunchConditionObject->RuntimeMaxSeconds = (int)$data['RuntimeMaxSec'];

            if(isset($data['SuccessExitStatus']))
            {
                foreach(explode(' ', $data['SuccessExitStatus']) as $item)
                {
                    if(in_array((int)$item, $LaunchConditionObject->SuccessExitStatus) == false)
                    {
                        $LaunchConditionObject->SuccessExitStatus[] = (int)$item;
                    }
                }
            }

            if(isset($data['ErrorExitStatus']))
            {
                foreach(explode(' ', $data['ErrorExitStatus']) as $item)
                {
                    if(in_array((int)$item, $LaunchConditionObject->ErrorExitStatus) == false)
                    {
                        $LaunchConditionObject->ErrorExitStatus[] = (int)$item;
                    }
                }
            }

            if(isset($data['RestartPreventExitStatus']))
            {
                $LaunchConditionObject->RestartPreventExitStatus = [];

                foreach(explode(' ', $data['RestartPreventExitStatus']) as $item)
                {
                    if(in_array((int)$item, $LaunchConditionObject->RestartPreventExitStatus) == false)
                    {
                        $LaunchConditionObject->RestartPreventExitStatus[] = (int)$item;
                    }
                }
            }

            if(isset($data['RestartForceExitStatus']))
            {
                $LaunchConditionObject->RestartForceExitStatus = [];

                foreach(explode(' ', $data['RestartForceExitStatus']) as $item)
                {
                    if(in_array((int)$item, $LaunchConditionObject->RestartForceExitStatus) == false)
                    {
                        $LaunchConditionObject->RestartForceExitStatus[] = (int)$item;
                    }
                }
            }

            if(isset($data['LogStdout']))
                $LaunchConditionObject->LogStdout = (bool)$data['LogStdout'];

            if(isset($data['LogStderr']))
                $LaunchConditionObject->LogStderr = (bool)$data['LogStderr'];

            return $LaunchConditionObject;
        }
    }