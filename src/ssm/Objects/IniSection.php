<?php

    namespace ssm\Objects;

    use ssm\Classes\IniParser;
    use ssm\Exceptions\InvalidDataException;

    class IniSection
    {
        /**
         * @var string
         */
        protected $name;

        /**
         * @var IniSection|null
         */
        protected $parent;

        /**
         * @var array
         */
        protected $contents = [];

        /**
         * Section constructor.
         *
         * @param string $name
         * @param IniSection|null $parent
         * @noinspection PhpMissingParamTypeInspection
         */
        public function __construct($name, IniSection $parent = null)
        {
            $this->name = $name;
            $this->parent = $parent;
        }

        /**
         * @param IniSection $parent
         *
         * @return $this
         */
        public function setParent(IniSection $parent): IniSection
        {
            $this->parent = $parent;

            return $this;
        }


        /**
         * @param array $data
         * @return $this
         * @throws InvalidDataException
         * @noinspection PhpMissingParamTypeInspection
         */
        public function setContents($data): IniSection
        {
            if (!is_array($data))
            {
                throw new InvalidDataException('Invalid section contents! ' .
                    'Section contents must be an array.');
            }
            $this->contents = IniParser::i()->itemValuetoStringRepresentation($data);

            return $this;
        }

        /**
         * @return array
         */
        protected function getContents(): array
        {
            return $this->contents;
        }

        /**
         * @return bool
         */
        public function hasParent(): bool
        {
            return ($this->parent instanceof IniSection);
        }

        /**
         * @return IniSection|null
         */
        public function getParent(): ?IniSection
        {
            return $this->parent;
        }

        /**
         * @return string
         */
        public function getName(): string
        {
            return $this->name;
        }


        /**
         * @return IniSection[]
         */
        protected function getParents(): array
        {
            $parents = [];
            $currentSection = $this;
            while ($currentSection->hasParent())
            {
                $parent = $currentSection->getParent();
                $parents[] = $parent;
                $currentSection = $parent;
            }
            rsort($parents);

            return $parents;
        }

        /**
         * @return array
         */
        protected function composeContents(): array
        {
            $contents = [];
            $parents = $this->getParents();
            foreach ($parents as $section)
            {
                $contents = array_merge($contents, $section->getContents());
            }
            return array_merge($contents, $this->getContents());
        }

        /**
         * Get normalized item value
         *
         * @param string $itemName
         * @param mixed $defaultValue
         * @return array|bool|float|int|string|null
         */
        public function get(string $itemName, $defaultValue = null)
        {
            $contents = $this->composeContents();
            /** @noinspection PhpIssetCanBeReplacedWithCoalesceInspection */
            $value = isset($contents[$itemName]) ? $contents[$itemName] : $defaultValue;

            return IniParser::i()->castItemValueToProperType($value);
        }

        /**
         * @param string $itemName
         * @param string|array|bool|null $itemValue
         *
         * @return $this
         * @throws InvalidDataException
         */
        public function set(string $itemName, $itemValue): IniSection
        {
            list($validationResult, $message) = IniParser::i()->validateItemName($itemName);
            if (false === $validationResult)
            {
                throw new InvalidDataException($message);
            }
            $this->contents[$itemName] = IniParser::i()->itemValuetoStringRepresentation($itemValue);

            return $this;
        }

        /**
         * @param string $itemName
         * @return bool
         * @noinspection PhpMissingParamTypeInspection
         * @noinspection PhpRedundantOptionalArgumentInspection
         * @noinspection PhpUnused
         */
        public function hasItem($itemName): bool
        {
            $value = $this->get($itemName, null);

            return !is_null($value);
        }

        /**
         * @return array
         */
        public function toArray(): array
        {
            return $this->composeContents();
        }

        /**
         * @return string
         */
        public function toString(): string
        {
            $lines = $this->renderName();
            foreach ($this->contents as $itemName => $itemValue)
            {
                $lines = array_merge($lines, $this->renderItem($itemName, $itemValue));
            }

            return implode(PHP_EOL, $lines) . PHP_EOL;
        }

        /**
         * @return string[]
         */
        protected function renderName(): array
        {

            if ($this->hasParent())
            {
                $line = [sprintf('[%s : %s]', $this->getName(), $this->getParent()->getName())];
            }
            else
            {
                $line = [sprintf('[%s]', $this->getName())];
            }

            return $line;
        }

        /**
         * @param string $name
         * @param string|array $value
         * @return array
         * @noinspection PhpMissingParamTypeInspection
         */
        protected function renderItem($name, $value): array
        {
            if (is_array($value))
            {
                $lines = $this->renderArrayItem($name, $value);
            }
            else
            {
                $lines = $this->renderStringItem($name, $value);
            }

            return $lines;
        }

        /**
         * @param string $name
         * @param string $value
         * @return string[]
         * @noinspection PhpMissingParamTypeInspection
         */
        protected function renderStringItem($name, $value): array
        {
            return [sprintf('%s = "%s"', $name, $value)];
        }

        /**
         * @param string $name
         * @param array $values
         * @return array
         * @noinspection PhpMissingParamTypeInspection
         */
        protected function renderArrayItem($name, array $values): array
        {
            $lines = [];
            $isAssocArray = (array_values($values) !== $values);
            foreach ($values as $key => $value)
            {
                $stringKey = $isAssocArray ? $key : '';
                $lines[] = sprintf('%s[%s] = "%s"', $name, $stringKey, $value);
            }

            return $lines;
        }

    }