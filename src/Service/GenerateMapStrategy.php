<?php
namespace Service;

use Exception;

class GenerateMapStrategy
{
    /**
     * @param $id
     * @param $country
     * @return GenerateMapStrategyDb|GenerateMapStrategyFile
     * @throws Exception
     */
    public static function getStrategy($id, $country)
    {
        switch ($id) {
            case 'file':
                return new GenerateMapStrategyFile($country);
            case 'db':
                return new GenerateMapStrategyDb();
            default:
                throw new Exception("Unknown strategy generate map");
        }
    }
}
