<?php
/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 27.09.2018
 * Time: 13:09
 */

namespace esas\cmsgate\tilda\lang;
use esas\cmsgate\bridge\lang\LocaleLoaderBridge;
use esas\cmsgate\tilda\protocol\RequestParamsTilda;

class LocaleLoaderTilda extends LocaleLoaderBridge
{
    private $orderCache;

    public function __construct($orderCache)
    {
        parent::__construct();
        $this->orderCache = $orderCache;
    }

    public function getLocale()
    {
        if ($this->orderCache == null)
            return parent::getLocale();
        return $this->orderCache->getOrderData()[RequestParamsTilda::LANG];
    }
}