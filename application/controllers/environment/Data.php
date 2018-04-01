<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/RestManager.php';
require APPPATH . '/libraries/CrudManagement.php';

class Data extends RestManager {
    private $className = 'Environment';
    private $modelName = 'EnvironmentModel';

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
            $dataModel[0]['filterKey'] = 'address like "%'.$queryString['q'].'%" or environment_id like "%'.$queryString['environment'].'%" or province_id like "%'.$queryString['province'].'%" or district_id like "%'.$queryString['district'].'%" or village_id like "%'.$queryString['village'].'%"';
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
        $province_id = (int) $this->post('province_id');
        $district_id = (int) $this->post('district_id');
        $village_id = (int) $this->post('village_id');
        $address = $this->post('address');
        $drainase = (int) $this->post('drainase');
        $hygiene = (int) $this->post('hygiene');
        $fount = (int) $this->post('fount');
        $pollution = (int) $this->post('pollution');
        $food_availability = (int) $this->post('food_availability');
        $land_area = (int) $this->post('land_area');

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
                    'province_id' => $province_id,
                    'district_id' => $district_id,
                    'village_id' => $village_id,
                    'address' => $address,
                    'drainase' => $drainase,
                    'hygiene' => $hygiene,
                    'fount' => $fount,
                    'pollution' => $pollution,
                    'food_availability' => $food_availability,
                    'land_area' => $land_area
                ]
            ]
        ];
        
        $data = $this->CrudManagement->run($config, $dataModel);

        if ($data['status'] === 'Problem')
        {
            $flag = 1;
        }

        return $this->response($data, $flag !== 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_REQUEST);
    }

    public function index_put()
    {
        $province_id = (int) $this->put('province_id');
        $district_id = (int) $this->put('district_id');
        $village_id = (int) $this->put('village_id');
        $address = $this->put('address');
        $drainase = (int) $this->put('drainase');
        $hygiene = (int) $this->put('hygiene');
        $fount = (int) $this->put('fount');
        $pollution = (int) $this->put('pollution');
        $food_availability = (int) $this->put('food_availability');
        $land_area = (int) $this->put('land_area');

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
                    'province_id' => $province_id,
                    'district_id' => $district_id,
                    'village_id' => $village_id,
                    'address' => $address,
                    'drainase' => $drainase,
                    'hygiene' => $hygiene,
                    'fount' => $fount,
                    'pollution' => $pollution,
                    'food_availability' => $food_availability,
                    'land_area' => $land_area
                ]
            ]
        ];
        
        $data = $this->CrudManagement->run($config, $dataModel);

        if ($data['status'] === 'Problem')
        {
            $flag = 1;
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
                'fieldName' => 'environment_id'
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