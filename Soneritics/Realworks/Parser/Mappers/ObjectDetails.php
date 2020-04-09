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
 * Class ObjectDetails
 *
 * @package Realworks\Parser\Mappers
 * @author Jordi Jolink <mail@jordijolink.nl>
 */
class ObjectDetails extends Mapper
{
    /**
     * Fields that can be mapped to integer values.
     * @var array
     */
    protected $integerMappings = ['ServicekostenPerMaand'];

    /**
     * Fields that can be mapped to string values.
     * @var array
     */
    protected $stringMappings = [
        'Aanvaarding',
        'ToelichtingAanvaarding',
        'ObjectAanmelding',
        'Bouwvorm',
        'Aanbiedingstekst'
    ];

    /**
     * Fields that can be mapped to \DateTime values.
     * @var array
     */
    protected $dateTimeMappings = ['DatumAanvaarding', 'DatumInvoer', 'DatumWijziging', 'DatumVeiling'];

    /**
     * Fields that can be mapped to RealEstate objects.
     * @var array
     */
    protected $objectMappings = ['Koop', 'Huur', 'Koopmengvorm', 'StatusBeschikbaarheid'];

    /**
     * Arrays that contain strings.
     * @var array
     */
    protected $stringArrayMappings = ['Internetplaatsingen'];

    /**
     * Map fields that are not default types.
     * @param $object
     * @param \SimpleXMLElement $data
     */
    protected function mapCustomFields($object, \SimpleXMLElement $data)
    {
        if (!empty($data->Adres->Nederlands)) {
            $object->Adres = $this
                ->getMapperRegister()
                ->getNederlandsAdresMapper()
                ->map($data->Adres->Nederlands);
        }

        if (!empty($data->Adres->Internationaal)) {
            $object->Adres = $this
                ->getMapperRegister()
                ->getInternationaalAdresMapper()
                ->map($data->Adres->Internationaal);
        }

        if (!empty($data->Adres->Straatnaam)) {
            $object->Adres = $this
                ->getMapperRegister()
                ->getBOGAdresMapper()
                ->map($data->Adres);
        }

        if (!empty($data->CombinatieObject->BOGObject)) {
            $object->CombinatieObject = $this
                ->getMapperRegister()
                ->getbogObjectMapper()
                ->map($data->CombinatieObject->BOGObject);
        }

        if (!empty($data->CombinatieObject->AgrarischBedrijf)) {
            $object->CombinatieObject = $this
                ->getMapperRegister()
                ->getAgrarischBedrijfMapper()
                ->map($data->CombinatieObject->AgrarischBedrijf);
        }
    }
}
