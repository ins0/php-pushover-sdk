php-pushover-sdk
================

php implementation to send messages over pushover.net api

ToDo
================
- [ ] make extended examples
- [ ] make tests
- [ ] make nice wiki/documentation
- [x] wait for feedback

Examples
================


    $auth = new \Pushover\Api\Authentication\Token('YOUR_PUSHOVER_API_TOKEN');
    $pushover = new \Pushover\Api($auth);

    // test emergency push message
    $message = new \Pushover\Api\Message\EmergencyMessage('FoBar Test Message', 'USER_TOKEN','DEVICE_NAME');
    $message->setTimestamp(time()-3600); // 1 hour before
    $message->setSound($message::SOUND_CASHREGISTER);

    $success = $pushover->push($message);
    if($success === true)
    {
        /** @var $response \Pushover\Api\Response\Response */
        $response = $pushover->getResponse();
        
        // get receipt status
        if( $pushover->getReceiptStatus( $response->getReceipt() ))
        {
            /** @var $response \Pushover\Api\Response\ReceiptResponse */
            $response = $pushover->getResponse();
            echo $response->getAcknowledged();
        }
    }

    // bulk push
    $messages = array();
    $messages[] = new \Pushover\Api\Message\NormalMessage('bar', 'USER_TOKEN'); // push message on all user devices
    $messages[] = new \Pushover\Api\Message\NormalMessage('baz', 'USER_TOKEN','DEVICE_NAME'); // push message on special user device

    $repsonse = $pushover->bulkPush($messages);
    if( $repsonse !== true )
    {
        // show failed push
        print_r($response);
    }
