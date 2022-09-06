<?php

    namespace App\Controller;

    use App\Model\ArticleModel;
    use App\View\View;
    
    use AttributesRouter\Attribute\Route;

    class ArticleController extends AbstractController {

        public function __construct() {

            parent::__construct(new ArticleModel(), new View());

            $this->model->setController($this);

        }

        #[Route('/articles', name: 'articles_index', methods: ['GET'])]
        public function articleIndex() {

            // [GET]
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {

                // Render template
                $template = $this->view->load('articles/articles.html');
                echo $template->render([
                    'articles' => $this->model->getAllArticle()
                ]);

            }

        }
        
    }