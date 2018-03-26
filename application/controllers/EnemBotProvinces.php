<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/RestManager.php';
require APPPATH . '/libraries/CrudManagement.php';

class EnemBotProvinces extends RestManager {
    private $className = 'Provinces';
    private $modelName = 'ProvincesModel';

    function __construct () 
    {
        // Construct the parent class
        parent::__construct();
        $this->CrudManagement = new CrudManagement();
    }

    public function superadmin_get()
    {
        $queryString = $this->input->get(); // Query String for filter data :)

        $config = [
            'catIdSegment' => 3,
            'isEditOrDeleteSegment' => 4
        ];

        $dataModel = [
            [
                'className' => $this->className,
                'modelName' => $this->modelName,
                'filter' => '',
                'filterKey' => '',
                'limit' => [
                    'startLimit' => 0,
                    'limitData' => 10000
                ],
                'fieldTarget' => 'name',
                'queryString' => $queryString,
                'dataMaster' => []
            ]
        ];
        
        $data = $this->CrudManagement->run($config, $dataModel);

        return $this->response($data, $flag !== 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_REQUEST);
    }

    public function superadmin_post()
    {
        $name = $this->post('name');
        $status_role = $this->post('status_role');

        $config = [
            'catIdSegment' => 3,
            'isEditOrDeleteSegment' => 4
        ];

        $dataModel = [
            [
                'className' => $this->className,
                'modelName' => $this->modelName,
                'filter' => '',
                'filterKey' => '',
                'limit' => [
                    'startLimit' => 0,
                    'limitData' => 10000
                ],
                'dataMaster' => [
                    'name' => $name,
                    'status_role' => $status_role
                ]
            ]
        ];
        
        $data = $this->CrudManagement->run($config, $dataModel);

        return $this->response($data, $flag !== 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_REQUEST);
    }

    public function superadmin_put()
    {
        $name = $this->put('name');
        $status_role = $this->put('status_role');

        $config = [
            'catIdSegment' => 3,
            'isEditOrDeleteSegment' => 4
        ];

        $dataModel = [
            [
                'className' => $this->className,
                'modelName' => $this->modelName,
                'filter' => '',
                'filterKey' => '',
                'limit' => [
                    'startLimit' => 0,
                    'limitData' => 10000
                ],
                'dataMaster' => [
                    'name' => $name,
                    'status_role' => $status_role
                ]
            ]
        ];
        
        $data = $this->CrudManagement->run($config, $dataModel);

        return $this->response($data, $flag !== 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_REQUEST);
    }

    public function superadmin_delete()
    {
        $config = [
            'catIdSegment' => 3,
            'isEditOrDeleteSegment' => 4
        ];

        $dataModel = [
            [
                'className' => $this->className,
                'modelName' => $this->modelName,
                'fieldName' => 'role_id'
            ]
        ];
        
        $data = $this->CrudManagement->run($config, $dataModel);

        return $this->response($data, $flag !== 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_REQUEST);
    }

    public function index_post() 
    {
        $data = [
            'status' => 'Ok',
            'messages' => 'Hello guys post :)'
        ];
        
        return $this->set_response($data, REST_Controller::HTTP_OK);
    }
}