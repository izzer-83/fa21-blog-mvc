<?php

    /*
    * author: tk
    * name: fa21-blog-mvc
    * date: 29.08.22
    */

    namespace App\Controller;

    // Package-Imports
    use AttributesRouter\Attribute\Route;

    // Project-Imports
    use App\Settings\AppSettings;

    use App\Controller\AbstractController;
    use App\Model\AdminModel;
    use App\Model\AuthModel;
    use App\View\View;    
    
    use App\Traits\UserStatus;
    use App\Traits\UserInput;
    
    class AdminController extends AbstractController {

        use UserInput;
        use UserStatus;

        public function __construct() {

            parent::__construct(new AdminModel(), new View());

        }

        #[Route('/admin', name: 'admin', methods: ['GET'])]
        public function admin_index() {
            
            // render template if user is logged in AND is admin
            if ($this->isLoggedIn() && $this->isAdmin()) {
                
                $template = $this->view->load('admin/admin.html');
                echo $template->render([
                    'title' => AppSettings::APP_TITLE . ' - Adminbereich'
                ]);

            }
            
            // render error in template if user is not logged in or is not admin
            else {

                $this->renderError('admin/admin_not_allowed.html', ['You are not allowed to view this page...']);

            }

        }

        #[Route('/admin/users', name: 'manage_users', methods: ['GET', 'POST'])]
        public function manage_users() {

            // [GET]
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {

                // check if user is logged in AND is admin
                if($this->isLoggedIn() && $this->isAdmin()) {

                    $template = $this->view->load('admin/users.html');
                    echo $template->render([
                        'title' => AppSettings::APP_TITLE . ' - User-Management',
                        'users' => $this->model->getAllUsers()
                    ]);

                }

                else {
                    
                    $this->renderError('admin/admin_not_allowed.html', ['You are not allowed to view this page...']);

                }

            }

            // [POST]
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            }

        }

        #[Route('/admin/users/edit/{id<\d+>}', name: 'edit_users', methods: ['GET', 'POST'])]
        public function edit_user(array $params) {

            // Get an escaped string from the userID in the URL
            $userID = $this->getCleanString($params['id']);

            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                
                $res = $this->model->getUserByUserID($userID);

                $template = $this->view->load('admin/user_edit.html');
                echo $template->render([
                    'title' => AppSettings::APP_TITLE . ' - Edit user [ ' . $res->username . ' ]',
                    'user' => $res
                ]);

            }

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                
                $err_msg = [];

                $username = $this->getCleanString($_POST['username']);
                $email = $this->getCleanString($_POST['email']);
                $password = $this->getCleanString($_POST['password']);
                $passwordRepeat = $this->getCleanString($_POST['password_repeat']);
                $isAdmin = false;

                $userExist = true;
                $emailExist = true;

                if (isset($_POST['isAdmin']) && $_POST['isAdmin'] == 'on') { $isAdmin = true; }               

                $res = $this->model->getUserByUserID($userID);

                if($username == $res->username) { $userExist = false; }
                if($email == $res->email) { $emailExist = false; }
                
                if (!isset($username) || $username == '') { array_push($err_msg, 'Please enter an username...'); }
                if (strlen($username) <= 4) { array_push($err_msg, 'The username is too short. Please choose a username with min. 4 characters...'); }
                if ($this->model->getUserByName($username) && $username != $res->username) { array_push($err_msg, 'The username is already in use...'); }
                    
                if (!isset($email) || $email == '') { array_push($err_msg, 'Please enter an email...'); }
                if ($this->model->getUserByEmail($email) && $email != $res->email) { array_push($err_msg, 'The email is already in use...'); }

                if (isset($password) && $password != '') {

                    if ($password != $passwordRepeat) { array_push($err_msg, 'The passwords you have entered did not match'); }
                    if (strlen($password) <= 8) { array_push($err_msg, 'The password you have entered is too short. Pleas enter min. 8 characters'); }

                }

                // Render errors into template
                if (count($err_msg) > 0) {

                    $template = $this->view->load('admin/user_edit.html');
                    echo $template->render([
                        'user' => $res,
                        'messages' => $err_msg
                    ]);
                    die();

                }
                
                // Check if we need an user update with or without new password
                if ($password == $passwordRepeat && ($password != '' && $passwordRepeat != '')) {

                    // Update User by userID
                    $this->model->updateUserByUserID($userID, $username, $email, $isAdmin, $password);                    
                    
                    // Reload data for actual user
                    $res = $this->model->getUserByUserID($userID);
                    
                    // Render Template wirth new data from database
                    $template = $this->view->load('admin/user_edit.html');
                    echo $template->render([
                        'user' => $res
                    ]);


                }
                else {

                    // Update User by userID
                    $this->model->updateUserByUserID($userID, $username, $email, $isAdmin);
                    
                    // Reload data for actual user
                    $res = $this->model->getUserByUserID($userID);

                    // Render Template wirth new data from database
                    $template = $this->view->load('admin/user_edit.html');
                    echo $template->render([
                        'user' => $res
                    ]);

                }

            }
            
        }

    }