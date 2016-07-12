<?php

namespace baibaratsky\WebMoney\Api\WMC\WMC1;

use baibaratsky\WebMoney\Api\WMC;
use baibaratsky\WebMoney\Exception\ApiException;
use baibaratsky\WebMoney\Request\RequestValidator;
use baibaratsky\WebMoney\Signer;

/**
 * Class Request
 *
 * @link http://wiki.wmtransfer.com/projects/webmoney/wiki/Interface_WMC1
 */
class Request extends WMC\Request
{

    /** @var string payment/purse */
    protected $payeePurse;

    /** @var string payment/@currency */
    protected $currency;

    /** @var float payment/price */
    protected $price;

    /** @var int payment/phone */
    protected $phone = 0;

    /**
     * @param string $authType
     *
     * @throws ApiException
     */
    public function __construct($authType = self::AUTH_CLASSIC)
    {
        switch ($authType) {
            case self::AUTH_CLASSIC:
                $this->url = 'https://transfer.gdcert.com/ATM/Xml/PrePayment1.ashx';
                break;

            case self::AUTH_LIGHT:
                $this->url = 'https://transfer.gdcert.com/ATM/Xml/PrePayment1.ashx';
                $this->signature = base64_encode(
                    $this->signerWmid . $this->currency .
                    $this->payeePurse . $this->phone .
                    $this->price
                );

                break;

            default:
                throw new ApiException('This interface doesn\'t support the authentication type given.');
        }

        parent::__construct($authType);
    }

    /**
     * @return array
     */
    protected function getValidationRules()
    {
        return array(
            RequestValidator::TYPE_REQUIRED => array(
                'price', 'currency'
            )
        );
    }

    /**
     * @return string
     */
    public function getData()
    {
        $xml = '<w3s.request lang="' . $this->getLang() . '">';
        $xml .= self::xmlElement('wmid', $this->getSignerWmid());
        $xml .= '<sign type="' . $this->getAuthTypeNum() . '">' . $this->signature . '</sign>';
        $xml .= '<payment currency="' . $this->getCurrency() . '">';
        $xml .= self::xmlElement('purse', $this->getPayeePurse());
        $xml .= self::xmlElement('phone', $this->getPhone());
        $xml .= self::xmlElement('price', $this->getPrice());
        $xml .= '</payment>';
        $xml .= '</w3s.request>';

        return $xml;
    }

    /**
     * @return string
     */
    public function getResponseClassName()
    {
        return Response::className();
    }

    /**
     * @param Signer $requestSigner
     */
    public function sign(Signer $requestSigner = null)
    {
        if ($this->authType === self::AUTH_CLASSIC) {
            $this->signature = $requestSigner->sign(
                $this->signerWmid . $this->currency .
                $this->payeePurse . $this->phone .
                $this->price
            );
        }
    }

    /**
     * @return string
     */
    public function getPayeePurse()
    {
        return $this->payeePurse;
    }

    /**
     * @param string $payeePurse
     */
    public function setPayeePurse($payeePurse)
    {
        $this->payeePurse = (string)$payeePurse;
    }

    /**
     * @return float
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param $phone
     */
    public function setPhone($phone)
    {
        $this->phone = (string)$phone;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param $price
     */
    public function setPrice($price)
    {
        $this->price = (float)$price;
    }

    /**
     * @return string "USD"|"EUR"
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param $currency "USD"|"EUR"
     */
    public function setCurrency($currency)
    {
        $this->currency = (string)$currency;
    }
}
