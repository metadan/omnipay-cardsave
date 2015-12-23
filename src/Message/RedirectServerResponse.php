<?php

namespace Omnipay\CardSave\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\ResponseInterface;

/**
 * CardSave Response
 */
class RedirectServerResponse extends AbstractResponse implements ResponseInterface
{
    protected $responseCode;
    protected $message;



    public function isSuccessful()
    {
        return $this->responseCode == 0;
    }

    public function getResponseCode() {
        return $this->responseCode;
    }

    public function getMessage() {
        return $this->message;
    }
}
