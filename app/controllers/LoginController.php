<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Select;

class LoginController extends ControllerBase {

    public function indexAction()
    {
        if ($this->session->has("username"))
        {
            $this->dispatcher->forward([
                'controller' => "index",
                'action' => "index"
            ]);
        }
    }


    public function loginAction()
    {

        $username = $this->request->getPost("username");
        $password = $this->request->getPost("password");

        $user = Users::findFirstByusername($username);
        if ($user) {
                if ($password == $user->password) {
//            if ($this->security->checkHash($password, $user->password)) {
                echo "password klopt";
                $this->flash->success("Success!");

                    $this->session->set("username", "$username");
                    $this->session->set("rank", "$user->rank");
                    $this->session->set("user_id", "$user->id");


                    $this->dispatcher->forward([
                    'controller' => "index",
                    'action' => "index"
                ]);

                // The password is valid
            }
            else {
                echo "password incorrect oid";
            }

        } else {
            // To protect against timing attacks. Regardless of whether a user exists or not, the script will take roughly the same amount as it will always be computing a hash.
            $this->security->hash(rand());
            $this->flash->error("Error!");

            $this->dispatcher->forward([
                'controller' => "login",
                'action' => "index"
            ]);
        }
        // The validation has failed
    }

    public function logoutAction() {
        $this->session->destroy();
        $this->response->redirect('');
    }
}