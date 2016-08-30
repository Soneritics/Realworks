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
 * @package Realworks\Parser\Mappers
 * @author Jordi Jolink <mail@jordijolink.nl>
 */
final class MapperRegister
{
    /**
     * @var Mapper
     */
    private $houseMapper;

    /**
     * @var Mapper
     */
    private $projectMapper;

    /**
     * @var Mapper
     */
    private $objectDetailsMapper;

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
    private $bouwgrondMapper;

    /**
     * @var Mapper
     */
    private $overigOGMapper;

    /**
     * @var Mapper
     */
    private $mediaMapper;

    /**
     * Set up the mappers to use.
     */
    public function __construct()
    {
        $this->houseMapper = new House($this);
        $this->projectMapper = new Project($this);
        $this->objectDetailsMapper = new ObjectDetails($this);
        $this->webMapper = new Web($this);
        $this->wonenMapper = new Wonen($this);
        $this->bouwgrondMapper = new Bouwgrond($this);
        $this->overigOGMapper = new OverigOG($this);
        $this->mediaMapper = new Media($this);
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
    public function setHouseMapper($houseMapper)
    {
        $this->houseMapper = $houseMapper;
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
    public function setProjectMapper($projectMapper)
    {
        $this->projectMapper = $projectMapper;
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
    public function setObjectDetailsMapper($objectDetailsMapper)
    {
        $this->objectDetailsMapper = $objectDetailsMapper;
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
    public function setWebMapper($webMapper)
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
    public function setWonenMapper($wonenMapper)
    {
        $this->wonenMapper = $wonenMapper;
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
    public function setBouwgrondMapper($bouwgrondMapper)
    {
        $this->bouwgrondMapper = $bouwgrondMapper;
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
    public function setOverigOGMapper($overigOGMapper)
    {
        $this->overigOGMapper = $overigOGMapper;
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
    public function setMediaMapper($mediaMapper)
    {
        $this->mediaMapper = $mediaMapper;
        return $this;
    }
}
