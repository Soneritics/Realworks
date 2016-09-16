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
 * Class Woonlagen
 *
 * @package Realworks\Parser\Mappers
 * @author Jordi Jolink <mail@jordijolink.nl>
 */
class Woonlagen extends Mapper
{
    /**
     * Map fields that are not default types.
     * @param $object
     * @param \SimpleXMLElement $data
     */
    protected function mapCustomFields($object, \SimpleXMLElement $data)
    {
        if (!empty($data->Kelder)) {
            $object->Kelder = $this->getMapperRegister()->getWoonlaagMapper()->map($data->Kelder);
        }

        if (!empty($data->BeganeGrondOfFlat)) {
            $object->BeganeGrondOfFlat = $this->getMapperRegister()->getWoonlaagMapper()->map($data->BeganeGrondOfFlat);
        }

        if (!empty($data->Verdieping)) {
            $object->Verdieping = $this->getMapperRegister()->getWoonlaagMapper()->map($data->Verdieping);
        }

        if (!empty($data->Zolder)) {
            $object->Zolder = $this->getMapperRegister()->getZolderVlieringMapper()->map($data->Zolder);
        }

        if (!empty($data->Vliering)) {
            $object->Vliering = $this->getMapperRegister()->getZolderVlieringMapper()->map($data->Vliering);
        }
    }
}
