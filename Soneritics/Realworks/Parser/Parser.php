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

use Realworks\File\XMLFile;
use Realworks\Parser\Mappers\MapperRegister;

/**
 * Class Parser
 *
 * @package Realworks\Parser
 * @author Jordi Jolink <mail@jordijolink.nl>
 */
abstract class Parser
{
    /**
     * @var XMLFile
     */
    private $xmlFile;

    /**
     * @var MapperRegister
     */
    protected $mapperRegister;

    /**
     * Parser constructor.
     */
    public function __construct(MapperRegister $mapperRegister)
    {
        $this->mapperRegister = $mapperRegister;
    }

    /**
     * Parse the XML file.
     * @param XMLFile $xmlFile
     * @return array
     */
    final public function parse(XMLFile $xmlFile)
    {
        $this->xmlFile = $xmlFile;
        return $this->parseXML();
    }

    /**
     * Parse the XML file by invoking an XML reader.
     * Each XML object must be processed per line by the ParseObject method.
     * @return array
     */
    protected function parseXML()
    {
        $xml = simplexml_load_file($this->xmlFile->getFilename());
        return $this->parseData($xml);
    }

    /**
     * Parse the XML into RealEstate entities.
     * @param mixed $object
     * @return mixed
     */
    abstract protected function parseData($object);
}
