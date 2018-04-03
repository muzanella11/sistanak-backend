<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/RestManager.php';
require APPPATH . '/libraries/CrudManagement.php';

class User extends RestManager {
    function __construct () 
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('enem_user_model');
        $this->CrudManagement = new CrudManagement();
    }

    public function index_get()
    {
        $queryString = $this->input->get(); // Query String for filter data :)

        $config = [
            'catIdSegment' => 2,
            'isEditOrDeleteSegment' => 3
        ];

        $dataModel = [
            [
                'className' => 'User',
                'modelName' => 'UserModel',
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

        if (isset($queryString) && count($queryString) > 0) {
            foreach ($queryString as $key => $value) {
                if (!$value)
                {
                    $queryString[$key] = 'null';
                }
            }

            $dataModel[0]['filter'] = 'create_sql';
            $dataModel[0]['filterKey'] = 'name like "%'.$queryString['q'].'%" or user_role like "%'.$queryString['status_role'].'%"';
            $dataModel[0]['fieldTarget'] = null;
        }
        
        $data = $this->CrudManagement->run($config, $dataModel);

        return $this->response($data, isset($flag) && $flag !== 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_REQUEST);
    }

    public function index_post()
    {
        $name = $this->post('name');
        $nik = $this->post('nik');
        $username = $this->post('username');
        $password = $this->enem_templates->enem_secret($this->post('password'));
        $email = $this->post('email');
        $phone = $this->post('phone');
        $user_role = $this->post('user_role');

        $config = [
            'catIdSegment' => 2,
            'isEditOrDeleteSegment' => 3
        ];

        $dataModel = [
            [
                'className' => 'User',
                'modelName' => 'UserModel',
                'filter' => '',
                'filterKey' => '',
                'limit' => [
                    'startLimit' => 0,
                    'limitData' => 10000
                ],
                'dataMaster' => [
                    'name' => $name,
                    'nik' => $nik,
                    'username' => $username,
                    'password' => $password,
                    'email' => $email,
                    'phone' => $phone,
                    'user_role' => $user_role
                ]
            ]
        ];
        
        $data = $this->CrudManagement->run($config, $dataModel);

        return $this->response($data, isset($flag) && $flag !== 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_REQUEST);
    }

    public function index_put()
    {
        $name = $this->put('name');
        $nik = $this->put('nik');
        $username = $this->put('username');
        $password = $this->enem_templates->enem_secret($this->put('password'));
        $email = $this->put('email');
        $phone = $this->put('phone');
        $user_role = $this->put('user_role');
        $assign_task = $this->put('assign_task');

        $config = [
            'catIdSegment' => 2,
            'isEditOrDeleteSegment' => 3
        ];

        $dataModel = [
            [
                'className' => 'User',
                'modelName' => 'UserModel',
                'filter' => '',
                'filterKey' => '',
                'limit' => [
                    'startLimit' => 0,
                    'limitData' => 10000
                ],
                'dataMaster' => [
                    'name' => $name,
                    'nik' => $nik,
                    'username' => $username,
                    'password' => $password,
                    'email' => $email,
                    'phone' => $phone,
                    'user_role' => $user_role,
                    'assign_task' => $assign_task
                ]
            ]
        ];
        
        $data = $this->CrudManagement->run($config, $dataModel);

        return $this->response($data, isset($flag) && $flag !== 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_REQUEST);
    }

    public function index_delete()
    {
        $config = [
            'catIdSegment' => 2,
            'isEditOrDeleteSegment' => 3
        ];

        $dataModel = [
            [
                'className' => 'User',
                'modelName' => 'UserModel',
                'fieldName' => 'user_id'
            ]
        ];
        
        $data = $this->CrudManagement->run($config, $dataModel);

        return $this->response($data, isset($flag) && $flag !== 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_REQUEST);
    }
}