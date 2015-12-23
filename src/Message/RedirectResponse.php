<?php

namespace Omnipay\CardSave\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * CardSave Response
 */
class RedirectResponse extends AbstractResponse implements RedirectResponseInterface
{
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
            return $this->getRequest()->getEndpoint().'?'.http_build_query($this->getRedirectData());
        }
    }

    public function getRedirectMethod()
    {
        return 'POST';
    }

    public function getRedirectData()
    {
        $redirectData = $this->data;

        return $redirectData;
    }
}
