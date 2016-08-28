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
 * Example file for the complete Realworks flow.
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
$validator = new \Realworks\ZippedContent\Validator($zippedContent);
if (!$validator->isValid()) {
    throw new \Exception('Zipped content is invalid.');
}

// Step 3: Unpack the zipped content
$unpacker = new \Realworks\ZippedContent\Unpacker($zippedContent);
$fileList = $unpacker->unpack();

# Find the XML and XSD file and ignore other files
$xmlFile = null;
$xsdFile = null;
if (!empty($fileList)) {
    foreach ($fileList as $file) {
        if (is_a($file, 'Realworks\File\XMLFile')) {
            $xmlFile = $file;
        } elseif (is_a($file, 'Realworks\File\XSDFile')) {
            $xsdFile = $file;
        }
    }
}

# If one of the files is missing, do not proceed
if ($xmlFile === null) {
    throw new \Exception('No XML file was found to process.');
}

if ($xsdFile === null) {
    throw new \Exception('No XSD file was found to validate the XML. Aborting.');
}

// Step 4: Validate the XML file against the XSD
$xmlFileValidator = new \Realworks\File\Validator\XMLFileValidator($xmlFile, $xsdFile);
if (!$xmlFileValidator->isValid()) {
    throw new \Exception('The XML could not be validated against the XSD.');
}

// Step 5: Process the XML file
$parserFactory = new \Realworks\Parser\ParserFactory;

// You can simply extend an existing parser and overwrite methods.
// To a custom parser:
// $parserFactory->addCustomParser($type, new CustomParser);

$parser = $parserFactory->build($type);

$result = $parser->parse($xmlFile);
print_r($result);
