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
        $data['CallbackURL'] = 'http://shine.com/payback';
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
        $data['ServerResultURL'] = 'http://shine.com/server-result';
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
        $this->validate('amount', 'card');

        $this->removeInvalidChars();
        $data = $this->buildData();
        $data['HashDigest'] = $this->buildHashDigest($data);

        return $data;
    }

    private function removeInvalidChars()
    {
        $fields = array("CustomerName", "Address1", "Address2", "Address3", "Address4", "City", "State", "PostCode", "EmailAddress", "PhoneNumber");
        foreach ($fields as $field) {
            if (isset($this->$field)) {
                $this->$field = $this->stripGWInvalidChars($this->$field);
            }
        }
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

    public function getPreSharedKey()
    {
        return $this->getParameter('preSharedKey');
    }

    public function sendData($data)
    {
        return $this->response = new RedirectResponse($this, $data);

//        // the PHP SOAP library sucks, and SimpleXML can't append element trees
//        // TODO: find PSR-0 SOAP library
//        $document = new DOMDocument('1.0', 'utf-8');
//        $envelope = $document->appendChild(
//            $document->createElementNS('http://schemas.xmlsoap.org/soap/envelope/', 'soap:Envelope')
//        );
//        $envelope->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
//        $envelope->setAttribute('xmlns:xsd', 'http://www.w3.org/2001/XMLSchema');
//        $body = $envelope->appendChild($document->createElement('soap:Body'));
//        $body->appendChild($document->importNode(dom_import_simplexml($data), true));
//
//        // post to Cardsave
//        $headers = array(
//            'Content-Type' => 'text/xml; charset=utf-8',
//            'SOAPAction' => $this->namespace.$data->getName());
//
//        $httpResponse = $this->httpClient->post($this->endpoint, $headers, $document->saveXML())->send();
//
//        return $this->response = new Response($this, $httpResponse->getBody());
    }

    public function getEndPoint()
    {
        return $this->endpoint;
    }
}
