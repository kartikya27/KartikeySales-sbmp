<?php

namespace Arky\Sales\Generators;

use Arky\Sales\Interfaces\Sequencer as SequencerContract;

class Sequencer implements SequencerContract
{
    /**
     * Length.
     *
     * @var string
     */
    public $length;
    /**
     * Length.
     *
     * @var string
     */
    public $date;

    /**
     * Prefix.
     *
     * @var string define country code
     */
    public $prefix;

    /**
     * Suffix.
     *
     * @var string
     */
    public $suffix;

    /**
     * Generator class.
     *
     * @var string
     */
    public $generatorClass;

    /**
     * Last id.
     *
     * @var int
     */
    public $lastId = 0;

    /**
     * Set length from the core config.
     *
     * @param  string  $configKey
     * @return void
     */
    public function setLength($configKey)
    {
        $this->length = 8 ; //todo core()->getConfigData($configKey);
    }

    /**
     * Set prefix from the core config.
     *
     * @param  string  $configKey
     * @return void
     */
    public function setPrefix($configKey)
    {
        $this->prefix = 'DE'; // todo core()->getConfigData($configKey);
    }

    /**
     * Set suffix from the core config.
     *
     * @param  string  $configKey
     * @return void
     */
    public function setSuffix($configKey)
    {
        $this->suffix = '24'; // core()->getConfigData($configKey);
    }

    /**
     * Set generator class from the core config.
     *
     * @param  string  $configKey
     * @return void
     */
    public function setGeneratorClass($configKey)
    {
        $this->generatorClass = 'IN'; // core()->getConfigData($configKey);
    }

    /**
     * Resolve generator class.
     *
     * @return string
     */

    public function resolveGeneratorClass()
    {
        if (
            $this->generatorClass !== ''
            && class_exists($this->generatorClass)
            && in_array(SequencerContract::class, class_implements($this->generatorClass), true)
        ) {
            return app($this->generatorClass)->generate();
        }
        return $this->generate();
    }

    /**
     * Create and return the next sequence number for e.g. an order.
     */
    public function generate(): string
    {
        return $this->prefix.'-'.($this->date).'-'.rand(111,999).$this->suffix.sprintf(
            "%0{$this->length}d",
            (self::revrsalNumberArray())
        );
    }

    public function revrsalNumberArray()
    {
        $originalArray = [1, 2, 3, 4, 5, 6, 7, 8, 9, 0];
        $reversedArray = array_reverse($originalArray);

        $orderNumber = (string)($this->lastId + 1);

        $transformedNumber = '';
        for ($i = 0; $i < strlen($orderNumber); $i++) {
            $digit = (int)$orderNumber[$i];
            $transformedNumber .= $reversedArray[$digit];
        }
        return $transformedNumber;

    }

}
