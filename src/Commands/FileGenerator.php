<?php

namespace Geeksdevelop\Sqlmodel\Commands;

use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

abstract class FileGenerator extends GeneratorCommand
{
    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        $file = $this->replaceNamespace($stub, $name)
                     ->replaceClass($stub, $name);

        $file = $this->buildContent($file);
        return $file;
    }

    /**
     * Build 
     *
     * @param  string  $name
     * @return string
     */
    protected function buildContent($stub)
    {
        $tableName = $this->option('table');
        $DataFillable = $this->option('fillable');

        $stub = $this->replaceTableName($stub, $tableName);
        $stub = $this->replaceDataFillable($stub, $DataFillable);

        return $stub;
    }

    /**
     * Replace 
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceTableName($stub, $name)
    {
        return str_replace('NameTable', $name, $stub);
    }

    /**
     * Replace 
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceDataFillable($stub, $data)
    {
        return str_replace('DataFillable', $data, $stub);
    }
}