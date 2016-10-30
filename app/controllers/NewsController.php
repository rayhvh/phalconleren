<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class NewsController extends ControllerBase
{

//    Show the index of news
    public function IndexAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'News', $_POST);
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $news = News::find("status = '0'");

        $paginator = new Paginator([
            'data' => $news,
            'limit'=> 10,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }

//    If you are logged in, only execute the following (create new news page)

    public function NewAction()
    {
        $this->handleSecurity('admin');
    }

    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "news",
                'action' => 'index'
            ]);

            return;
        }

        $userid = $this->session->get("user_id");

        $news = new News();
        $news->users_id = $userid;
        $news->name = $this->request->getPost("name");
        $news->message = $this->request->getPost("message");
        $news->status = 0;


        if (!$news->save()) {
            foreach ($news->getMessages() as $error) {
                $this->flash->error($error);
            }

            $this->dispatcher->forward([
                'controller' => "news",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("news was created successfully");

        $this->dispatcher->forward([
            'controller' => "news",
            'action' => 'index'
        ]);

        $this->view->disable();    // omdat we geen view willen aanmaken die daadwerkelijk post.
        $this->response->redirect(news);
    }






    public function manageAction()
    {
        $this->handleSecurity('admin');

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'News', $_POST);
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }


//        Query below
        $filter  = $this->request->getPost("filter");

        if(!isset($filter) || ($filter == 'All')) {
            $news = News::find();
        } else
        {
            $news = News::find("status = '$filter'");
        }

        //Till here

        if (count($news) == 0) {
            $this->flash->notice("There are no results for this filter!");

            $this->dispatcher->forward([
                "controller" => "news",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $news,
            'limit'=> 10,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }






    public function deleteAction($id)
    {
        $news = News::findFirstByid($id);
        if($news->status == 0) {

            $this->flash->error("This needs to be archived first!");

            $this->dispatcher->forward([
                'controller' => "news",
                'action' => 'manage'
            ]);

            return;
        }

        if (!$news->delete())
        {
            foreach ($news->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "news",
                'action' => 'manage'
            ]);

            return;
        }

        $this->flash->success("News article '$news->name' is deleted!");


        $this->dispatcher->forward([
            'controller' => "news",
            'action' => "manage"
        ]);

    }





    public function ArchiveAction()
    {
        $id = $this->request->getPost("id");

        $news = News::findFirstByid($id);

        if($news->status == 0) {
            $news->status = 1;
            $this->flash->notice("News article '$news->name' is archived");
        } else {
            $news->status = 0;
            $this->flash->success("News article '$news->name' is published!");

        }


        if (!$news->save())
        {
            foreach ($news->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "news",
                'action' => 'manage'
            ]);

            return;
        }

        $this->dispatcher->forward([
            'controller' => "news",
            'action' => "manage"
        ]);

    }

}

