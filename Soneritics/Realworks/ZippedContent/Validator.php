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

use Realworks\File\File;
use Realworks\RoleInterface\IValidator;

/**
 * ZippedContent Validator
 *
 * @package Realworks\ZippedContent
 * @author Jordi Jolink <mail@jordijolink.nl>
 */
class Validator implements IValidator
{
    /**
     * @var ZippedContent
     */
    private $zippedContent;

    /**
     * Validator constructor.
     * @param ZippedContent $zippedContent
     */
    public function __construct(ZippedContent $zippedContent)
    {
        $this->zippedContent = $zippedContent;
    }

    /**
     * Validate the object.
     * @return boolean
     */
    public function isValid()
    {
        # File exists
        if (!file_exists($this->zippedContent->getFilename())) {
            return false;
        }

        # Can open zip file
        $zip = new \ZipArchive;
        if ($zip->open($this->zippedContent->getFilename()) !== true) {
            return false;
        }

        # Check if an XML and XSD file are present
        $xmlFile = false;
        $xsdFile = false;
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);
            $file = new File($filename);
            $extension = strtolower($file->getExtension());

            if ($extension === 'xml') {
                $xmlFile = true;
            } elseif ($extension === 'xsd') {
                $xsdFile = true;
            }
        }

        $zip->close();

        return $xmlFile && $xsdFile;
    }
}
