<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 13.04.2020
 * Time: 12:23
 */

namespace esas\cmsgate\tilda;

use esas\cmsgate\bridge\CmsConnectorBridge;
use esas\cmsgate\bridge\service\OrderService;
use esas\cmsgate\descriptors\CmsConnectorDescriptor;
use esas\cmsgate\descriptors\VendorDescriptor;
use esas\cmsgate\descriptors\VersionDescriptor;
use esas\cmsgate\Registry;
use esas\cmsgate\tilda\lang\LocaleLoaderTilda;
use esas\cmsgate\tilda\protocol\RequestParamsTilda;
use esas\cmsgate\tilda\wrappers\OrderWrapperTilda;

class CmsConnectorTilda extends CmsConnectorBridge
{
    /**
     * Для удобства работы в IDE и подсветки синтаксиса.
     * @return $this
     */
    public static function fromRegistry()
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
                "v2.0.2",
                "2023-07-24"
            ),
            "Cmsgate Tilda connector",
            "https://github.com/esasby/cmsgate-tilda-lib/",
            VendorDescriptor::esas(),
            "tilda"
        );
    }

    public function createLocaleLoaderCached($cache)
    {
        return new LocaleLoaderTilda($cache);
    }

    public function getNotificationURL() {
        $cache = OrderService::fromRegistry()->getSessionOrderSafe();
        return $cache->getOrderData()[RequestParamsTilda::NOTIFICATION_URL];
    }

    public function getReturnToShopSuccessURL()
    {
        $cache = OrderService::fromRegistry()->getSessionOrderSafe();
        return $cache->getOrderData()[RequestParamsTilda::SUCCESS_URL];
    }

    public function getReturnToShopFailedURL()
    {
        $cache = OrderService::fromRegistry()->getSessionOrderSafe();
        return $cache->getOrderData()[RequestParamsTilda::FAILED_URL];
    }
}