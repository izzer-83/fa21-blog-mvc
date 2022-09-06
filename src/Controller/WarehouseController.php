<?php
    
    namespace App\Controller;
    
    use App\Model\WarehouseModel;
    use App\View\View;
    use AttributesRouter\Attribute\Route;
    

    class WarehouseController extends AbstractController {

        public function __construct() {

            parent::__construct(new WarehouseModel(), new View());

            $this->model->setController($this);

        }

        #[Route('/warehouses', name: 'warehouse_index', methods: ['GET', 'POST'])] 
        public function warehouse_index() {

            // [GET]
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                
                $template = $this->view->load('warehouse/warehouses.html');
                echo $template->render([
                    'warehouses' => $this->model->getAllWarehouses(),
                    'articles' => $this->model->getWarehouseOverviewData()
                ]);

            }
            
        }

    }