<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface IModelConverterService
{
    /**
     * export query records to file.
     *
     * @param string $format
     * @param mixed $models
     * @param array $fieldsToExport
     * @return string
     */
    function ExportModelsToFile(string $format, $models, $fieldsToExport);

}
