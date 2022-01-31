<?php
/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 27.09.2018
 * Time: 13:09
 */

namespace esas\cmsgate\lang;
use esas\cmsgate\tilda\RequestParamsTilda;

class LocaleLoaderTilda extends LocaleLoaderCache
{
    public function __construct($orderCache)
    {
        parent::__construct($orderCache);
    }

    public function getLocale()
    {
        if ($this->orderCache == null)
            return parent::getLocale();
        return $this->orderCache->getOrderData()[RequestParamsTilda::LANG];
    }
}