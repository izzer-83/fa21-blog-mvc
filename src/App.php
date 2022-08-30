<?php

    /*
    * author: tk
    * name: fa21-blog-mvc
    * date: 29.08.22
    */

    namespace App;
    
    use App\Controller\MainController;
    use App\Controller\AuthController;
    use App\Settings\AppSettings;

    use AttributesRouter\Router;
    use App\Controller\AdminController;
    
    class App {

        public static function run() {

            // Create instance of the Router
            $router = new Router([MainController::class], AppSettings::ROUTER_URI_PREFIX);

            // Add additional routes to the router
            $router->addRoutes([AuthController::class]);
            $router->addRoutes([AdminController::class]);

            // If there is a match, the route will return the class and method associated
            // to the request as well as route parameters
            if ($match = $router->match()) {
                $controller = new $match['class']();
                $controller->{$match['method']}($match['params']);
            }

        }
        
    }

?>