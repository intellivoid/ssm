<?php

    namespace ssm\Abstracts;

    abstract class RestartCondition
    {
        /**
         * the process will not automatically restart for any reason whatsoever
         */
        const No = 'no';

        /**
         * will only restart if the process's lunch condition indicates success.
         */
        const OnSuccess = 'on-success';

        /**
         * will only restart if the process's lunch condition indicates failure.
         */
        const OnFailure = 'on-failure';

        /**
         * will always restart the process unless stopped manually this is only applicable to the start lunch condition
         */
        const UnlessStopped = 'unless-stopped';

        /**
         * will always restart the process, this is only applicable to the start lunch condition.
         */
        const Always = 'always';
    }