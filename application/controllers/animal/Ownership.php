<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/RestManager.php';
require APPPATH . '/libraries/CrudManagement.php';

class Ownership extends RestManager {
    private $className = 'AnimalOwnership';
    private $modelName = 'AnimalOwnershipModel';

    function __construct () 
    {
        // Construct the parent class
        parent::__construct();
        $this->CrudManagement = new CrudManagement();
    }

    public function index_get()
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

        if (isset($queryString) && count($queryString) > 0) {
            foreach ($queryString as $key => $value) {
                if (!$value)
                {
                    $queryString[$key] = 'null';
                }
            }

            $dataModel[0]['filter'] = 'create_sql';
            $dataModel[0]['filterKey'] = 'fullname like "%'.$queryString['q'].'%" or ownership_id like "%'.$queryString['ownership'].'%"';
            $dataModel[0]['fieldTarget'] = null;
        }
        
        $data = $this->CrudManagement->run($config, $dataModel);

        if ($data['status'] === 'Problem')
        {
            $flag = 1;
        }

        return $this->response($data, $flag !== 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_REQUEST);
    }

    public function index_post()
    {
        $fullname = $this->post('fullname');
        $identity_number = $this->post('identity_number');
        $identity_type = $this->post('identity_type');
        $phone = $this->post('phone');
        $province_id = $this->post('province_id');
        $region_id = $this->post('region_id');
        $village_id = $this->post('village_id');
        $address = $this->post('address');
        $birth_date = $this->post('birth_date');
        $animal_list = $this->post('animal_list');

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
                    'fullname' => $fullname,
                    'identity_number' => $identity_number,
                    'identity_type' => $identity_type,
                    'phone' => $phone,
                    'province_id' => $province_id,
                    'region_id' => $region_id,
                    'village_id' => $village_id,
                    'address' => $address,
                    'birth_date' => $birth_date
                ]
            ]
        ];
        
        $data = $this->CrudManagement->run($config, $dataModel);

        if ($data['status'] === 'Problem')
        {
            $flag = 1;
        }
        else 
        {
            if (count($animal_list) > 0)
            {
                $dataModelDetail = [
                    [
                        'className' => 'AnimalOwnershipDetail',
                        'modelName' => 'AnimalOwnershipDetailModel',
                        'filter' => '',
                        'filterKey' => '',
                        'limit' => [
                            'startLimit' => 0,
                            'limitData' => 10000
                        ],
                        'dataMaster' => []
                    ]
                ];

                foreach ($animal_list as $key => $value) {
                    $dataModelDetail[0]['dataMaster'] = $value;
                }

                $data = $this->CrudManagement->run($config, $dataModelDetail);

                if ($data['status'] === 'Problem')
                {
                    $flag = 1;
                }
            }
        }

        return $this->response($data, $flag !== 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_REQUEST);
    }

    public function index_put()
    {
        $fullname = $this->put('fullname');
        $identity_number = $this->put('identity_number');
        $identity_type = $this->put('identity_type');
        $phone = $this->put('phone');
        $province_id = $this->put('province_id');
        $region_id = $this->put('region_id');
        $village_id = $this->put('village_id');
        $address = $this->put('address');
        $birth_date = $this->put('birth_date');

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
                    'fullname' => $fullname,
                    'identity_number' => $identity_number,
                    'identity_type' => $identity_type,
                    'phone' => $phone,
                    'province_id' => $province_id,
                    'region_id' => $region_id,
                    'village_id' => $village_id,
                    'address' => $address,
                    'birth_date' => $birth_date
                ]
            ]
        ];
        
        $data = $this->CrudManagement->run($config, $dataModel);

        if ($data['status'] === 'Problem')
        {
            $flag = 1;
        }
        else
        {
            $data = $this->CrudManagement->run($config, $dataModel);

            if ($data['status'] === 'Problem')
            {
                $flag = 1;
            }
            else 
            {
                if (count($animal_list) > 0)
                {
                    $dataModelDetail = [
                        [
                            'className' => 'AnimalOwnershipDetail',
                            'modelName' => 'AnimalOwnershipDetailModel',
                            'filter' => '',
                            'filterKey' => '',
                            'limit' => [
                                'startLimit' => 0,
                                'limitData' => 10000
                            ],
                            'dataMaster' => []
                        ]
                    ];

                    foreach ($animal_list as $key => $value) {
                        $dataModelDetail[0]['dataMaster'] = $value;
                    }

                    $data = $this->CrudManagement->run($config, $dataModelDetail);

                    if ($data['status'] === 'Problem')
                    {
                        $flag = 1;
                    }
                }
            }
        }

        return $this->response($data, $flag !== 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_REQUEST);
    }

    public function index_delete()
    {
        $config = [
            'catIdSegment' => 3,
            'isEditOrDeleteSegment' => 4
        ];

        $dataModel = [
            [
                'className' => $this->className,
                'modelName' => $this->modelName,
                'fieldName' => 'ownership_id'
            ]
        ];
        
        $data = $this->CrudManagement->run($config, $dataModel);

        if ($data['status'] === 'Problem')
        {
            $flag = 1;
        }

        return $this->response($data, $flag !== 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_REQUEST);
    }
}