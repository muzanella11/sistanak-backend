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
        $flag = 0;
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
                'fieldTarget' => 'fullname',
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

        $dataModel[0]['filter'] = 0;
        $dataModel[0]['filterKey'] = null;
        $dataModel[0]['limit'] = null;

        $getTotalData = $this->CrudManagement->run($config, $dataModel);

        $data['totalData'] = count($getTotalData['data']);

        // Get data ownership detail
        $dataModelOwnerDetail = [
            [
                'className' => 'AnimalOwnershipDetail',
                'modelName' => 'AnimalOwnershipDetailModel',
                'filter' => 'ownership_id',
                'filterKey' => '',
                'limit' => [
                    'startLimit' => 0,
                    'limitData' => 10000
                ],
                'fieldTarget' => 'name',
                'dataMaster' => []
            ]
        ];

        foreach ($data['data'] as $key => $value) {
            if ($value->ownership_id)
            {
                $dataModelOwnerDetail[0]['filterKey'] = $value->ownership_id;
                $dataOwnerDetail = $this->CrudManagement->run($config, $dataModelOwnerDetail);
                
                $dataMaster = json_encode($data['data'][$key]);
                $dataMasterEncode = json_decode($dataMaster, TRUE);
                $dataMasterEncode['animal_list'] = $dataOwnerDetail['data'];
                $dataMasterResult = $dataMasterEncode;
                $data['data'][$key] = $dataMasterResult;
            }
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
        $latestIdOwnership = $data['latest_created'];

        if ($data['status'] === 'Problem')
        {
            $flag = 1;
        }
        else 
        {
            if (count($animal_list) > 0 && $latestIdOwnership)
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

                // Set ownership_id animal list
                $index = 0;
                foreach ($animal_list as $key => $value) {
                    $animal_list[$index]['ownership_id'] = $latestIdOwnership;
                    $index++;
                }

                // Add animal detail ownership
                foreach ($animal_list as $key => $value) {
                    $dataModelDetail[0]['dataMaster'] = $value;

                    $data = $this->CrudManagement->run($config, $dataModelDetail);

                    if ($data['status'] === 'Problem')
                    {
                        $flag = 1;
                    }
                }

                // Set data ownership
                $data['latest_created'] = $latestIdOwnership;
            }
        }

        return $this->response($data, isset($flag) && $flag !== 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_REQUEST);
    }

    public function index_put()
    {
        $flag = 0;
        $fullname = $this->put('fullname');
        $identity_number = $this->put('identity_number');
        $identity_type = $this->put('identity_type');
        $phone = $this->put('phone');
        $province_id = $this->put('province_id');
        $region_id = $this->put('region_id');
        $village_id = $this->put('village_id');
        $address = $this->put('address');
        $birth_date = $this->put('birth_date');
        $animal_list = $this->put('animal_list');

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

                // Add animal detail ownership
                foreach ($animal_list as $key => $value) {
                    if (!isset($value['ownership_detail_id']))
                    {
                        $config = [
                            'catIdSegment' => 'create',
                            'isEditOrDeleteSegment' => null,
                            'customParam' => true
                        ];
                    }
                    else 
                    {
                        $config = [
                            'catIdSegment' => (int) $value['ownership_detail_id'],
                            'isEditOrDeleteSegment' => 'edit',
                            'customParam' => true
                        ];
                    }

                    $dataModelDetail[0]['dataMaster'] = $value;

                    $data = $this->CrudManagement->run($config, $dataModelDetail);

                    if ($data['status'] === 'Problem')
                    {
                        $flag = 1;
                    }
                }
            }
        }

        return $this->response($data, isset($flag) && $flag !== 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_REQUEST);
    }

    public function index_delete()
    {
        $flag = 0;
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

        return $this->response($data, isset($flag) && $flag !== 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_REQUEST);
    }
}