<?php

namespace Omnipay\CardSave\Message;

use DOMDocument;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;

/**
 * CardSave Response
 */
class RedirectResponse extends AbstractResponse implements RedirectResponseInterface
{
//    public function __construct(RequestInterface $request, $data)
//    {
//        $this->request = $request;
//
//        // we only care about the content of the soap:Body element
//        $responseDom = new DOMDocument;
//        $responseDom->loadXML($data);
//        $this->data = simplexml_import_dom($responseDom->documentElement->firstChild->firstChild);
//
//        $resultElement = $this->getResultElement();
//        if (!isset($resultElement->StatusCode)) {
//            throw new InvalidResponseException;
//        }
//    }

//    public function getResultElement()
//    {
//        $resultElement = preg_replace('/Response$/', 'Result', $this->data->getName());
//
//        return $this->data->$resultElement;
//    }

    public function isSuccessful()
    {
        return false;
    }

    public function isRedirect()
    {
        return true;
    }

    public function getRedirectUrl()
    {
        if ($this->isRedirect()) {
            return $this->getRequest()->getEndpoint().'?'.http_build_query($this->data);
        }
    }

    public function getRedirectMethod()
    {
        return 'POST';
    }

    public function getRedirectData()
    {
        $redirectData = $this->data;
//            'PaReq' => (string) $this->data->TransactionOutputData->ThreeDSecureOutputData->PaREQ,
//            'TermUrl' => $this->getRequest()->getReturnUrl(),
//            'MD' => (string) $this->data->TransactionOutputData['CrossReference'],
//        );
        return $redirectData;
    }

}
