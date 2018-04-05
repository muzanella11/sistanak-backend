<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/RestManager.php';
require APPPATH . '/libraries/CrudManagement.php';

class Role extends RestManager {
    function __construct () 
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('enem_user_model');
        $this->CrudManagement = new CrudManagement();
    }

    public function index_get()
    {
        $flag = 0;
        $queryString = $this->input->get(); // Query String for filter data :)

        $config = [
            'catIdSegment' => 2,
            'isEditOrDeleteSegment' => 3
        ];

        $dataModel = [
            [
                'className' => 'Role',
                'modelName' => 'RoleModel',
                'filter' => '',
                'filterKey' => '',
                'limit' => [
                    'startLimit' => isset($queryString['offset']) ? $queryString['offset'] : 0,
                    'limitData' => isset($queryString['limit']) ? $queryString['limit'] : 10000
                ],
                'fieldTarget' => 'name',
                'queryString' => $queryString,
                'dataMaster' => []
            ]
        ];

        if (isset($queryString) && count($queryString) > 0) {
            foreach ($queryString as $key => $value) {
                if (!$value)
                {
                    $queryString[$key] = 'null';
                }
            }

            $dataModel[0]['filter'] = 'create_sql';
            $dataModel[0]['filterKey'] = $queryString['q'] !== 'null' || $queryString['role'] !== 'null' ? 'WHERE name like "%'.$queryString['q'].'%" or user_role like "%'.$queryString['role'].'%"' : null;
            $dataModel[0]['fieldTarget'] = null;
        }
        
        $data = $this->CrudManagement->run($config, $dataModel);

        // For pagination
        $dataModel[0]['filter'] = 0;
        $dataModel[0]['filterKey'] = null;
        $dataModel[0]['limit'] = null;

        $getTotalData = $this->CrudManagement->run($config, $dataModel);

        $data['totalData'] = count($getTotalData['data']);
        // End pagination

        foreach ($data['data'] as $key => $value) {
            $dataMaster = json_encode($data['data'][$key]);
            $dataMasterEncode = json_decode($dataMaster, TRUE);
            $data['data'][$key] = $dataMasterEncode;
            $roleId = (int) $data['data'][$key]['role_id'];
            $data['data'][$key]['role_id'] = $roleId;
        }

        if ($data['status'] === 'Problem')
        {
            $flag = 1;
        }

        return $this->response($data, isset($flag) && $flag !== 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_REQUEST);
    }

    public function index_post()
    {
        $flag = 0;
        $name = $this->post('name');
        $description = $this->post('description');

        $config = [
            'catIdSegment' => 2,
            'isEditOrDeleteSegment' => 3
        ];

        $dataModel = [
            [
                'className' => 'Role',
                'modelName' => 'RoleModel',
                'filter' => '',
                'filterKey' => '',
                'limit' => [
                    'startLimit' => 0,
                    'limitData' => 10000
                ],
                'dataMaster' => [
                    'name' => $name,
                    'description' => $description,
                    'status_role' => 0
                ]
            ]
        ];
        
        $data = $this->CrudManagement->run($config, $dataModel);

        if ($data['status'] === 'Problem')
        {
            $flag = 1;
        }

        return $this->response($data, isset($flag) && $flag !== 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_REQUEST);
    }

    public function index_put()
    {
        $flag = 0;
        $name = $this->put('name');
        $description = $this->put('description');

        $config = [
            'catIdSegment' => 2,
            'isEditOrDeleteSegment' => 3
        ];

        $dataModel = [
            [
                'className' => 'Role',
                'modelName' => 'RoleModel',
                'filter' => '',
                'filterKey' => '',
                'limit' => [
                    'startLimit' => 0,
                    'limitData' => 10000
                ],
                'dataMaster' => [
                    'name' => $name,
                    'description' => $description,
                    'status_role' => 0
                ]
            ]
        ];
        
        $data = $this->CrudManagement->run($config, $dataModel);

        if ($data['status'] === 'Problem')
        {
            $flag = 1;
        }

        return $this->response($data, isset($flag) && $flag !== 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_REQUEST);
    }

    public function index_delete()
    {
        $flag = 0;
        $config = [
            'catIdSegment' => 2,
            'isEditOrDeleteSegment' => 3
        ];

        $dataModel = [
            [
                'className' => 'Role',
                'modelName' => 'RoleModel',
                'fieldName' => 'role_id'
            ]
        ];
        
        $data = $this->CrudManagement->run($config, $dataModel);

        if ($data['status'] === 'Problem')
        {
            $flag = 1;
        }

        return $this->response($data, isset($flag) && $flag !== 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_REQUEST);
    }

    public function ping_post() 
    {
        $data = [
            'status' => 'Ok',
            'messages' => 'Hello guys post :)'
        ];
        
        return $this->set_response($data, REST_Controller::HTTP_OK);
    }
}