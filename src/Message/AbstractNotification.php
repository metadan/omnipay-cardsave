<?php
/**
 * Created by PhpStorm.
 * User: dan
 * Date: 22/12/15
 * Time: 15:48
 */

namespace Omnipay\CardSave\Message;


use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Exception\RuntimeException;
use Omnipay\Common\Helper;
use Omnipay\Common\Message\NotificationInterface;
use Omnipay\Common\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

abstract class AbstractNotification implements NotificationInterface
{
    /* NB Suggest moving all the parameter methods to a trait so they can be shared by AbstractRequest & AbstractNotification */

    /**
     * The request parameters
     *
     * @var \Symfony\Component\HttpFoundation\ParameterBag
     */
    protected $parameters;

    /**
     * The HTTP request object.
     *
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $httpRequest;

    protected $transactionReference;

    protected $transactionStatus;

    protected $message;

    /**
     * Create a new Request
     *
     * @param HttpRequest     $httpRequest A Symfony HTTP request object
     */
    public function __construct(HttpRequest $httpRequest)
    {
        $this->httpRequest = $httpRequest;
        $this->initialize();
    }

    /**
     * Initialize the object with parameters.
     *
     * If any unknown parameters passed, they will be ignored.
     *
     * @param array $parameters An associative array of parameters
     *
     * @return $this
     * @throws RuntimeException
     */
    public function initialize(array $parameters = array())
    {
        $this->parameters = new ParameterBag;

        Helper::initialize($this, $parameters);

        return $this;
    }

    /**
     * Get all parameters as an associative array.
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters->all();
    }

    /**
     * Get a single parameter.
     *
     * @param string $key The parameter key
     * @return mixed
     */
    protected function getParameter($key)
    {
        return $this->parameters->get($key);
    }

    /**
     * Set a single parameter
     *
     * @param string $key The parameter key
     * @param mixed $value The value to set
     * @return AbstractNotification Provides a fluent interface
     * @throws RuntimeException if a request parameter is modified after the request has been sent.
     */
    protected function setParameter($key, $value)
    {
        if (null !== $this->response) {
            throw new RuntimeException('Request cannot be modified after it has been sent!');
        }

        $this->parameters->set($key, $value);

        return $this;
    }

    /**
     * Validate the request.
     *
     * This method is called internally by gateways to avoid wasting time with an API call
     * when the request is clearly invalid.
     *
     * @param string ... a variable length list of required parameters
     * @throws InvalidRequestException
     */
    public function validate()
    {
        foreach (func_get_args() as $key) {
            $value = $this->parameters->get($key);
            if (empty($value)) {
                throw new InvalidRequestException("The $key parameter is required");
            }
        }
    }

}