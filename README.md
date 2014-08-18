php-pushover-sdk
================

php implementation to send messages over pushover.net api

todo
================

- [ ] make nice wiki/documentation
- [ ] add all unit tests
- [ ] add device specific link helper (Pushover/Api/Message/Link)
- [x] add api friendly bulk requests
- [x] get some feedback

examples
================


    $auth = new \Pushover\Api\Authentication\Token('YOUR_PUSHOVER_API_TOKEN');
    $pushover = new \Pushover\Api($auth);

    // test emergency push message
    $message = new \Pushover\Api\Message\EmergencyMessage('FoBar Test Message', 'USER_TOKEN','DEVICE_NAME');
    $message->setTimestamp(strtotime('-1 hour')); // 1 hour before
    $message->setSound($message::SOUND_CASHREGISTER);

    $result = $pushover->push($message);
    if($result !== false)
    {
        /** @var $response \Pushover\Api\Response\Response */
        $response = $pushover->getResponse();

        // get emergency receipt status
        $success = $pushover->getReceiptStatus($response->getReceipt());
        if( $success !== false )
        {
            /** @var $response \Pushover\Api\Response\ReceiptResponse */
            $receiptResponse = $pushover->getResponse();
            echo $receiptResponse->getAcknowledged();
        }
    }

    // bulk push
    $messages = array();
    $messages[] = new \Pushover\Api\Message\NormalMessage('bar', 'USER_TOKEN'); // push message on all user devices
    $messages[] = new \Pushover\Api\Message\NormalMessage('baz', 'USER_TOKEN','DEVICE_NAME'); // push message on special user device

    $results = $pushover->bulkPush($messages);
    if( $result === false )
    {
        // show failed push messages
        print_r($pushover->getErrors());

    } else {

        // show response
        print_R($pushover->getResponseSet());
    }
