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
    use App\Model\AuthModel;
    use App\View\View;
    
    // Traits
    use App\Traits\Encryption;
    use App\Traits\UserInput;
    
    class AuthController extends AbstractController {

        use Encryption;
        use UserInput;        

        public function __construct() {
            
            parent::__construct(new AuthModel(), new View());

            $this->model->setController($this);
            
        }

        // routes
        #[Route('/login', name: 'login', methods: ['GET', 'POST'])]
        public function login() {
            
            // [GET]
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                
                $template = $this->view->load('auth/login.html');
                echo $template->render([
                    'title' => AppSettings::APP_TITLE . ' - Login',
                ]);

            }

            // [POST]
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                
                $err_msg = [];

                $username = $this->getCleanString($_POST['username']);
                $password = $this->getCleanString($_POST['password']);
                
                // handle input errors
                if (!isset($_POST['username']) || $_POST['username'] == '') { $err_msg[1] = 'Please enter an email adress!'; }
                if (!isset($_POST['password']) || $_POST['password'] == '') { $err_msg[2] = 'Please enter a password!'; }
                
                $res = $this->model->getUserByName($username);

                // render error if user did not exist
                if (!$res) { $err_msg[0] = 'Please enter a valid username/e-mail combination'; }

                if ($res) {

                    // render error if the passwords did not compare
                    if (!$this->compare($password, $res->password)) { $err_msg[0] = 'Please enter a valid username/e-mail combination'; }
                    
                    if ($this->compare($password, $res->password)) {

                        $_SESSION['sid'] = session_id();
                        $_SESSION['publicID'] = $res->publicID;
                        $_SESSION['username'] = $res->username;
                        $_SESSION['isLoggedIn'] = true;

                        if ($res->roleID == 1) {
                            $_SESSION['isAdmin'] = true;
                        }
                        else {
                            $_SESSION['isAdmin'] = false;
                        }

                        if ($res->roleID == 2) {
                            $_SESSION['isManager'] = true;
                        }
                        else {
                            $_SESSION['isManager'] = false;
                        }

                        header('Location: ./');
                    }

                }

                // render error if there is an input error
                if (count($err_msg) > 0) {

                    if (isset($err_msg[1]) || isset($err_msg[2])) {

                        $err_msg[0] = '';                        

                    }

                    ksort($err_msg);

                    $error_temp = $this->view->load('auth/login.html');
                    echo $error_temp->render([
                        'messages' => $err_msg
                    ]);
                    
                }

            }
            
        }

        #[Route('/register', name: 'register', methods: ['GET','POST'])]
        public function register() {

            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                
                $template = $this->view->load('auth/register.html');
                echo $template->render([
                    'title' => AppSettings::APP_TITLE . ' - Login',
                ]);

            }

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                $err_msg = [];

                // Clean user-inputs
                
                $username = $this->getCleanString($_POST['username']);
                $password = $this->getCleanString($_POST['password']);
                $password_repeat = $this->getCleanString($_POST['password_repeat']);
                $email = $this->getCleanString($_POST['email']);

                // Check if all user-inputs are set and not empty
                if (!isset($_POST['username']) || $_POST['username'] == '')                 { $err_msg[4] = 'Please enter an username!'; }
                if (!isset($_POST['password']) || $_POST['password'] == '')                 { $err_msg[5] = 'Please enter a password!'; }
                if (!isset($_POST['password_repeat']) || $_POST['password_repeat'] == '')   { $err_msg[6] = 'Please re-enter your password!'; }
                if (!isset($_POST['email']) || $_POST['email'] == '')                       { $err_msg[7] = 'Please enter an e-mail!'; }

                // Check if user or email-adress already exist
                if ($this->model->getUserByName($username))                                 { $err_msg[0] = 'The username is already in use...'; }
                if ($this->model->getUserByEmail($email))                                   { $err_msg[1] = 'The email is already in use...'; }
                
                // Check if the password has a min. length of 8 chars.
                if (strlen($password) < 8 )                                                 { $err_msg[2] = 'The password is to short. Please select a password with min. 8 characters.'; }

                // Compare password and password repeat.
                if ($password != $password_repeat)                                          { $err_msg[3] = 'The passwords did not match...'; }

                // When an error message is in the $err_msg array, then render the error
                if (count($err_msg) > 0) {

                    ksort($err_msg);

                    $error_temp = $this->view->load('auth/register.html');
                    echo $error_temp->render([
                        'messages' => $err_msg
                    ]);
                    die();

                }

                // Create new user
                $newUser = $this->model->newUser($username, $password, $email);

                // Render template
                if ($newUser) {

                    $template = $this->view->load('auth/register.html');
                    echo $template->render(['messages' => 'New user ' . $username . ' successful created.']);
                    sleep(3);
                    #header('Location: ./');

                }

            }

        }

        #[Route('/logout', name: 'logout', methods: ['GET'])]
        public function logout() {

            $_SESSION['sid'] = null;
            $_SESSION['publicID'] = null;
            $_SESSION['username'] = null;
            $_SESSION['isLoggedIn'] = false;
            $_SESSION['isAdmin'] = null;

            header('Location: ./');

        }

    }
?>