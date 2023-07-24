<?php


namespace esas\cmsgate\tilda\security;


use esas\cmsgate\bridge\security\CmsAuthServiceBySecret;
use esas\cmsgate\tilda\protocol\RequestParamsTilda;

abstract class CmsAuthServiceTilda extends CmsAuthServiceBySecret
{
    public function generateVerificationSignature($request, $secret)
    {
        $line = $request[RequestParamsTilda::ORDER_ID]
            . '|' . $request[RequestParamsTilda::ORDER_AMOUNT]
            . '|' . $secret
            . '|' . $request[RequestParamsTilda::ORDER_CURRENCY];
        $this->logger->info('Sign values: ' . $line);
        return hash('sha256', $line);
    }

    public function getRequestFieldSignature() {
        return RequestParamsTilda::SIGNATURE;
    }
}