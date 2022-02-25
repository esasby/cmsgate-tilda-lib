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
use esas\cmsgate\tilda\RequestParamsTilda;
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
                "v1.17.2",
                "2022-02-25"
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
        return new ConfigStorageTilda();
    }

    public function getNotificationURL() {
        $cache = CloudRegistry::getRegistry()->getOrderCacheService()->getSessionOrderCacheSafe();
        return $cache->getOrderData()[RequestParamsTilda::NOTIFICATION_URL];
    }

    public function getReturnToShopSuccessURL()
    {
        $cache = CloudRegistry::getRegistry()->getOrderCacheService()->getSessionOrderCacheSafe();
        return $cache->getOrderData()[RequestParamsTilda::SUCCESS_URL];
    }

    public function getReturnToShopFailedURL()
    {
        $cache = CloudRegistry::getRegistry()->getOrderCacheService()->getSessionOrderCacheSafe();
        return $cache->getOrderData()[RequestParamsTilda::FAILED_URL];
    }
}