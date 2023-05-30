
<?php

use Phalcon\Mvc\Controller;
use Phalcon\Security\JWT\Builder;
use Phalcon\Security\JWT\Signer\Hmac;
use Phalcon\Acl\Adapter\Memory;
use Phalcon\Security\JWT\Token\Parser;

class AuthorizeController extends Controller
{
    public function indexAction()
    {

        $tokenReceived = $_GET['token'];
        $now           = new DateTimeImmutable();
        $id            = 'abcd123456789';

        $signer     = new Hmac();
        $passphrase = 'QcMpZ&b&mo3TPsPk668J6QH8JA$&U&m2';

        $parser      = new Parser();

        $tokenObject = $parser->parse($tokenReceived);

        $sub = $tokenObject->getClaims()->getPayload()['sub'];

        $sub = explode("/", $sub);

        $ch = curl_init();
        $url = "http://172.25.0.3/api/authorize";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $sub);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $contents = curl_exec($ch);


        if ($contents !== null) {
            echo "Successfull authorization";
            die;
        } else {
            echo "Not Successfull authorization";
            die;
        }
    }
}
