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
        $realEstateObject = "\RealEstate\{$realEstateObjectClassName}";
        $object = new $realEstateObject;

        # Default mappings
        foreach ($this->stringMappings as $string) {
            if (isset($data->$string)) {
                $object->$string = (string)$data->$string;
            }
        }

        foreach ($this->integerMappings as $int) {
            if (isset($data->$int)) {
                $object->$int = (int)$data->$int;
            }
        }

        foreach ($this->dateTimeMappings as $date) {
            if (isset($data->$date)) {
                $object->$date = (int)$data->$date;
            }
        }

        $this->mapCustomFields($object, $data);

        return $object;
    }

    /**
     * Map fields that are not default types.
     * @param $object
     * @param \SimpleXMLElement $data
     */
    public function mapCustomFields($object, \SimpleXMLElement $data)
    {
        // Overwrite in child class when custom fields need to be mapped
    }
}
