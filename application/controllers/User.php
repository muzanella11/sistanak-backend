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
        $flag = 0;
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
            $dataModel[0]['filterKey'] = $queryString['q'] !== 'null' ? 'WHERE name like "%'.$queryString['q'].'%" or user_role like "%'.$queryString['q'].'%" or nik like "%'.$queryString['q'].'%"' : null;
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

        // Get data ownership detail
        $dataModelRoleDetail = [
            [
                'className' => 'Role',
                'modelName' => 'RoleModel',
                'filter' => 'id',
                'filterKey' => '',
                'limit' => null,
                'fieldTarget' => 'name',
                'dataMaster' => []
            ]
        ];

        foreach ($data['data'] as $key => $value) {
            if ($value->user_role)
            {
                $dataModelRoleDetail[0]['filterKey'] = $value->user_role;
                $dataRoleDetail = $this->CrudManagement->run($config, $dataModelRoleDetail);
                
                $dataMaster = json_encode($data['data'][$key]);
                $dataMasterEncode = json_decode($dataMaster, TRUE);
                $dataMasterEncode['user_role_detail'] = $dataRoleDetail['data'][0];
                $dataMasterResult = $dataMasterEncode;
                $data['data'][$key] = $dataMasterResult;
            }
        }

        foreach ($data['data'] as $key => $value) {
            $dataMaster = json_encode($data['data'][$key]);
            $dataMasterEncode = json_decode($dataMaster, TRUE);
            $data['data'][$key] = $dataMasterEncode;
            $userId = (int) $data['data'][$key]['user_id'];
            $nik = (int) $data['data'][$key]['nik'];
            $role = (int) $data['data'][$key]['user_role'];
            $assignTask = (int) $data['data'][$key]['assign_task'];
            $data['data'][$key]['user_id'] = $userId;
            $data['data'][$key]['user_role'] = $role;
            $data['data'][$key]['assign_task'] = $assignTask;
            $data['data'][$key]['nik'] = $nik;
        }

        return $this->response($data, isset($flag) && $flag !== 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_REQUEST);
    }

    public function index_post()
    {
        $flag = 0;
        $name = $this->post('name');
        $nik = $this->post('nik');
        $username = $this->post('username');
        $password = $this->enem_templates->enem_secret($this->post('password'));
        $email = $this->post('email');
        $phone = $this->post('phone');
        $user_role = $this->post('user_role');
        $address = $this->post('address');

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
                    'address' => $address
                ]
            ]
        ];
        
        $data = $this->CrudManagement->run($config, $dataModel);

        return $this->response($data, isset($flag) && $flag !== 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_REQUEST);
    }

    public function index_put()
    {
        $flag = 0;
        $name = $this->put('name');
        $nik = $this->put('nik');
        $username = $this->put('username');
        $password = $this->enem_templates->enem_secret($this->put('password'));
        $email = $this->put('email');
        $phone = $this->put('phone');
        $user_role = $this->put('user_role');
        $address = $this->put('address');
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
                    'address' => $address,
                    'assign_task' => $assign_task
                ]
            ]
        ];
        
        $data = $this->CrudManagement->run($config, $dataModel);

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
                'className' => 'User',
                'modelName' => 'UserModel',
                'fieldName' => 'user_id'
            ]
        ];
        
        $data = $this->CrudManagement->run($config, $dataModel);

        return $this->response($data, isset($flag) && $flag !== 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_REQUEST);
    }
}