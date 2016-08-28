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
namespace Realworks\ZippedContent;

use Realworks\Exceptions\CanNotCreateDirectory;
use Realworks\Exceptions\CanNotOpenZipFile;
use Realworks\File\File;
use Realworks\File\XMLFile;
use Realworks\File\XSDFile;

/**
 * Class Unpacker
 *
 * @package Realworks\ZippedContent
 * @author Jordi Jolink <mail@jordijolink.nl>
 */
class Unpacker
{
    /**
     * @var ZippedContent
     */
    private $zippedContent;

    /**
     * @var string
     */
    private $directory;

    /**
     * Unpacker constructor.
     * @param ZippedContent $zippedContent
     * @param string|null $unpackDirectory
     */
    public function __construct(ZippedContent $zippedContent, $unpackDirectory = null)
    {
        $this->setZippedContent($zippedContent);
        if ($unpackDirectory === null) {
            $this->setUnpackDirectory($zippedContent->getDirectory() . 'extract_' . $zippedContent->getBasename());
        } else {
            $this->setUnpackDirectory($unpackDirectory);
        }
    }

    /**
     * Setter for a ZippedContent object.
     * @param ZippedContent $zippedContent
     * @return $this
     */
    public function setZippedContent(ZippedContent $zippedContent)
    {
        $this->zippedContent = $zippedContent;
        return $this;
    }

    /**
     * Set an unpack directory.
     * @param string $directory
     * @return $this
     * @throws CanNotCreateDirectory
     */
    public function setUnpackDirectory($directory)
    {
        if (!file_exists($directory)) {
            $created = @mkdir($directory, 0777, true);
            if ($created === false) {
                throw new CanNotCreateDirectory("Can not create directory: {$directory}");
            }
        }

        if (!in_array(substr($directory, -1), ['/', '\\'])) {
            $directory .= '/';
        }

        $this->directory = $directory;
        return $this;
    }

    /**
     * Unpack the zip file.
     * @return array of Realworks\File\File objects
     * @throws CanNotOpenZipFile
     */
    public function unpack()
    {
        $result = [];

        $zip = new \ZipArchive;
        if ($zip->open($this->zippedContent->getFilename()) !== true) {
            throw new CanNotOpenZipFile("Can not open zip file: {$this->zippedContent->getFilename()}");
        }

        $zip->extractTo($this->directory);
        $zip->close();

        foreach (scandir($this->directory) as $extractedFileName) {
            $fullFilename = $this->directory . $extractedFileName;
            if (!is_dir($fullFilename)) {
                $file = new File($fullFilename);
                $extension = strtolower($file->getExtension());

                switch ($extension) {
                    case 'xml':
                        $result[] = new XMLFile($fullFilename);
                        break;
                    case 'xsd':
                        $result[] = new XSDFile($fullFilename);
                        break;
                    default:
                        $result[] = $file;
                }
            }
        }

        return $result;
    }
}
