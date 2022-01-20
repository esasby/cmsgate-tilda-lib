<?php
/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 27.09.2018
 * Time: 13:09
 */

namespace esas\cmsgate\lang;

use esas\cmsgate\cache\LocaleLoaderCache;
use esas\cmsgate\tilda\RequestParamsTilda;

class LocaleLoaderTilda extends LocaleLoaderCache
{
    public function getLocale()
    {
        if ($this->orderCache == null)
            return parent::getLocale();
        return $this->orderCache->getOrderData()[RequestParamsTilda::LANG];
    }


    public function getCmsVocabularyDir()
    {
        return dirname(__FILE__);
    }
}