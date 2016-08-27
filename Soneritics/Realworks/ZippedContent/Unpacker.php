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
            $this->setUnpackDirectory($zippedContent->getDirectory() . $zippedContent->getBasename());
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
     */
    public function setUnpackDirectory($directory)
    {
        // TODO: Implementation
        return $this;
    }

    /**
     * Unpack the zip file.
     * @return array of Realworks\File\File objects
     */
    public function unpack()
    {
        // TODO: implementation
        return [];
    }
}
