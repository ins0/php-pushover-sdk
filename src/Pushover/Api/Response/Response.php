<?php
namespace Pushover\Api\Response;

class Response implements ResponseInterface
{
    protected $status;
    protected $errors;
    protected $request;
    protected $receipt;
    protected $statusCode;

    /**
     * Exchange API response to Squad Object
     *
     * @param $array
     * @return $this
     */
    public function exchangeArray($array)
    {
        $self = $this;
        $vars = get_class_vars(get_class($this));
        array_map(function($v, $k) use ($vars, $self) {
            $k = str_replace('_', '', $k);
            if( method_exists($self, 'set' . ucwords(strtolower($k)) ) )
            {
                call_user_func(array($self, 'set' . ucwords(strtolower($k))), $v);
            }
        }, $array, array_keys($array));

        return $this;
    }

    /**
     * @param mixed $statusCode
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param mixed $receipt
     */
    public function setReceipt($receipt)
    {
        $this->receipt = $receipt;
    }

    /**
     * @return mixed
     */
    public function getReceipt()
    {
        return $this->receipt;
    }

    /**
     * @param mixed $errors
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
    }

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param mixed $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @return mixed
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }
}