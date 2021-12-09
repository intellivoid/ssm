<?php

    namespace ssm\Objects;

    use ssm\Classes\IniParser;
    use ssm\Exceptions\FileException;
    use ssm\Exceptions\InvalidDataException;

    class IniFile
    {
        /**
         * @var string
         */
        protected $file;

        /**
         * @var IniParser;
         */
        protected $parser;

        /**
         * @var IniSection[]
         */
        protected $sections = [];

        /**
         * IniFile constructor.
         *
         * @param string|null $file
         * @throws InvalidDataException
         */
        public function __construct(?string $file=null)
        {
            $localRawContents = '';
            $this->parser = IniParser::i();
            if(is_null($file) == false)
            {
                $rawContents = file_get_contents($file);
                $this->file = $file;
                $this->sections = $this->parser->parseIniString($rawContents, $localRawContents);
            }
        }

        /**
         * @param string $file
         * @return IniFile
         */
        public static function load(string $file): IniFile
        {
            return new self($file);
        }

        /**
         * @param array $data
         * @return IniFile
         * @throws InvalidDataException
         */
        public static function fromArray(array $data): IniFile
        {
            $iniSections = IniParser::i()->parseArray($data);

            return self::fromIniSections($iniSections);
        }

        /**
         * @param IniSection[] $iniSections
         * @return IniFile
         * @throws InvalidDataException
         */
        public static function fromIniSections(array $iniSections): IniFile
        {
            $iniFile = new IniFile();
            foreach ($iniSections as $iniSection)
            {
                if (false === $iniSection instanceof IniSection)
                {
                    throw new InvalidDataException('The service file contains invalid data');
                }
                $iniFile->addSection($iniSection);
            }

            return $iniFile;
        }

        /**
         * @param string|null $outputFile
         * @throws FileException
         */
        public function save(string $outputFile = null)
        {
            if (is_null($outputFile))
            {
                if (is_null($this->file))
                {
                    throw new FileException('No output file set! Please, set an output file.');
                }
                $outputFile = $this->file;
            }

            if (is_file($outputFile) && !is_writable($outputFile))
            {
                throw new FileException(sprintf('Output file "%s" is not writable!', $outputFile));
            }

            $result = file_put_contents($outputFile, $this->toString());
            if (false === $result)
            {
                throw new FileException(sprintf('Error writing file "%s"!', $outputFile));
            }
        }

        /**
         * @param IniSection $section
         * @return $this
         * @throws InvalidDataException
         * @noinspection PhpUnused
         */
        public function addSection(IniSection $section): IniFile
        {
            if ($this->hasSection($section->getName()))
            {
                throw new InvalidDataException(sprintf('Section "%s" already exists!',
                    $section->getName()));
            }

            if ($section->hasParent())
            {
                if (!isset($this->sections[$section->getParent()->getName()]))
                {
                    throw new InvalidDataException(sprintf('Parent section "%s" does not exists!',
                        $section->getParent()->getName()));
                }
            }

            $this->sections[$section->getName()] = $section;

            return $this;
        }

        /**
         * @param string $sectionName
         * @return bool
         */
        public function hasSection(string $sectionName): bool
        {
            return isset($this->sections[$sectionName]);
        }

        /**
         * @param string $sectionName
         *
         * @return IniSection
         * @throws InvalidDataException
         * @noinspection PhpMissingParamTypeInspection
         */
        public function getSection($sectionName): IniSection
        {
            if (!$this->hasSection($sectionName))
            {
                throw new InvalidDataException(sprintf('Section "%s" does not exists!', $sectionName));
            }

            return $this->sections[$sectionName];
        }

        /**
         * Get normalized item value
         *
         * @param string $sectionName
         * @param string $itemName
         * @param mixed $defaultValue
         * @return array|bool|float|int|string|null
         * @throws InvalidDataException
         */
        public function get(string $sectionName, string $itemName, $defaultValue = null)
        {
            $section = $this->getSection($sectionName);

            return $section->get($itemName, $defaultValue);
        }

        /**
         * @param string $sectionName
         * @param string $itemName
         * @param string $itemValue

         * @return $this
         * @throws InvalidDataException
         */
        public function set(string $sectionName, string $itemName, string $itemValue): IniFile
        {
            $section = $this->getSection($sectionName);
            $section->set($itemName, $itemValue);
            return $this;
        }

        /**
         * @return array
         */
        public function toArray(): array
        {
            $data = [];
            foreach ($this->sections as $sectionName => $section)
            {
                $data[$sectionName] = $section->toArray();
            }

            return $data;
        }

        /**
         * @return string
         */
        public function toString(): string
        {
            $contents = [];
            foreach ($this->sections as $section)
            {
                $contents[] = $section->toString();
            }

            return implode(PHP_EOL, $contents);
        }
    }