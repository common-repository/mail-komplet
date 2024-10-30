<?php
$res = '';
if (isset($_GET['apiKey']) && !empty($_GET['apiKey']) && isset($_GET['baseCrypt']) && !empty($_GET['baseCrypt'])) {
    /**
     * The class containing Mail Komplet API call function
     */
    require_once dirname( __FILE__ ) . '/includes/class-mail-komplet-api-caller.php';
    
    $res = Mail_Komplet_Api_Caller::mail_komplet_api_call($_GET['apiKey'], $_GET['baseCrypt'], 'GET', 'mailingLists');
}
echo $res;