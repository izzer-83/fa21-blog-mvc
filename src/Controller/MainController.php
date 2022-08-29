<?php

    namespace App\Controller;

    // Project-Imports
    use App\Model\MainModel;
    use App\View\View;

    // Package-Imports
    use AttributesRouter\Attribute\Route;

    class MainController extends AbstractController {

        public function __construct() {
            parent::__construct(new MainModel(), new View());
        }

        // routes
        #[Route('/', name: 'home', methods: ['GET', 'POST'])]
        public function home() {
            
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                
                $template = $this->view->load('index.html');
                echo $template->render([
                    'test' => 'Dies ist ein Test!'
                ]);

            }
            
        }

    }