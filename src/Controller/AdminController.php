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
    use App\Controller\AbstractController;
    use App\Model\AdminModel;
    use App\Model\ArticleModel;
    use App\Model\AuthModel;
    use App\Model\WarehouseModel;
    use App\Settings\AppSettings;
    use App\View\View;    
    
    // Traits
    use App\Traits\UserStatus;
    use App\Traits\UserInput;
    
    
    
    class AdminController extends AbstractController {

        private ArticleModel $articleModel;
        private AuthModel $authModel;
        private WarehouseModel $warehouseModel;

        use UserInput;
        use UserStatus;

        public function __construct() {

            parent::__construct(new AdminModel(), new View());

            $this->authModel = new AuthModel();
            $this->warehouseModel = new WarehouseModel();
            $this->articleModel = new ArticleModel();

            $this->authModel->setController($this);
            $this->warehouseModel->setController($this);
            $this->articleModel->setController($this);

        }

        #[Route('/admin', name: 'admin', methods: ['GET'])]
        public function adminIndex() {
            
            // render template if user is logged in AND is admin
            if ($this->isLoggedIn() && $this->isAdmin()) {
                
                $template = $this->view->load('admin/admin.html');
                echo $template->render([
                    'title' => AppSettings::APP_TITLE . ' - Adminbereich',
                    'userCount' => $this->model->getUserCount(),
                    'latestUser' => $this->model->getLatestUser(),
                    'articleCount' => $this->model->getArticleCount(),
                    'latestArticle' => $this->model->getLatestArticle()
                ]);

            }
            
            // render error in template if user is not logged in or is not admin
            else {

                $this->renderError('admin/admin_not_allowed.html', ['You are not allowed to view this page...']);

            }

        }

        #[Route('/admin/users', name: 'manage_users', methods: ['GET'])]
        public function manageUsers() {

            // [GET]
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {

                // Check if user is logged in AND is admin
                if($this->isLoggedIn() && $this->isAdmin()) {

                    $template = $this->view->load('admin/users.html');
                    echo $template->render([
                        'title' => AppSettings::APP_TITLE . ' - User-Management',
                        'users' => $this->authModel->getAllUsers()
                    ]);

                }

                // Render error template if user is not an admin.
                else {
                    
                    $this->renderError('admin/admin_not_allowed.html', ['You are not allowed to view this page...']);

                }

            }

        }

        #[Route('/admin/users/edit/{id<\d+>}', name: 'edit_users', methods: ['GET', 'POST'])]
        public function editUser(array $params) {

            // check if user is logged in AND is admin
            if($this->isLoggedIn() && $this->isAdmin()) {

                // Get an escaped string from the userID in the URL
                $userID = $this->getCleanString($params['id']);

                // [GET]
                if ($_SERVER['REQUEST_METHOD'] == 'GET') {                    

                    $template = $this->view->load('admin/user_edit.html');
                    echo $template->render([
                        'title' => AppSettings::APP_TITLE . ' - Edit user',
                        'user' => $this->authModel->getUserByUserID($userID),
                        'roles' => $this->authModel->getAllUserRoles()
                    ]);

                }

                // [POST]
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    
                    // Array for error messages
                    $err_msg = [];

                    // Get clean strings from post
                    $username = $this->getCleanString($_POST['username']);
                    $email = $this->getCleanString($_POST['email']);
                    $password = $this->getCleanString($_POST['password']);
                    $passwordRepeat = $this->getCleanString($_POST['password_repeat']);
                    $userRole = $this->getCleanString($_POST['roles']);
                    
                    // Query database for username
                    $res = $this->authModel->getUserByName($username);

                    // Error-handling
                    if (!isset($username) || $username == '') { array_push($err_msg, 'Please enter an username...'); }
                    if (strlen($username) < 4) { array_push($err_msg, 'The username is too short. Please choose a username with min. 4 characters...'); }
                    if ($this->authModel->getUserByName($username) && $username != $res->username) { array_push($err_msg, 'The username is already in use...'); }
                        
                    if (!isset($email) || $email == '') { array_push($err_msg, 'Please enter an email...'); }
                    if ($this->authModel->getUserByEmail($email) && $email != $res->email) { array_push($err_msg, 'The email is already in use...'); }

                    if (isset($password) && $password != '') {

                        if ($password != $passwordRepeat) { array_push($err_msg, 'The passwords you have entered did not match'); }
                        if (strlen($password) < 8) { array_push($err_msg, 'The password you have entered is too short. Pleas enter min. 8 characters'); }

                    }

                    // Render errors into template
                    if (count($err_msg) > 0) {

                        $template = $this->view->load('admin/user_edit.html');
                        echo $template->render([
                            'messages' => $err_msg,
                            'user' => $this->authModel->getUserByUserID($userID),                            
                            'roles' => $this->authModel->getAllUserRoles()
                        ]);
                        die();

                    }
                    
                    // Check if we need an user update with or without new password
                    if ($password == $passwordRepeat && ($password != '' && $passwordRepeat != '')) {

                        // Update User by userID
                        $this->authModel->updateUserByUserID($userID, $username, $email, $userRole, $password);                    
                                                                               
                        // Render Template wirth new data from database
                        $template = $this->view->load('admin/user_edit.html');
                        echo $template->render([
                            'user' => $this->authModel->getUserByUserID($userID),
                            'roles' => $this->authModel->getAllUserRoles()                            
                        ]);

                    }
                    else {
                        
                        // Update User by userID
                        $this->authModel->updateUserByUserID($userID, $username, $email, $userRole);
                        
                        // Reload data for actual user
                        $res = $this->authModel->getUserByUserID($userID);

                        // Render Template wirth new data from database
                        $template = $this->view->load('admin/user_edit.html');
                        echo $template->render([
                            'user' => $res,
                            'roles' => $this->authModel->getAllUserRoles()
                        ]);

                    }

                }
                
            }

            // Render error template if user is not an admin
            else {
                    
                $this->renderError('admin/admin_not_allowed.html', ['You are not allowed to view this page...']);

            }
            
        }

        #[Route('/admin/users/delete/{id<\d+>}', name: 'delete_users', methods: ['GET'])] 
        public function deleteUser(array $params) {

            // Get an escaped string from the URL parameter 'id'
            $userID = $this->getCleanString($params['id']);

            // [GET]
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {

                // Render template
                $template = $this->view->load('admin/user_delete.html');
                echo $template->render([
                    'title' => AppSettings::APP_TITLE . ' - Delete User',
                    'userID' => $userID
                ]);

            }

        }

        #[Route('/admin/users/delete/{id<\d+>}/confirm', name: 'delete_users_confirm', methods: ['GET'])]
        public function deleteUserConfirm(array $params) {

            // check if user is logged in AND is admin
            if($this->isLoggedIn() && $this->isAdmin()) {

                // Get an escaped string from the URL parameter 'id'
                $userID = $this->getCleanString($params['id']);

                if ($_SERVER['REQUEST_METHOD'] == 'GET') {

                    // Check if the user with the userID exist
                    if (!$this->authModel->getUserByUserID($userID)) { $this->renderError('admin/user_delete.html', ['The user you are trying to delete did not exist']); die(); }
                    
                    // Delete user
                    if ($this->authModel->deleteUser($userID)) {

                        header('Location: ' . AppSettings::ROUTER_URI_PREFIX . '/admin/users');
                        die();

                    }
                    
                    // Forward... just for safety :D
                    header('Location: ' . AppSettings::ROUTER_URI_PREFIX);    
                    die();
                }

            }

            // Render error template if user is not an admin
            else {
                    
                $this->renderError('admin/admin_not_allowed.html', ['You are not allowed to view this page...']);

            }

        }
        
        #[Route('/admin/roles', name: 'user_roles', methods: ['GET', 'POST'])]
        public function userRoles() {

            // check if user is logged in AND is admin
            if($this->isLoggedIn() && $this->isAdmin()) {

                // [GET]
                if ($_SERVER['REQUEST_METHOD'] == 'GET') {

                    $template = $this->view->load('admin/roles.html');
                    echo $template->render([
                        'roles' => $this->authModel->getAllUserRoles()
                    ]);

                }

            }

            // Render error template if user is not an admin
            else {
                    
                $this->renderError('admin/admin_not_allowed.html', ['You are not allowed to view this page...']);

            }

        }

        #[Route('/admin/warehouses', name: 'warehouses_index', methods: ['GET', 'POST'])]
        public function warehousesIndex() {

            // check if user is logged in AND is admin
            if($this->isLoggedIn() && $this->isAdmin()) {

                // [GET]
                if ($_SERVER['REQUEST_METHOD'] == 'GET') {

                    $template = $this->view->load('admin/warehouses.html');
                    echo $template->render([
                        'warehouses' => $this->warehouseModel->getAllWarehouses()
                    ]);

                }

                // [POST]
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                    // Array for error messages
                    $err_msg = array();
    
                    // Error-handling
                    if(!isset($_POST['name']) || $_POST['name'] == '') { array_push($err_msg, 'Pleas enter a name for the warehouse.'); }
                    if(!isset($_POST['description']) || $_POST['description'] == '') { array_push($err_msg, 'Pleas enter a description for the warehouse.'); }
                    if(!isset($_POST['maxQuantity']) || $_POST['maxQuantity'] == '') { array_push($err_msg, 'Pleas enter a maximum quantity for the warehouse.'); }
                    if(!is_int($_POST['maxQuantity']) || $_POST['maxQuantity'] < 0) { array_push($err_msg, 'Please enter a valid number.'); }
    
                    // Get clean strings from post array
                    $name = $this->getCleanString($_POST['name']);
                    $description = $this->getCleanString($_POST['description']);
                    $maxQuantity = $this->getCleanString($_POST['maxQuantity']);
    
                    // Render errors into template
                    if (count($err_msg) > 0) {
    
                        $template = $this->view->load('admin/warehouses.html');
                        echo $template->render([
                            'messages' => $err_msg,
                            'warehouses' => $this->warehouseModel->getAllWarehouses()
                        ]);
                        die();
    
                    }
                    
                    // Create a new warehouse
                    $this->warehouseModel->newWarehouse($name, $description, $maxQuantity);
                    
                    // Render template
                    $template = $this->view->load('admin/warehouses.html');
                    echo $template->render([
                        'messages' => $err_msg,
                        'warehouses' => $this->warehouseModel->getAllWarehouses()
                    ]);
    
                }

            }

            // Render error template if user is not an admin
            else {
                    
                $this->renderError('admin/admin_not_allowed.html', ['You are not allowed to view this page...']);

            }

        }

        #[Route('/admin/articles', name: 'articles_admin_index', methods: ['GET', 'POST'])]
        public function articlesIndex() {

            // check if user is logged in AND is admin
            if($this->isLoggedIn() && $this->isAdmin()) {

                // [GET]
                if ($_SERVER['REQUEST_METHOD'] == 'GET') {

                    $template = $this->view->load('admin/articles.html');
                    echo $template->render([
                        'warehouses' => $this->warehouseModel->getAllWarehouses()
                    ]);

                }

                if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                    $err_msg = array();

                    if (!isset($_POST['name']) || $_POST['name'] == '') { array_push($err_msg, 'Please enter a name.'); }
                    if (!isset($_POST['description']) || $_POST['description'] == '') { array_push($err_msg, 'Please enter a description.'); }
                    if (!isset($_POST['quantity']) || $_POST['quantity'] == '') { array_push($err_msg, 'Please enter a quantity.'); }
                    if (!isset($_POST['minQuantity']) || $_POST['minQuantity'] == '') { array_push($err_msg, 'Please enter a minQuantity.'); }
                    if (!isset($_POST['warehouses']) || $_POST['warehouses'] == '') { array_push($err_msg, 'Please enter a warehouse.'); }


                    if (count($err_msg) > 0) {

                        $this->renderError('admin/articles.html', $err_msg);

                    }
                    
                    else {

                        $name = $this->getCleanString($_POST['name']);
                        $description = $this->getCleanString($_POST['description']);
                        $quantity = $this->getCleanString($_POST['quantity']);
                        $minQuantity = $this->getCleanString($_POST['minQuantity']);
                        $warehouseID = $this->getCleanString($_POST['warehouses']);

                        $this->articleModel->newArticle($name, $description, $quantity, $minQuantity, $warehouseID, $_SESSION['userID']);

                        $template = $this->view->load('admin/articles.html');
                        echo $template->render();
                    }

                }
                
            }

        }

    }
