<?php
class Mail_Komplet_Api_Caller {
    /**
     * Calls a Mailkomplet API method
     *
     * @param string $apiKey - API key, generated in Mailkomplet admin
     * @param string $baseCrypt - Base crypt - from Mailkomplet admin
     * @param string $method - HTTP method name
     * @param string $url - API function name
     * @param array $data - API call params
     * @return mixed - result of curl_exec with given params
     */
    public static function mail_komplet_api_call($apiKey, $baseCrypt, $method, $url, $data = null)
    {
        $apiUrl = 'http://api2.mail-komplet.cz/api/' . $baseCrypt . '/' . $url;
        $args = array(
            'headers' => array(
                'accept' => 'application/json;charset:utf-8',
                'content-type' => 'application/json;charset=UTF-8',
                'x-requested-with' => 'XMLHttpRequest',
                'authorization' => 'Basic ' . $apiKey
            )
        );
        switch ($method)
        {
            case "POST":
                $args['method'] = 'POST';
                if ($data)
                    $args['body'] = json_encode($data);
                    break;
            case "PUT":
                $args['method'] = 'PUT';
                break;
            default:
        }
        
        require_once dirname(dirname(dirname(dirname(dirname( __FILE__ ))))) . '/wp-load.php';
        $response = wp_remote_get($apiUrl, $args);
        $reponse_body = wp_remote_retrieve_body($response);
        return $reponse_body;
    }
}
