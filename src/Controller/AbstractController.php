<?php

    /*
    * author: tk
    * name: fa21-blog-mvc
    * date: 29.08.22
    */

    namespace App\Controller;
    use App\Settings\AppSettings;
    use App\Model\AbstractModel;
    use App\View\View;

    abstract class AbstractController {

        protected AbstractModel $model;
        protected View $view;

        public function __construct(AbstractModel $model, View $view) {

            $this->model = $model;
            $this->view = $view;

        }

        public function renderError(string $template, array $msg) {

            $error_temp = $this->view->load($template);
            echo $error_temp->render([
                'title' => AppSettings::APP_TITLE . ' - Error',
                'messages' => $msg
            ]);

        }

    }