
<?php

use Phalcon\Mvc\Controller;
use Phalcon\Security\JWT\Builder;
use Phalcon\Security\JWT\Signer\Hmac;

class LoginController extends Controller
{
    public function indexAction()
    {
        // login
    }
    public function loginAction()
    {
        $ch = curl_init();
        $url = "http://172.25.0.3/api/login";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $contents = curl_exec($ch);

        $contents = json_decode($contents, true);
        if ($contents != null) {
            $_SESSION['details'] = $contents['name'] . "/" . $contents['email'] . "/" . $contents['password'];
            $signer  = new Hmac();
            $builder = new Builder($signer);
            $passphrase = 'QcMpZ&b&mo3TPsPk668J6QH8JA$&U&m2';
            $builder
                ->setSubject($_SESSION['details'])
                ->setPassphrase($passphrase);
            $tokenObject = $builder->getToken();

            echo 'This is the token for role is ' . PHP_EOL;
            echo $tokenObject->getToken();
            $token = $tokenObject->getToken();

            $this->view->data = $token;
        } else {
            echo "Invalid Credentilas";
            die;
            $this->response->redirect("login");
        }
    }
}
