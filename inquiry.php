public function inquiry()
    {
        $notificationHeader = getallheaders();
        $notificationBody = file_get_contents('php://input');
        $bodynotif = json_decode($notificationBody, true);
        $vanum = $bodynotif['virtual_account_info']['virtual_account_number'];
        $clientId = 'MCH-xxxx';
        $secretKey = 'SK-xxxx';
        date_default_timezone_set('UTC');
        $timestamp      = date('Y-m-d\TH:i:s\Z');
        $requestid = $notificationHeader['Request-Id'];
        $path = '/inquiry';

        $Body = array(
            'order' =>
            array(
                'invoice_number' => 'INV-xx',
                'amount' => 10000
            ),
            'virtual_account_info' =>
            array(
                'virtual_account_number' => $vanum,
                'info1' => 'Thanks for shooping',
                'info2' => 'at Ashddq',
                'info3' => 'Enjoy!'
            ),
            'virtual_account_inquiry' =>
            array(
                'status' => 'success'
            ),
            'customer' =>
            array(
                'name' => 'Ashddq',
                'email' => 'customer@gmail.com'
            )
        );
        $digest = base64_encode(hash('sha256', json_encode($Body), true));
        $abc = "Client-Id:" . $clientId . "\n" . "Request-Id:" . $requestid . "\n" . "Response-Timestamp:" . $timestamp . "\n" . "Request-Target:" . $path . "\n" . "Digest:" . $digest;
        $signature = base64_encode(hash_hmac('sha256', $abc, $secretKey, true));
        $finalsignature = "HMACSHA256=" . $signature;
        if ($finalSignature == $notificationHeader['Signature']) {
        return $this->response->setJson($Body)->setHeader('Client-Id', $clientId)->setHeader('Request-Id', $requestid)->setHeader('Response-Timestamp', $timestamp)->setHeader('Signature', $finalsignature);
        } else {
            $this->response->setStatusCode(400);
        }
    }
