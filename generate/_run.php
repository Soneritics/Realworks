<?php
/**
 * Put the PHP classes in the Soneritics\RealEstate namespace in a directory,
 * put this script in the same directory and the necessary files for the namespace
 * Realworks\Parser\Mappers will be created.
 */


$mappers = [];
foreach (scandir(__DIR__) as $file) {
    if (!in_array($file, ['.', '..', '_run.php'])) {
        $className = substr($file, 0, -4);
        $mappers[] = $className;
        file_put_contents(
            __DIR__ . '/' . $file,
            "<?php
/*
 * The MIT License
 *
 * Copyright 2016 Jordi Jolink.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the \"Software\"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED \"AS IS\", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace Realworks\Parser\Mappers;

/**
 * Class {$className}
 *
 * @package Realworks\Parser\Mappers
 * @author Jordi Jolink <mail@jordijolink.nl>
 */
class {$className} extends Mapper
{
}"
        );
    }
}


$mapperRegister = "<?php
/*
 * The MIT License
 *
 * Copyright 2016 Jordi Jolink.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the \"Software\"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED \"AS IS\", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
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
{";

foreach ($mappers as $mapper) {
    $mapperRegister .= "\n    /**
     * @var Mapper
     */
    private \$" . lcfirst($mapper) . "Mapper;\n";
}

$mapperRegister .= "\n\n    /**
     * Set up the mappers to use.
     */
    public function __construct()
    {";

foreach ($mappers as $mapper) {
    $mapperRegister .= "\n        \$this->" . lcfirst($mapper) . "Mapper = new {$mapper}(\$this);";
}

$mapperRegister .= "\n    }\n";

foreach ($mappers as $mapper) {
    $mapperRegister .= "\n    /**
     * @return Mapper
     */
    public function get{$mapper}Mapper()
    {
        return \$this->" . lcfirst($mapper) . "Mapper;
    }

    /**
     * @param Mapper \$" . lcfirst($mapper) . "Mapper
     * @return MapperRegister
     */
    public function set{$mapper}Mapper(Mapper \$" . lcfirst($mapper) . "Mapper)
    {
        \$this->" . lcfirst($mapper) . "Mapper = \$" . lcfirst($mapper) . "Mapper;
        return \$this;
    }\n";
}

$mapperRegister .= "\n}";
file_put_contents(__DIR__ . '/MapperRegister.php', $mapperRegister);
