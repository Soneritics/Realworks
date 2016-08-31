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
namespace RealEstate;

/**
 * Class WonenDetails
 *
 * @package RealEstate
 * @author Jordi Jolink <mail@jordijolink.nl>
 */
class WonenDetails
{
    /**
     * @var Bestemming
     */
    public $Bestemming;

    /**
     * @var MatenEnLigging
     */
    public $MatenEnLigging;

    /**
     * @var Bouwjaar
     */
    public $Bouwjaar;

    /**
     * @var array of Onderhoud
     */
    public $Onderhoud = [];

    /**
     * @var SchuurBerging
     */
    public $SchuurBerging;

    /**
     * @var Diversen
     */
    public $Diversen;

    /**
     * @var array
     */
    public $Keurmerken = [];

    /**
     * @var Energielabel
     */
    public $Energielabel;

    /**
     * @var int|null
     */
    public $EPC;

    /**
     * @var Installatie
     */
    public $Installatie;

    /**
     * @var array of strings
     */
    public $VoorzieningenWonen = [];

    /**
     * @var string|null (max 2.000 characters)
     */
    public $Toelichting;

    /**
     * @var Tuin
     */
    public $Tuin;

    /**
     * @var Hoofdtuin
     */
    public $Hoofdtuin;

    /**
     * @var Garage
     */
    public $Garage;

    /**
     * @var Parkeren
     */
    public $Parkeren;
}
