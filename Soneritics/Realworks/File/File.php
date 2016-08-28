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
namespace Realworks\File;

use Realworks\RoleInterface\IFile;

/**
 * Class File
 *
 * @package Realworks\File
 * @author Jordi Jolink <mail@jordijolink.nl>
 */
class File implements IFile
{
    /**
     * @var string
     */
    protected $filename;

    /**
     * Get the filename of the file.
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Get the basename of the file.
     * @return string
     */
    public function getBasename()
    {
        return basename($this->filename);
    }

    /**
     * Get the directory of the file, including a trailing (back)slash.
     * @return string
     */
    public function getDirectory()
    {
        return dirname($this->filename) . DIRECTORY_SEPARATOR;
    }

    /**
     * Get the extension of the file.
     */
    public function getExtension()
    {
        $parts = explode('.', $this->filename);
        return $parts[count($parts) - 1];
    }

    /**
     * IFile constructor.
     * @param string $filename
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
    }
}
