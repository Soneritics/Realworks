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
 * Class MapperRegister
 * Register mappers for a custom implementation.
 *
 * This class is auto generated.
 *
 * @package Realworks\Parser\Mappers
 * @author Jordi Jolink <mail@jordijolink.nl>
 */
final class MapperRegister
{
    /**
     * @var Mapper
     */
    private $afmetingenMapper;

    /**
     * @var Mapper
     */
    private $agrarischBedrijfMapper;

    /**
     * @var Mapper
     */
    private $appartementMapper;

    /**
     * @var Mapper
     */
    private $bogObjectMapper;

    /**
     * @var Mapper
     */
    private $badkamerTypeMapper;

    /**
     * @var Mapper
     */
    private $bestemmingMapper;

    /**
     * @var Mapper
     */
    private $bouwgrondMapper;

    /**
     * @var Mapper
     */
    private $bouwjaarMapper;

    /**
     * @var Mapper
     */
    private $cvKetelMapper;

    /**
     * @var Mapper
     */
    private $dakMapper;

    /**
     * @var Mapper
     */
    private $diversenMapper;

    /**
     * @var Mapper
     */
    private $energielabelMapper;

    /**
     * @var Mapper
     */
    private $garageMapper;

    /**
     * @var Mapper
     */
    private $hoofdtuinMapper;

    /**
     * @var Mapper
     */
    private $houseMapper;

    /**
     * @var Mapper
     */
    private $BOGMapper;

    /**
     * @var Mapper
     */
    private $huurMapper;

    /**
     * @var Mapper
     */
    private $installatieMapper;

    /**
     * @var Mapper
     */
    private $internationaalAdresMapper;

    /**
     * @var Mapper
     */
    private $jaarOmschrijvingMapper;

    /**
     * @var Mapper
     */
    private $keukenTypeMapper;

    /**
     * @var Mapper
     */
    private $koopMapper;

    /**
     * @var Mapper
     */
    private $koopmengvormMapper;

    /**
     * @var Mapper
     */
    private $mapperRegisterMapper;

    /**
     * @var Mapper
     */
    private $matenEnLiggingMapper;

    /**
     * @var Mapper
     */
    private $mediaMapper;

    /**
     * @var Mapper
     */
    private $nederlandsAdresMapper;

    /**
     * @var Mapper
     */
    private $objectDetailsMapper;

    /**
     * @var Mapper
     */
    private $onderhoudMapper;

    /**
     * @var Mapper
     */
    private $openHuisMapper;

    /**
     * @var Mapper
     */
    private $overigOGMapper;

    /**
     * @var Mapper
     */
    private $overigOGObjectMapper;

    /**
     * @var Mapper
     */
    private $parkerenMapper;

    /**
     * @var Mapper
     */
    private $projectMapper;

    /**
     * @var Mapper
     */
    private $schuurBergingMapper;

    /**
     * @var Mapper
     */
    private $statusBeschikbaarheidMapper;

    /**
     * @var Mapper
     */
    private $tuinMapper;

    /**
     * @var Mapper
     */
    private $verdiepingenMapper;

    /**
     * @var Mapper
     */
    private $verkochtOnderVoorbehoudMapper;

    /**
     * @var Mapper
     */
    private $wozMapper;

    /**
     * @var Mapper
     */
    private $webMapper;

    /**
     * @var Mapper
     */
    private $wonenMapper;

    /**
     * @var Mapper
     */
    private $wonenDetailsMapper;

    /**
     * @var Mapper
     */
    private $woonhuisMapper;

    /**
     * @var Mapper
     */
    private $woonkamerTypeMapper;

    /**
     * @var Mapper
     */
    private $woonlaagMapper;

    /**
     * @var Mapper
     */
    private $woonlagenMapper;

    /**
     * @var Mapper
     */
    private $zolderVlieringMapper;

    /**
     * @var Mapper
     */
    private $gebouwMapper;

    /**
     * @var Mapper
     */
    private $gebouwDetailsMapper;

    /**
     * @var Mapper
     */
    private $kantoorruimteMapper;

    /**
     * @var Mapper
     */
    private $prijsSpecificatieMapper;

    /**
     * @var ProjectDetails
     */
    private $projectDetailsMapper;

    /**
     * @var BouwType
     */
    private $bouwTypeMapper;

    /**
     * @var BouwTypeDetails
     */
    private $bouwTypeDetailsMapper;

    /**
     * @var FinancieleGegevens
     */
    private $financieleGegevensMapper;

    /**
     * @var Maten
     */
    private $matenMapper;

    /**
     * @var Presentatie
     */
    private $presentatieMapper;

    /**
     * @var ProjectMaten
     */
    private $projectMatenMapper;

    /**
     * Set up the mappers to use.
     */
    public function __construct()
    {
        $this->afmetingenMapper = new Afmetingen($this);
        $this->agrarischBedrijfMapper = new AgrarischBedrijf($this);
        $this->appartementMapper = new Appartement($this);
        $this->bogObjectMapper = new BOGObject($this);
        $this->badkamerTypeMapper = new BadkamerType($this);
        $this->bestemmingMapper = new Bestemming($this);
        $this->bouwgrondMapper = new Bouwgrond($this);
        $this->bouwjaarMapper = new Bouwjaar($this);
        $this->cvKetelMapper = new CVKetel($this);
        $this->dakMapper = new Dak($this);
        $this->diversenMapper = new Diversen($this);
        $this->energielabelMapper = new Energielabel($this);
        $this->garageMapper = new Garage($this);
        $this->hoofdtuinMapper = new Hoofdtuin($this);
        $this->houseMapper = new House($this);
        $this->BOGMapper = new BOG($this);
        $this->huurMapper = new Huur($this);
        $this->installatieMapper = new Installatie($this);
        $this->internationaalAdresMapper = new InternationaalAdres($this);
        $this->jaarOmschrijvingMapper = new JaarOmschrijving($this);
        $this->keukenTypeMapper = new KeukenType($this);
        $this->koopMapper = new Koop($this);
        $this->koopmengvormMapper = new Koopmengvorm($this);
        $this->matenEnLiggingMapper = new MatenEnLigging($this);
        $this->mediaMapper = new Media($this);
        $this->nederlandsAdresMapper = new NederlandsAdres($this);
        $this->objectDetailsMapper = new ObjectDetails($this);
        $this->onderhoudMapper = new Onderhoud($this);
        $this->openHuisMapper = new OpenHuis($this);
        $this->overigOGMapper = new OverigOG($this);
        $this->overigOGObjectMapper = new OverigOGObject($this);
        $this->parkerenMapper = new Parkeren($this);
        $this->projectMapper = new Project($this);
        $this->schuurBergingMapper = new SchuurBerging($this);
        $this->statusBeschikbaarheidMapper = new StatusBeschikbaarheid($this);
        $this->tuinMapper = new Tuin($this);
        $this->verdiepingenMapper = new Verdiepingen($this);
        $this->verkochtOnderVoorbehoudMapper = new VerkochtOnderVoorbehoud($this);
        $this->wozMapper = new WOZ($this);
        $this->webMapper = new Web($this);
        $this->wonenMapper = new Wonen($this);
        $this->wonenDetailsMapper = new WonenDetails($this);
        $this->woonhuisMapper = new Woonhuis($this);
        $this->woonkamerTypeMapper = new WoonkamerType($this);
        $this->woonlaagMapper = new Woonlaag($this);
        $this->woonlagenMapper = new Woonlagen($this);
        $this->zolderVlieringMapper = new ZolderVliering($this);
        $this->gebouwMapper = new Gebouw($this);
        $this->BOGAdresMapper = new BOGAdres($this);
        $this->gebouwDetailsMapper = new GebouwDetails($this);
        $this->kantoorruimteMapper = new Kantoorruimte($this);
        $this->prijsSpecificatieMapper = new PrijsSpecificatie($this);
        $this->projectDetailsMapper = new ProjectDetails($this);
        $this->bouwTypeMapper = new BouwType($this);
        $this->bouwTypeDetailsMapper = new BouwTypeDetails($this);
        $this->financieleGegevensMapper = new FinancieleGegevens($this);
        $this->matenMapper = new Maten($this);
        $this->presentatieMapper = new Presentatie($this);
        $this->projectMatenMapper = new ProjectMaten($this);
    }

    /**
     * @return Mapper
     */
    public function getAfmetingenMapper()
    {
        return $this->afmetingenMapper;
    }

    /**
     * @param Mapper $afmetingenMapper
     * @return MapperRegister
     */
    public function setAfmetingenMapper(Mapper $afmetingenMapper)
    {
        $this->afmetingenMapper = $afmetingenMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getAgrarischBedrijfMapper()
    {
        return $this->agrarischBedrijfMapper;
    }

    /**
     * @param Mapper $agrarischBedrijfMapper
     * @return MapperRegister
     */
    public function setAgrarischBedrijfMapper(Mapper $agrarischBedrijfMapper)
    {
        $this->agrarischBedrijfMapper = $agrarischBedrijfMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getAppartementMapper()
    {
        return $this->appartementMapper;
    }

    /**
     * @param Mapper $appartementMapper
     * @return MapperRegister
     */
    public function setAppartementMapper(Mapper $appartementMapper)
    {
        $this->appartementMapper = $appartementMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getbogObjectMapper()
    {
        return $this->bogObjectMapper;
    }

    /**
     * @param Mapper $bogObjectMapper
     * @return MapperRegister
     */
    public function setbogObjectMapper(Mapper $bogObjectMapper)
    {
        $this->bogObjectMapper = $bogObjectMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getBadkamerTypeMapper()
    {
        return $this->badkamerTypeMapper;
    }

    /**
     * @param Mapper $badkamerTypeMapper
     * @return MapperRegister
     */
    public function setBadkamerTypeMapper(Mapper $badkamerTypeMapper)
    {
        $this->badkamerTypeMapper = $badkamerTypeMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getBestemmingMapper()
    {
        return $this->bestemmingMapper;
    }

    /**
     * @param Mapper $bestemmingMapper
     * @return MapperRegister
     */
    public function setBestemmingMapper(Mapper $bestemmingMapper)
    {
        $this->bestemmingMapper = $bestemmingMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getBouwgrondMapper()
    {
        return $this->bouwgrondMapper;
    }

    /**
     * @param Mapper $bouwgrondMapper
     * @return MapperRegister
     */
    public function setBouwgrondMapper(Mapper $bouwgrondMapper)
    {
        $this->bouwgrondMapper = $bouwgrondMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getBouwjaarMapper()
    {
        return $this->bouwjaarMapper;
    }

    /**
     * @param Mapper $bouwjaarMapper
     * @return MapperRegister
     */
    public function setBouwjaarMapper(Mapper $bouwjaarMapper)
    {
        $this->bouwjaarMapper = $bouwjaarMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getCVKetelMapper()
    {
        return $this->cvKetelMapper;
    }

    /**
     * @param Mapper $cvKetelMapper
     * @return MapperRegister
     */
    public function setCVKetelMapper(Mapper $cvKetelMapper)
    {
        $this->cvKetelMapper = $cvKetelMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getDakMapper()
    {
        return $this->dakMapper;
    }

    /**
     * @param Mapper $dakMapper
     * @return MapperRegister
     */
    public function setDakMapper(Mapper $dakMapper)
    {
        $this->dakMapper = $dakMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getDiversenMapper()
    {
        return $this->diversenMapper;
    }

    /**
     * @param Mapper $diversenMapper
     * @return MapperRegister
     */
    public function setDiversenMapper(Mapper $diversenMapper)
    {
        $this->diversenMapper = $diversenMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getEnergielabelMapper()
    {
        return $this->energielabelMapper;
    }

    /**
     * @param Mapper $energielabelMapper
     * @return MapperRegister
     */
    public function setEnergielabelMapper(Mapper $energielabelMapper)
    {
        $this->energielabelMapper = $energielabelMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getGarageMapper()
    {
        return $this->garageMapper;
    }

    /**
     * @param Mapper $garageMapper
     * @return MapperRegister
     */
    public function setGarageMapper(Mapper $garageMapper)
    {
        $this->garageMapper = $garageMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getHoofdtuinMapper()
    {
        return $this->hoofdtuinMapper;
    }

    /**
     * @param Mapper $hoofdtuinMapper
     * @return MapperRegister
     */
    public function setHoofdtuinMapper(Mapper $hoofdtuinMapper)
    {
        $this->hoofdtuinMapper = $hoofdtuinMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getHouseMapper()
    {
        return $this->houseMapper;
    }

    /**
     * @param Mapper $houseMapper
     * @return MapperRegister
     */
    public function setHouseMapper(Mapper $houseMapper)
    {
        $this->houseMapper = $houseMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getBOGMapper()
    {
        return $this->BOGMapper;
    }

    /**
     * @param Mapper $BOGMapper
     * @return MapperRegister
     */
    public function setBOGMapper(Mapper $BOGMapper)
    {
        $this->houseMapper = $BOGMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getHuurMapper()
    {
        return $this->huurMapper;
    }

    /**
     * @param Mapper $huurMapper
     * @return MapperRegister
     */
    public function setHuurMapper(Mapper $huurMapper)
    {
        $this->huurMapper = $huurMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getInstallatieMapper()
    {
        return $this->installatieMapper;
    }

    /**
     * @param Mapper $installatieMapper
     * @return MapperRegister
     */
    public function setInstallatieMapper(Mapper $installatieMapper)
    {
        $this->installatieMapper = $installatieMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getInternationaalAdresMapper()
    {
        return $this->internationaalAdresMapper;
    }

    /**
     * @param Mapper $internationaalAdresMapper
     * @return MapperRegister
     */
    public function setInternationaalAdresMapper(Mapper $internationaalAdresMapper)
    {
        $this->internationaalAdresMapper = $internationaalAdresMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getJaarOmschrijvingMapper()
    {
        return $this->jaarOmschrijvingMapper;
    }

    /**
     * @param Mapper $jaarOmschrijvingMapper
     * @return MapperRegister
     */
    public function setJaarOmschrijvingMapper(Mapper $jaarOmschrijvingMapper)
    {
        $this->jaarOmschrijvingMapper = $jaarOmschrijvingMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getKeukenTypeMapper()
    {
        return $this->keukenTypeMapper;
    }

    /**
     * @param Mapper $keukenTypeMapper
     * @return MapperRegister
     */
    public function setKeukenTypeMapper(Mapper $keukenTypeMapper)
    {
        $this->keukenTypeMapper = $keukenTypeMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getKoopMapper()
    {
        return $this->koopMapper;
    }

    /**
     * @param Mapper $koopMapper
     * @return MapperRegister
     */
    public function setKoopMapper(Mapper $koopMapper)
    {
        $this->koopMapper = $koopMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getKoopmengvormMapper()
    {
        return $this->koopmengvormMapper;
    }

    /**
     * @param Mapper $koopmengvormMapper
     * @return MapperRegister
     */
    public function setKoopmengvormMapper(Mapper $koopmengvormMapper)
    {
        $this->koopmengvormMapper = $koopmengvormMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getMapperRegisterMapper()
    {
        return $this->mapperRegisterMapper;
    }

    /**
     * @param Mapper $mapperRegisterMapper
     * @return MapperRegister
     */
    public function setMapperRegisterMapper(Mapper $mapperRegisterMapper)
    {
        $this->mapperRegisterMapper = $mapperRegisterMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getMatenEnLiggingMapper()
    {
        return $this->matenEnLiggingMapper;
    }

    /**
     * @param Mapper $matenEnLiggingMapper
     * @return MapperRegister
     */
    public function setMatenEnLiggingMapper(Mapper $matenEnLiggingMapper)
    {
        $this->matenEnLiggingMapper = $matenEnLiggingMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getMediaMapper()
    {
        return $this->mediaMapper;
    }

    /**
     * @param Mapper $mediaMapper
     * @return MapperRegister
     */
    public function setMediaMapper(Mapper $mediaMapper)
    {
        $this->mediaMapper = $mediaMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getNederlandsAdresMapper()
    {
        return $this->nederlandsAdresMapper;
    }

    /**
     * @param Mapper $nederlandsAdresMapper
     * @return MapperRegister
     */
    public function setNederlandsAdresMapper(Mapper $nederlandsAdresMapper)
    {
        $this->nederlandsAdresMapper = $nederlandsAdresMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getObjectDetailsMapper()
    {
        return $this->objectDetailsMapper;
    }

    /**
     * @param Mapper $objectDetailsMapper
     * @return MapperRegister
     */
    public function setObjectDetailsMapper(Mapper $objectDetailsMapper)
    {
        $this->objectDetailsMapper = $objectDetailsMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getOnderhoudMapper()
    {
        return $this->onderhoudMapper;
    }

    /**
     * @param Mapper $onderhoudMapper
     * @return MapperRegister
     */
    public function setOnderhoudMapper(Mapper $onderhoudMapper)
    {
        $this->onderhoudMapper = $onderhoudMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getOpenHuisMapper()
    {
        return $this->openHuisMapper;
    }

    /**
     * @param Mapper $openHuisMapper
     * @return MapperRegister
     */
    public function setOpenHuisMapper(Mapper $openHuisMapper)
    {
        $this->openHuisMapper = $openHuisMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getOverigOGMapper()
    {
        return $this->overigOGMapper;
    }

    /**
     * @param Mapper $overigOGMapper
     * @return MapperRegister
     */
    public function setOverigOGMapper(Mapper $overigOGMapper)
    {
        $this->overigOGMapper = $overigOGMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getOverigOGObjectMapper()
    {
        return $this->overigOGObjectMapper;
    }

    /**
     * @param Mapper $overigOGObjectMapper
     * @return MapperRegister
     */
    public function setOverigOGObjectMapper(Mapper $overigOGObjectMapper)
    {
        $this->overigOGObjectMapper = $overigOGObjectMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getParkerenMapper()
    {
        return $this->parkerenMapper;
    }

    /**
     * @param Mapper $parkerenMapper
     * @return MapperRegister
     */
    public function setParkerenMapper(Mapper $parkerenMapper)
    {
        $this->parkerenMapper = $parkerenMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getProjectMapper()
    {
        return $this->projectMapper;
    }

    /**
     * @param Mapper $projectMapper
     * @return MapperRegister
     */
    public function setProjectMapper(Mapper $projectMapper)
    {
        $this->projectMapper = $projectMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getSchuurBergingMapper()
    {
        return $this->schuurBergingMapper;
    }

    /**
     * @param Mapper $schuurBergingMapper
     * @return MapperRegister
     */
    public function setSchuurBergingMapper(Mapper $schuurBergingMapper)
    {
        $this->schuurBergingMapper = $schuurBergingMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getStatusBeschikbaarheidMapper()
    {
        return $this->statusBeschikbaarheidMapper;
    }

    /**
     * @param Mapper $statusBeschikbaarheidMapper
     * @return MapperRegister
     */
    public function setStatusBeschikbaarheidMapper(Mapper $statusBeschikbaarheidMapper)
    {
        $this->statusBeschikbaarheidMapper = $statusBeschikbaarheidMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getTuinMapper()
    {
        return $this->tuinMapper;
    }

    /**
     * @param Mapper $tuinMapper
     * @return MapperRegister
     */
    public function setTuinMapper(Mapper $tuinMapper)
    {
        $this->tuinMapper = $tuinMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getVerdiepingenMapper()
    {
        return $this->verdiepingenMapper;
    }

    /**
     * @param Mapper $verdiepingenMapper
     * @return MapperRegister
     */
    public function setVerdiepingenMapper(Mapper $verdiepingenMapper)
    {
        $this->verdiepingenMapper = $verdiepingenMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getVerkochtOnderVoorbehoudMapper()
    {
        return $this->verkochtOnderVoorbehoudMapper;
    }

    /**
     * @param Mapper $verkochtOnderVoorbehoudMapper
     * @return MapperRegister
     */
    public function setVerkochtOnderVoorbehoudMapper(Mapper $verkochtOnderVoorbehoudMapper)
    {
        $this->verkochtOnderVoorbehoudMapper = $verkochtOnderVoorbehoudMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getWOZMapper()
    {
        return $this->wozMapper;
    }

    /**
     * @param Mapper $wozMapper
     * @return MapperRegister
     */
    public function setWOZMapper(Mapper $wozMapper)
    {
        $this->wozMapper = $wozMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getWebMapper()
    {
        return $this->webMapper;
    }

    /**
     * @param Mapper $webMapper
     * @return MapperRegister
     */
    public function setWebMapper(Mapper $webMapper)
    {
        $this->webMapper = $webMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getWonenMapper()
    {
        return $this->wonenMapper;
    }

    /**
     * @param Mapper $wonenMapper
     * @return MapperRegister
     */
    public function setWonenMapper(Mapper $wonenMapper)
    {
        $this->wonenMapper = $wonenMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getWonenDetailsMapper()
    {
        return $this->wonenDetailsMapper;
    }

    /**
     * @param Mapper $wonenDetailsMapper
     * @return MapperRegister
     */
    public function setWonenDetailsMapper(Mapper $wonenDetailsMapper)
    {
        $this->wonenDetailsMapper = $wonenDetailsMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getWoonhuisMapper()
    {
        return $this->woonhuisMapper;
    }

    /**
     * @param Mapper $woonhuisMapper
     * @return MapperRegister
     */
    public function setWoonhuisMapper(Mapper $woonhuisMapper)
    {
        $this->woonhuisMapper = $woonhuisMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getWoonkamerTypeMapper()
    {
        return $this->woonkamerTypeMapper;
    }

    /**
     * @param Mapper $woonkamerTypeMapper
     * @return MapperRegister
     */
    public function setWoonkamerTypeMapper(Mapper $woonkamerTypeMapper)
    {
        $this->woonkamerTypeMapper = $woonkamerTypeMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getWoonlaagMapper()
    {
        return $this->woonlaagMapper;
    }

    /**
     * @param Mapper $woonlaagMapper
     * @return MapperRegister
     */
    public function setWoonlaagMapper(Mapper $woonlaagMapper)
    {
        $this->woonlaagMapper = $woonlaagMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getWoonlagenMapper()
    {
        return $this->woonlagenMapper;
    }

    /**
     * @param Mapper $woonlagenMapper
     * @return MapperRegister
     */
    public function setWoonlagenMapper(Mapper $woonlagenMapper)
    {
        $this->woonlagenMapper = $woonlagenMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getZolderVlieringMapper()
    {
        return $this->zolderVlieringMapper;
    }

    /**
     * @param Mapper $zolderVlieringMapper
     * @return MapperRegister
     */
    public function setZolderVlieringMapper(Mapper $zolderVlieringMapper)
    {
        $this->zolderVlieringMapper = $zolderVlieringMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getGebouwMapper()
    {
        return $this->gebouwMapper;
    }

    /**
     * @param Mapper $gebouwMapper
     * @return MapperRegister
     */
    public function setGebouwMapper(Mapper $gebouwMapper)
    {
        $this->gebouwMapper = $gebouwMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getGebouwDetailsMapper()
    {
        return $this->gebouwDetailsMapper;
    }

    /**
     * @param Mapper $gebouwDetailsMapper
     * @return MapperRegister
     */
    public function setGebouwDetailsMapper(Mapper $gebouwDetailsMapper)
    {
        $this->gebouwDetailsMapper = $gebouwDetailsMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getKantoorruimteMapper()
    {
        return $this->kantoorruimteMapper;
    }

    /**
     * @param Mapper $kantoorruimteMapper
     * @return MapperRegister
     */
    public function setKantoorruimteMapper(Mapper $kantoorruimteMapper)
    {
        $this->kantoorruimteMapper = $kantoorruimteMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getBOGAdresMapper()
    {
        return $this->BOGAdresMapper;
    }

    /**
     * @param Mapper $BOGAdresMapper
     * @return MapperRegister
     */
    public function setBOGAdresMapper(Mapper $BOGAdresMapper)
    {
        $this->BOGAdresMapper = $BOGAdresMapper;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getPrijsSpecificatieMapper()
    {
        return $this->prijsSpecificatieMapper;
    }

    /**
     * @param Mapper $prijsSpecificatieMapper
     * @return MapperRegister
     */
    public function setPrijsSpecificatieMapper($prijsSpecificatieMapper)
    {
        $this->prijsSpecificatieMapper = $prijsSpecificatieMapper;
        return $this;
    }

    /**
     * @return ProjectDetails
     */
    public function getProjectDetailsMapper()
    {
        return $this->projectDetailsMapper;
    }

    /**
     * @param ProjectDetails $projectDetailsMapper
     * @return MapperRegister
     */
    public function setProjectDetailsMapper($projectDetailsMapper)
    {
        $this->projectDetailsMapper = $projectDetailsMapper;
        return $this;
    }

    /**
     * @return BouwType
     */
    public function getBouwTypeMapper()
    {
        return $this->bouwTypeMapper;
    }

    /**
     * @param BouwType $bouwTypeMapper
     * @return MapperRegister
     */
    public function setBouwTypeMapper($bouwTypeMapper)
    {
        $this->bouwTypeMapper = $bouwTypeMapper;
        return $this;
    }

    /**
     * @return BouwTypeDetails
     */
    public function getBouwTypeDetailsMapper()
    {
        return $this->bouwTypeDetailsMapper;
    }

    /**
     * @param BouwTypeDetails $bouwTypeDetailsMapper
     * @return MapperRegister
     */
    public function setBouwTypeDetailsMapper($bouwTypeDetailsMapper)
    {
        $this->bouwTypeDetailsMapper = $bouwTypeDetailsMapper;
        return $this;
    }

    /**
     * @return FinancieleGegevens
     */
    public function getFinancieleGegevensMapper()
    {
        return $this->financieleGegevensMapper;
    }

    /**
     * @param FinancieleGegevens $financieleGegevensMapper
     * @return MapperRegister
     */
    public function setFinancieleGegevensMapper($financieleGegevensMapper)
    {
        $this->financieleGegevensMapper = $financieleGegevensMapper;
        return $this;
    }

    /**
     * @return Maten
     */
    public function getMatenMapper()
    {
        return $this->matenMapper;
    }

    /**
     * @param Maten $matenMapper
     * @return MapperRegister
     */
    public function setMatenMapper($matenMapper)
    {
        $this->matenMapper = $matenMapper;
        return $this;
    }

    /**
     * @return Presentatie
     */
    public function getPresentatieMapper()
    {
        return $this->presentatieMapper;
    }

    /**
     * @param Presentatie $presentatieMapper
     * @return MapperRegister
     */
    public function setPresentatieMapper($presentatieMapper)
    {
        $this->presentatieMapper = $presentatieMapper;
        return $this;
    }

    /**
     * @return ProjectMaten
     */
    public function getProjectMatenMapper()
    {
        return $this->projectMatenMapper;
    }

    /**
     * @param ProjectMaten $projectMatenMapper
     * @return MapperRegister
     */
    public function setProjectMatenMapper($projectMatenMapper)
    {
        $this->projectMatenMapper = $projectMatenMapper;
        return $this;
    }
}
