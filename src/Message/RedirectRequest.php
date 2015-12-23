<?php

namespace Omnipay\CardSave\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * CardSave Purchase Request
 */
class RedirectRequest extends AbstractRequest
{
    protected $endpoint = 'https://mms.cardsaveonlinepayments.com/Pages/PublicPages/PaymentForm.aspx';
    protected $transactionType = 'SALE';

    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    public function setPreSharedKey($value)
    {
        return $this->setParameter('preSharedKey', $value);
    }

    public function setServerResultUrl($value)
    {
        return $this->setParameter('serverResultUrl', $value);
    }

    public function setBaseUrl($value)
    {
        return $this->setParameter('baseUrl', $value);
    }


    private function buildData()
    {
        $dateTime = date('Y-m-d H:i:s O');
        $data['MerchantID'] = $this->getMerchantId();
        $data['Amount'] = $this->getAmountInteger();
        $data['CurrencyCode'] = $this->getCurrencyNumeric();
        $data['EchoAVSCheckResult'] = 'true';
        $data['EchoCV2CheckResult'] = 'true';
        $data['EchoThreeDSecureAuthenticationCheckResult'] = 'true';
        $data['EchoCardType'] = 'true';
        $data['OrderID'] = $this->getTransactionId();
        $data['TransactionType'] = $this->transactionType;
        $data['TransactionDateTime'] = $dateTime;
        $data['CallbackURL'] = $this->getBaseUrl().$this->getReturnUrl();
        $data['OrderDescription'] = $this->getDescription();
        $data['CustomerName'] = $this->stripGWInvalidChars($this->getCard()->getName());
        $data['Address1'] = $this->stripGWInvalidChars($this->getCard()->getAddress1());
        $data['Address2'] = $this->stripGWInvalidChars($this->getCard()->getAddress2());
        $data['Address3'] = $this->stripGWInvalidChars('');
        $data['Address4'] = $this->stripGWInvalidChars('');
        $data['City'] = $this->stripGWInvalidChars($this->getCard()->getCity());
        $data['State'] = $this->stripGWInvalidChars($this->getCard()->getState());
        $data['PostCode'] = $this->stripGWInvalidChars($this->getCard()->getPostcode());
        $data['CountryCode'] = $this->getCard()->getCountry();
        $data['EmailAddress'] = $this->stripGWInvalidChars('');
        $data['PhoneNumber'] = $this->stripGWInvalidChars('');
        $data['EmailAddressEditable'] = 'false';
        $data['PhoneNumberEditable'] = 'false';
        $data['CV2Mandatory'] = 'true';
        $data['Address1Mandatory'] = 'true';
        $data['CityMandatory'] = 'true';
        $data['PostCodeMandatory'] = 'true';
        $data['StateMandatory'] = 'true';
        $data['CountryMandatory'] = 'true';
        $data['ResultDeliveryMethod'] = 'SERVER';
        $data['ServerResultURL'] = $this->getBaseUrl().$this->getServerResultUrl();
        $data['PaymentFormDisplaysResult'] = 'false';
//        $data['ServerResultURLCookieVariables'] = '';
//        $data['ServerResultURLFormVariables'] = '';
//        $data['ServerResultURLQueryStringVariables'] = '';
//        $data['ThreeDSecureCompatMode'] = 'false';
//        $data['ServerResultCompatMode'] = 'false';
        return $data;
    }

    public function getData()
    {
        $this->validate('amount');

        $data = $this->buildData();
        $data['HashDigest'] = $this->buildHashDigest($data);

        return $data;
    }

    private function stripGWInvalidChars($strToCheck)
    {
        $toReplace = array("#", "\\", ">", "<", "\"", "[", "]");
        $cleanString = str_replace($toReplace, "", $strToCheck);

        return $cleanString;
    }

    private function buildHashDigest($data)
    {
        $HashString = "PreSharedKey=".$this->getPreSharedKey();
        $HashString = $HashString.'&MerchantID='.$data['MerchantID'];
        $HashString = $HashString.'&Password='.$this->getPassword();
        $HashString = $HashString.'&Amount='.$data['Amount'];
        $HashString = $HashString.'&CurrencyCode='.$data['CurrencyCode'];
        $HashString = $HashString.'&EchoAVSCheckResult='.$data['EchoAVSCheckResult'];
        $HashString = $HashString.'&EchoCV2CheckResult='.$data['EchoCV2CheckResult'];
        $HashString = $HashString.'&EchoThreeDSecureAuthenticationCheckResult='.$data['EchoThreeDSecureAuthenticationCheckResult'];
        $HashString = $HashString.'&EchoCardType='.$data['EchoCardType'];
        $HashString = $HashString.'&OrderID='.$data['OrderID'];
        $HashString = $HashString.'&TransactionType='.$data['TransactionType'];
        $HashString = $HashString.'&TransactionDateTime='.$data['TransactionDateTime'];
        $HashString = $HashString.'&CallbackURL='.$data['CallbackURL'];
        $HashString = $HashString.'&OrderDescription='.$data['OrderDescription'];
        $HashString = $HashString.'&CustomerName='.$data['CustomerName'];
        $HashString = $HashString.'&Address1='.$data['Address1'];
        $HashString = $HashString.'&Address2='.$data['Address2'];
        $HashString = $HashString.'&Address3='.$data['Address3'];
        $HashString = $HashString.'&Address4='.$data['Address4'];
        $HashString = $HashString.'&City='.$data['City'];
        $HashString = $HashString.'&State='.$data['State'];
        $HashString = $HashString.'&PostCode='.$data['PostCode'];
        $HashString = $HashString.'&CountryCode='.$data['CountryCode'];
        $HashString = $HashString.'&EmailAddress='.$data['EmailAddress'];
        $HashString = $HashString.'&PhoneNumber='.$data['PhoneNumber'];
        $HashString = $HashString.'&EmailAddressEditable='.$data['EmailAddressEditable'];
        $HashString = $HashString.'&PhoneNumberEditable='.$data['PhoneNumberEditable'];
        $HashString = $HashString."&CV2Mandatory=".$data['CV2Mandatory'];
        $HashString = $HashString."&Address1Mandatory=".$data['Address1Mandatory'];
        $HashString = $HashString."&CityMandatory=".$data['CityMandatory'];
        $HashString = $HashString."&PostCodeMandatory=".$data['PostCodeMandatory'];
        $HashString = $HashString."&StateMandatory=".$data['StateMandatory'];
        $HashString = $HashString."&CountryMandatory=".$data['CountryMandatory'];
        $HashString = $HashString."&ResultDeliveryMethod=".$data['ResultDeliveryMethod'];
        $HashString = $HashString."&ServerResultURL=".$data['ServerResultURL'];
        $HashString = $HashString."&PaymentFormDisplaysResult=".$data['PaymentFormDisplaysResult'];
//        $HashString = $HashString."&ServerResultURLCookieVariables=".$data['ServerResultURLCookieVariables'];
//        $HashString = $HashString."&ServerResultURLFormVariables=".$data['ServerResultURLFormVariables'];
//        $HashString = $HashString."&ServerResultURLQueryStringVariables=".$data['ServerResultURLQueryStringVariables'];
        $HashDigest = sha1($HashString);

        return $HashDigest;
    }

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function getServerResultURL()
    {
        return $this->getParameter('serverResultUrl');
    }

    public function getBaseUrl()
    {
        return $this->getParameter('baseUrl');
    }

    public function getPreSharedKey()
    {
        return $this->getParameter('preSharedKey');
    }

    public function sendData($data)
    {
        return $this->response = new RedirectResponse($this, $data);
    }

    public function getEndPoint()
    {
        return $this->endpoint;
    }
}
