<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class CharactersController extends ControllerBase
{

//    Show the index of characters
    public function IndexAction()
    {
        $userid = $this->session->get("user_id");

        if (!isset($userid))
       {
               $this->dispatcher->forward([
                   "controller" => "index",
                   "action" => "index"
               ]);

           $this->flash->error("You do not have access to this page");

           return;
       }

        $characters = Characters::find("users_id = '" . $userid . "'");

//        $filter  = $this->request->getPost("filter");
//        $filter = 'druid';
//
//        $characters = Characters::find(array
//        (
//            "class = '$filter'",
//            "users_id = '" . $userid . "'",
////            'order'=> 'name ASC'
////            'order'=> $filter
//        ));


//        var_dump($characters);
        $this->view->setVar('characters', $characters);

        if (count($characters) == 0) {
            $this->flash->notice("You do not have any characters");

            $this->dispatcher->forward([
                "controller" => "characters",
                "action" => "new"
            ]);

            return;
        }
    }

    public function NewAction()
    {
        $userid = $this->session->get("user_id");

        if (!isset($userid))
        {
            $this->dispatcher->forward([
                "controller" => "index",
                "action" => "index"
            ]);

            $this->flash->error("You do not have access to this page");

            return;
        }
    }

    public function ConfirmAction()
    {
        $userid = $this->session->get("user_id");

        if (!isset($userid))
        {
            $this->dispatcher->forward([
                "controller" => "index",
                "action" => "index"
            ]);

            $this->flash->error("You do not have access to this page");

            return;
        }

        $name  = $this->request->getPost("name");
        $class = $this->request->getPost("class");
        $role = $this->request->getPost("role");

        $this->view->setVars(
            [
                "name" => $name,
                "class"    => $class,
                "role" => $role,
            ]
        );

    }

    public function CreateAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "characters",
                'action' => 'index'
            ]);

            return;
        }

        $userid = $this->session->get("user_id");

        $characters = new Characters();
        $characters->name = $this->request->getPost("name");  // <---- must be deleted when you can log in!
        $characters->class = $this->request->getPost("class");
        $characters->spec = $this->request->getPost("role");
        $characters->users_id = $userid;
        $characters->main= 0;


        if (!$characters->save()) {
            foreach ($characters->getMessages() as $error) {
                $this->flash->error($error);
            }

            $this->dispatcher->forward([
                'controller' => "characters",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("character was created successfully");

        $this->response->redirect('characters');

    }

    public function MainAction() {
        $userid = $this->session->get("user_id");
        $characters = Characters::find("users_id = '" . $userid . "'");

        $chars = count($characters);

        if($chars < 5) {
            $this->response->redirect('characters');
            return;
        }

        $this->view->setVar('characters', $characters);


        $main = Characters::find(array
        (
            "main = '1'",
            "users_id = '" . $userid . "'",
        ));

        $this->view->setVar('main', $main);


    }



    public function setmainAction() {
        $userid = $this->session->get("user_id");
        $name = $this->request->getPost("name");

        $characters = Characters::findFirst(array
        (
            "name = '$name'",
            "users_id = '" . $userid . "'",
        ));

        if($characters->name === $name && ($characters->main == 0) ) {
            $characters->main = 1;
        } else {
            $this->flash->error("Your character does not belong to you, does not exist or is already your main");
        }

        if (!$characters->save())
        {
            foreach ($characters->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "characters",
                'action' => 'main'
            ]);

            return;
        }

        $this->dispatcher->forward([
            'controller' => "characters",
            'action' => "main"
        ]);
    }
}