<?php
/*
 * The MIT License
 *
 * Copyright 2016 Jordi Jolink.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace Realworks\Parser;

use Realworks\Exceptions\MissingParser;
use Realworks\Parser\Mappers\MapperRegister;
use Realworks\RealEstateType\IRealEstateType;

/**
 * Class ParserFactory
 *
 * @package Realworks\Parser
 * @author Jordi Jolink <mail@jordijolink.nl>
 */
class ParserFactory
{
    /**
     * @var array
     */
    private $customParsers = [];

    /**
     * @var MApperRegister
     */
    private $mapperRegister;

    /**
     * Add a custom parser for a specific IRealEstateType.
     * @param IRealEstateType $type
     * @param Parser $parser
     * @return $this
     */
    public function addCustomParser(IRealEstateType $type, Parser $parser)
    {
        $this->customParsers[(string)$type] = $parser;
        return $this;
    }

    /**
     * Get the MApperRegister
     * @return MapperRegister
     */
    public function getMapperRegister()
    {
        if (empty($this->mapperRegister)) {
            $this->mapperRegister = new MapperRegister;
        }

        return $this->mapperRegister;
    }

    /**
     * Build a Parser class based on an IRealEstateType.
     * @param IRealEstateType $type
     * @return Parser
     * @throws MissingParser
     */
    public function build(IRealEstateType $type)
    {
        $mapperRegister = $this->getMapperRegister();

        if (!empty($this->customParsers[(string)$type])) {
            return $this->customParsers[(string)$type]($mapperRegister);
        }

        $class = '\Realworks\Parser\RealEstateParser\\' . $type;
        if (!class_exists($class)) {
            throw new MissingParser("Parser not found for {$type}");
        }

        return new $class($mapperRegister);
    }
}
