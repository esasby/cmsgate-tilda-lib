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
use esas\cmsgate\utils\CMSGateException;
use esas\cmsgate\wrappers\OrderWrapperTilda;

abstract class CmsConnectorTilda extends CmsConnectorCached
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
                "v1.16.5",
                "2022-02-07"
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

    public function getNotificationURL() {
        $cache = Registry::getRegistry()->getCacheRepository()->getSessionCacheSafe();
        return $cache->getOrderData()[RequestParamsTilda::NOTIFICATION_URL];
    }

    public abstract function getNotificationSecret();

    /**
     * @throws CMSGateException
     * @param $request
     * @return mixed
     */
    public abstract function checkSignature($request);

    /**
     * @param OrderWrapperTilda $orderWrapper
     * @return mixed
     */
    public abstract function createNotificationSignature($orderWrapper);

    public function getReturnToShopSuccessURL()
    {
        $cache = Registry::getRegistry()->getCacheRepository()->getSessionCacheSafe();
        return $cache->getOrderData()[RequestParamsTilda::SUCCESS_URL];
    }

    public function getReturnToShopFailedURL()
    {
        $cache = Registry::getRegistry()->getCacheRepository()->getSessionCacheSafe();
        return $cache->getOrderData()[RequestParamsTilda::FAILED_URL];
    }
}