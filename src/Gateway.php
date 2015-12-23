<?php

namespace Omnipay\CardSave;

use Omnipay\CardSave\Message\CompletePurchaseRequest;
use Omnipay\CardSave\Message\PurchaseRequest;
use Omnipay\Common\AbstractGateway;

/**
 * CardSave Gateway
 *
 * @link http://www.cardsave.net/dev-downloads
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'CardSave';
    }

    public function getDefaultParameters()
    {
        return array(
            'merchantId' => '',
            'password' => '',
            'preSharedKey' => '',
            'serverResultUrl' => '',
            'baseUrl' => ''
        );
    }

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    public function getPreSharedKey()
    {
        return $this->getParameter('preSharedKey');
    }

    public function setPreSharedKey($value)
    {
        return $this->setParameter('preSharedKey', $value);
    }

    public function getServerResultUrl()
    {
        return $this->getParameter('serverResultUrl');
    }

    public function setServerResultUrl($value)
    {
        return $this->setParameter('serverResultUrl', $value);
    }

    public function getBaseUrl()
    {
        return $this->getParameter('baseUrl');
    }

    public function setBaseUrl($value)
    {
        return $this->setParameter('baseUrl', $value);
    }



    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\CardSave\Message\PurchaseRequest', $parameters);
    }

    public function purchaseViaRedirect(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\CardSave\Message\RedirectRequest', $parameters);
    }

    public function acceptNotification(array $parameters = array()) {
        return $this->createNotification('\Omnipay\CardSave\Message\RedirectServerNotification',$parameters);
    }

    public function supportsAcceptNotification()
    {
        return true;
    }

    public function referencedPurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\CardSave\Message\ReferencedPurchaseRequest', $parameters);
    }

    public function completePurchaseViaRedirect(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\CardSave\Message\CompletePurchaseRequest', $parameters);
    }


    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\CardSave\Message\CompletePurchaseRequest', $parameters);
    }

    public function refund(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\CardSave\Message\RefundRequest', $parameters);
    }


    /**
     * Create and initialize a notification object
     *
     * This function is usually used to create objects of type
     * Omnipay\CardSave\Message\AbstractNotification (or a non-abstract subclass of it)
     * and initialise them with using existing parameters from this gateway.
     *
     * Example:
     *
     * <code>
     *   class MyRequest extends \Omnipay\CardSave\Message\AbstractNotification {};
     *
     *   class MyGateway extends \Omnipay\Common\AbstractGateway {
     *     function myNotification($parameters) {
     *       $this->createNotification('MyNotification', $parameters);
     *     }
     *   }
     *
     *   // Create the gateway object
     *   $gw = Omnipay::create('MyGateway');
     *
     *   // Create the notification object
     *   $myNotification = $gw->myNotification($someParameters);
     * </code>
     *
     * @see \Omnipay\Common\Message\AbstractNotification
     * @param string $class The request class name
     * @param array $parameters
     * @return \Omnipay\CardSave\Message\AbstractNotification
     */
    protected function createNotification($class, array $parameters)
    {
        $obj = new $class($this->httpRequest);

        return $obj->initialize(array_replace($this->getParameters(), $parameters));
    }
}
