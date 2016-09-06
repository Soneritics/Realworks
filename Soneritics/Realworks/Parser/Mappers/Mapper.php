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
namespace Realworks\Parser\Mappers;

/**
 * Class Mapper
 *
 * @package Realworks\Parser\Mappers
 * @author Jordi Jolink <mail@jordijolink.nl>
 */
abstract class Mapper
{
    /**
     * Fields that can be mapped to integer values.
     * @var array
     */
    protected $integerMappings = [];

    /**
     * Fields that can be mapped to string values.
     * @var array
     */
    protected $stringMappings = [];

    /**
     * Fields that can be mapped to \DateTime values.
     * @var array
     */
    protected $dateTimeMappings = [];

    /**
     * Fields that can be mapped to boolean values.
     * @var array
     */
    protected $booleanMappings = [];

    /**
     * Fields that can be mapped to RealEstate objects.
     * @var array
     */
    protected $objectMappings = [];

    /**
     * Arrays that contain strings.
     * @var array
     */
    protected $stringArrayMappings = [];

    /**
     * @var MapperRegister
     */
    private $mapperRegister;

    /**
     * Mapper constructor.
     * @param MapperRegister $mapperRegister
     */
    public function __construct(MapperRegister $mapperRegister)
    {
        $this->mapperRegister = $mapperRegister;
    }

    /**
     * @return MapperRegister
     */
    protected function getMapperRegister()
    {
        return $this->mapperRegister;
    }

    /**
     * Map to the specified Real Estate object.
     * @param \SimpleXMLElement $data
     * @return mixed
     */
    public function map(\SimpleXMLElement $data)
    {
        $realEstateObjectClassName = (new \ReflectionClass($this))->getShortName();
        $realEstateObject = '\RealEstate\\' . $realEstateObjectClassName;
        $object = new $realEstateObject;

        $this->mapStrings($object, $data);
        $this->mapIntegers($object, $data);
        $this->mapBooleans($object, $data);
        $this->mapDateTime($object, $data);
        $this->mapObjects($object, $data);
        $this->mapStringArray($object, $data);
        $this->mapCustomFields($object, $data);

        return $object;
    }

    /**
     * Map fields that are not default types.
     * @param $object
     * @param \SimpleXMLElement $data
     */
    protected function mapCustomFields($object, \SimpleXMLElement $data)
    {
        // Overwrite in child class when custom fields need to be mapped
    }

    /**
     * Map strings
     * @param $object
     * @param \SimpleXMLElement $data
     */
    protected function mapStrings($object, \SimpleXMLElement $data)
    {
        foreach ($this->stringMappings as $string) {
            if (isset($data->$string)) {
                $object->$string = (string)$data->$string;
            }
        }
    }

    /**
     * Map integers
     * @param $object
     * @param \SimpleXMLElement $data
     */
    protected function mapIntegers($object, \SimpleXMLElement $data)
    {
        foreach ($this->integerMappings as $int) {
            if (isset($data->$int)) {
                $object->$int = (int)$data->$int;
            }
        }
    }

    /**
     * Map DateTime fields
     * @param $object
     * @param \SimpleXMLElement $data
     */
    protected function mapDateTime($object, \SimpleXMLElement $data)
    {
        foreach ($this->dateTimeMappings as $date) {
            if (isset($data->$date)) {
                $object->$date = (int)$data->$date;
            }
        }
    }

    /**
     * Map booleans
     * @param $object
     * @param \SimpleXMLElement $data
     */
    protected function mapBooleans($object, \SimpleXMLElement $data)
    {
        $boolValues = ['ja', '1', 'yes', 'true'];
        foreach ($this->stringMappings as $string) {
            if (isset($data->$string)) {
                $object->$string = in_array(strtolower($data->$string), $boolValues);
            }
        }
    }

    /**
     * Map objects
     * @param $object
     * @param \SimpleXMLElement $data
     */
    protected function mapObjects($object, \SimpleXMLElement $data)
    {
        foreach ($this->objectMappings as $objectMapping) {
            if (isset($data->$objectMapping)) {
                $mapper = $this->getMapperRegister()->{"get{$objectMapping}Mapper"}();
                $object->$objectMapping = $mapper->map($data->$objectMapping);
            }
        }
    }

    /**
     * Map a string array
     * @param $object
     * @param \SimpleXMLElement $data
     */
    protected function mapStringArray($object, \SimpleXMLElement $data)
    {
        foreach ($this->stringArrayMappings as $stringArray) {
            $object->$stringArray = [];
            $this->getStringsFromNestedArray($data->$stringArray, $object->$stringArray);
        }
    }

    /**
     * Find all strings in a nested array/XMLObject.
     * @param \SimpleXMLElement $data
     * @param array $strings Reference to an array to be filled
     */
    private function getStringsFromNestedArray(\SimpleXMLElement $data, array &$strings)
    {
        foreach ((array)$data as $item) {
            if (!is_string($item)) {
                $this->getStringsFromNestedArray($item, $strings);
            } else {
                $strings[] = (string)$item;
            }
        }
    }
}
