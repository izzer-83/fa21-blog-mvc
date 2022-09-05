<?php

    /*
    * author: tk
    * name: fa21-blog-mvc
    * date: 29.08.22
    */

    namespace App;
    
    // Project-Imports
    use App\Controller\AuthController;
    use App\Controller\AdminController;
    use App\Controller\ArticleController;
    use App\Controller\MainController;
    use App\Controller\WarehouseController;
    use App\Settings\AppSettings;

    // Package-Imports
    use AttributesRouter\Router;
    
    class App {

        public static function run() {

            // Create instance of the Router
            $router = new Router([MainController::class], AppSettings::ROUTER_URI_PREFIX);

            // Add additional controller to the router
            $router->addRoutes([AuthController::class]);
            $router->addRoutes([AdminController::class]);
            $router->addRoutes([WarehouseController::class]);
            $router->addRoutes([ArticleController::class]);

            // If there is a match, the route will return the class and method associated
            // to the request as well as route parameters
            if ($match = $router->match()) {
                $controller = new $match['class']();
                $controller->{$match['method']}($match['params']);
            }

        }
        
    }

?>