<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 15.07.2019
 * Time: 13:14
 */

namespace esas\cmsgate;

class ConfigStorageTilda extends ConfigStorageCached
{
    public function __construct($orderCache)
    {
        parent::__construct($orderCache);
    }

    public function getConstantConfigValue($key)
    {
        switch ($key) {
            case ConfigFields::orderPaymentStatusPending():
                return "cmsgate_pending";
            case ConfigFields::orderPaymentStatusPayed():
                return "cmsgate_payed";
            case ConfigFields::orderPaymentStatusFailed():
                return "cmsgate_failed";
            case ConfigFields::orderPaymentStatusCanceled():
                return "cmsgate_canceled";
            default:
                return null;
        }
    }

    public function createCmsRelatedKey($key) {
        return 'ps_' . $key;
    }
}