<?php
namespace Pushover\Api\Response;

class ReceiptResponse implements ResponseInterface
{
    protected $status;
    protected $acknowledged;
    protected $acknowledged_at;
    protected $acknowledged_by;
    protected $last_delivered_at;
    protected $expired;
    protected $expires_at;
    protected $called_back;
    protected $called_back_at;
    protected $request;
    protected $statusCode;

    /**
     * Exchange API response to Object
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
     * @param mixed $acknowledged
     */
    public function setAcknowledged($acknowledged)
    {
        $this->acknowledged = $acknowledged;
    }

    /**
     * @return mixed
     */
    public function getAcknowledged()
    {
        return $this->acknowledged;
    }

    /**
     * @param mixed $acknowledged_at
     */
    public function setAcknowledgedAt($acknowledged_at)
    {
        $this->acknowledged_at = $acknowledged_at;
    }

    /**
     * @return mixed
     */
    public function getAcknowledgedAt()
    {
        return $this->acknowledged_at;
    }

    /**
     * @param mixed $acknowledged_by
     */
    public function setAcknowledgedBy($acknowledged_by)
    {
        $this->acknowledged_by = $acknowledged_by;
    }

    /**
     * @return mixed
     */
    public function getAcknowledgedBy()
    {
        return $this->acknowledged_by;
    }

    /**
     * @param mixed $called_back
     */
    public function setCalledBack($called_back)
    {
        $this->called_back = $called_back;
    }

    /**
     * @return mixed
     */
    public function getCalledBack()
    {
        return $this->called_back;
    }

    /**
     * @param mixed $called_back_at
     */
    public function setCalledBackAt($called_back_at)
    {
        $this->called_back_at = $called_back_at;
    }

    /**
     * @return mixed
     */
    public function getCalledBackAt()
    {
        return $this->called_back_at;
    }

    /**
     * @param mixed $expired
     */
    public function setExpired($expired)
    {
        $this->expired = $expired;
    }

    /**
     * @return mixed
     */
    public function getExpired()
    {
        return $this->expired;
    }

    /**
     * @param mixed $expires_at
     */
    public function setExpiresAt($expires_at)
    {
        $this->expires_at = $expires_at;
    }

    /**
     * @return mixed
     */
    public function getExpiresAt()
    {
        return $this->expires_at;
    }

    /**
     * @param mixed $last_delivered_at
     */
    public function setLastDeliveredAt($last_delivered_at)
    {
        $this->last_delivered_at = $last_delivered_at;
    }

    /**
     * @return mixed
     */
    public function getLastDeliveredAt()
    {
        return $this->last_delivered_at;
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