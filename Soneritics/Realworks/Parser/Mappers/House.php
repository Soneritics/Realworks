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
 * Class House
 *
 * @package Realworks\Parser\Mappers
 * @author Jordi Jolink <mail@jordijolink.nl>
 */
class House extends Mapper
{
    /**
     * Fields that can be mapped to integer values.
     * @var array
     */
    protected $integerMappings = ['ObjectTiaraID', 'ObjectSystemID'];

    /**
     * Fields that can be mapped to string values.
     * @var array
     */
    protected $stringMappings = ['NVMVestigingNR', 'ObjectAfdeling', 'ObjectCompany', 'ObjectCode'];

    /**
     * Map fields that are not default types.
     * @param $object
     * @param \SimpleXMLElement $data
     */
    public function mapCustomFields($object, \SimpleXMLElement $data)
    {
        $this->mapHouse($object, $data);
    }

    /**
     * Map the XML data to the House object
     * @param House $house
     * @param \SimpleXMLElement $data
     */
    protected function mapHouse(House $house, \SimpleXMLElement $data)
    {
        if (isset($data->Web)) {
            $house->Web = $this->getMapperRegister()->getWebMapper()->map($data);
        }

        if (isset($data->ObjectDetails)) {
            $house->Web = $this->getMapperRegister()->getObjectDetailsMapper()->map($data);
        }

        if (isset($data->Wonen)) {
            $house->Web = $this->getMapperRegister()->getWonenMapper()->map($data);
        }

        if (isset($data->Bouwgrond)) {
            $house->Web = $this->getMapperRegister()->getBouwgrondMapper()->map($data);
        }

        if (isset($data->OverigOG)) {
            $house->Web = $this->getMapperRegister()->getOverigOGMapper()->map($data);
        }

        if (!empty($data->MediaLijst)) {
            $this->processMediaList($house, $data->MediaLijst);
        }
    }

    /**
     * Process the media list
     * @param \RealEstate\House $house
     * @param \SimpleXMLElement $mediaList
     */
    protected function processMediaList(\RealEstate\House $house, \SimpleXMLElement $mediaList)
    {
        if (!empty($mediaList->Media)) {
            foreach ($mediaList->Media as $media) {
                $house->MediaLijst[] = $this->getMapperRegister()->getMediaMapper()->map($media);
            }
        }
    }
}
