<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 13.04.2020
 * Time: 12:23
 */

namespace esas\cmsgate;

use esas\cmsgate\descriptors\CmsConnectorDescriptor;
use esas\cmsgate\descriptors\VendorDescriptor;
use esas\cmsgate\descriptors\VersionDescriptor;
use esas\cmsgate\lang\LocaleLoaderTilda;
use esas\cmsgate\wrappers\OrderWrapperTilda;

class CmsConnectorTilda extends CmsConnectorCached
{
    /**
     * Для удобства работы в IDE и подсветки синтаксиса.
     * @return $this
     */
    public static function getInstance()
    {
        return Registry::getRegistry()->getCmsConnector();
    }

    public function createOrderWrapperCached($cache)
    {
        return new OrderWrapperTilda($cache);
    }

    public function createCmsConnectorDescriptor()
    {
        return new CmsConnectorDescriptor(
            "cmsgate-tilda-lib",
            new VersionDescriptor(
                "v1.15.0",
                "2022-01-12"
            ),
            "Cmsgate Tilda connector",
            "https://bitbucket.esas.by/projects/CG/repos/cmsgate-tilda-lib/browse",
            VendorDescriptor::esas(),
            "tilda"
        );
    }

    public function createLocaleLoaderCached($cache)
    {
        return new LocaleLoaderTilda($cache);
    }

    public function createConfigStorage()
    {
        $cache = Registry::getRegistry()->getCacheRepository()->getSessionCacheSafe();
        return new ConfigStorageTilda($cache);
    }
}