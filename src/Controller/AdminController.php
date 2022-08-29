<?php

    namespace App\Controller;

    use App\Controller\AbstractController;
    use App\Model\AdminModel;
    use App\View\View;    

    class AdminController extends AbstractController {

        private AdminModel $model;
        private View $view;

        public function __construct() {

            parent::__construct(new AdminModel(), new View());

        }

        

    }