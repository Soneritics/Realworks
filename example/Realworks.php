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

/**
 * Example file for the Realworks flow.
 * Illustrates the parsing of the 'Wonen' data.
 */

require_once __DIR__ . '/../vendor/autoload.php';

// Configs. When you are using this, remove the following line: $downloader->setDownloadUrl('..');
$username = '';
$password = '';

// Step 1: Download the file
$type = new \Realworks\RealEstateType\Wonen;
$downloader = new \Realworks\Downloader\Downloader($username, $password, $type);

# Explicitly overrule the URL, so no user/pass is needed
$downloader->setDownloadUrl('http://xml-publish.realworks.nl/servlets/ogexport?koppeling=WEBSITE&og=WONEN&documentatie=true');

$zippedContent = $downloader->download();

// Step 2: Validate the zipped content

// Step 3: Unpack the zipped content
