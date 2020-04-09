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
namespace Realworks\Downloader;

use Realworks\Exceptions\TempDirNotWritable;
use Realworks\Exceptions\UnableToDownload;
use Realworks\RealEstateType\IRealEstateType;
use Realworks\ZippedContent\ZippedContent;

/**
 * Class Downloader
 *
 * @package Realworks\Downloader
 * @author Jordi Jolink <mail@jordijolink.nl>
 */
class Downloader
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var IRealEstateType
     */
    private $realEstateType;

    /**
     * @var string
     */
    private $office;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $tempDir;

    /**
     * Downloader constructor.
     * @param $username
     * @param $password
     * @param IRealEstateType $realEstateType
     * @param string $tempDir
     */
    public function __construct($username, $password, IRealEstateType $realEstateType, $tempDir = '')
    {
        $this->username = $username;
        $this->password = $password;
        $this->realEstateType = $realEstateType;
        $this->setDownloadUrlFromUserPassType($username, $password, $realEstateType);
        $this->setTempDir($tempDir);
    }

    /**
     * Set the download URL.
     * @param string $url
     * @return $this
     */
    public function setDownloadUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Set the office number (optional parameter)
     * @param $office
     * @return $this
     */
    public function setOffice($office)
    {
        $this->office = $office;
        return $this;
    }

    /**
     * Download the zip file.
     * @return ZippedContent
     * @throws UnableToDownload
     */
    public function download()
    {
        $source = $this->url;
        $destination = $this->tempDir . $this->realEstateType . '.zip';

        try {
            $contents = file_get_contents($source);
            file_put_contents($destination, $contents);
            $contents = null;
        } catch (\Exception $e) {
            throw new UnableToDownload("Could not download {$source}", $e);
        }

        return new ZippedContent($destination);
    }

    /**
     * Set the URL from the provided username, password and type.
     * @param string $username
     * @param string $password
     * @param IRealEstateType $realEstateType
     */
    private function setDownloadUrlFromUserPassType($username, $password, IRealEstateType $realEstateType)
    {
        $url = sprintf(
            'https://xml-publish.realworks.nl/servlets/ogexport?koppeling=WEBSITE&og=%s&user=%s&password=%s',
            strtoupper($realEstateType),
            $username,
            $password
        );

        if (!empty($this->office)) {
            $url .= '?&kantoor=' . $this->office;
        }

        $this->setDownloadUrl($url);
    }

    /**
     * Set and check the temp directory to download and extract the file in.
     * @param string $tempDir
     * @throws TempDirNotWritable
     */
    private function setTempDir($tempDir)
    {
        if (empty($tempDir)) {
            $tempDir = sys_get_temp_dir();
        }

        if (!empty($tempDir) && !in_array(substr($tempDir, -1), ['/', '\\'])) {
            $tempDir .= '/';
        }

        $finalTempDir = $tempDir . 'realworks-' . time() . '/';
        if (!is_writable($tempDir) || !mkdir($finalTempDir)) {
            throw new TempDirNotWritable("Temp dir not writable: {$finalTempDir}");
        }

        $this->tempDir = $finalTempDir;
    }
}
