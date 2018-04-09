<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/RestManager.php';
require APPPATH . '/libraries/CrudManagement.php';
require APPPATH . '/libraries/PdfManagement.php';

use Carbon\Carbon;

class Data extends RestManager {
    private $className = 'Environment';
    private $modelName = 'EnvironmentModel';

    function __construct () 
    {
        // Construct the parent class
        parent::__construct();
        $this->CrudManagement = new CrudManagement();
        $this->Carbon = new \Carbon\Carbon();
        $this->pdf = new PdfManagement();
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
            $dataModel[0]['filterKey'] = $queryString['q'] !== 'null' ? 'WHERE address like "%'.$queryString['q'].'%" or land_area like "%'.$queryString['q'].'%"' : null;
            $dataModel[0]['fieldTarget'] = null;
        }
        
        $data = $this->CrudManagement->run($config, $dataModel);

        $getTotalData = $this->CrudManagement->run($config, $dataModel);

        $data['totalData'] = count($getTotalData['data']);

        foreach ($data['data'] as $key => $value) {
            $dataMaster = json_encode($data['data'][$key]);
            $dataMasterEncode = json_decode($dataMaster, TRUE);
            $data['data'][$key] = $dataMasterEncode;
            $provinceId = (int) $data['data'][$key]['province_id'];
            $districtId = (int) $data['data'][$key]['district_id'];
            $villageId = (int) $data['data'][$key]['village_id'];
            $drainase = (int) $data['data'][$key]['drainase'];
            $environmentId = (int) $data['data'][$key]['environment_id'];
            $foodAvail = (int) $data['data'][$key]['food_availability'];
            $fount = (int) $data['data'][$key]['fount'];
            $hygiene = (int) $data['data'][$key]['hygiene'];
            $pollution = (int) $data['data'][$key]['pollution'];
            $landArea = (int) $data['data'][$key]['land_area'];

            $data['data'][$key]['province_id'] = $provinceId;
            $data['data'][$key]['district_id'] = $districtId;
            $data['data'][$key]['village_id'] = $villageId;
            $data['data'][$key]['drainase'] = $drainase;
            $data['data'][$key]['environment_id'] = $environmentId;
            $data['data'][$key]['food_availability'] = $foodAvail;
            $data['data'][$key]['fount'] = $fount;
            $data['data'][$key]['hygiene'] = $hygiene;
            $data['data'][$key]['pollution'] = $pollution;
            $data['data'][$key]['land_area'] = $landArea;
        }

        // Get data province
        $dataModelProvinceDetail = [
            [
                'className' => 'Provinces',
                'modelName' => 'ProvincesModel',
                'filter' => 'id',
                'filterKey' => '',
                'limit' => null,
                'fieldTarget' => 'name',
                'dataMaster' => []
            ]
        ];

        foreach ($data['data'] as $key => $value) {
            if ($value['province_id'])
            {
                $dataModelProvinceDetail[0]['filterKey'] = $value['province_id'];
                $dataProvinceDetail = $this->CrudManagement->run($config, $dataModelProvinceDetail);
                // var_dump($dataProvinceDetail);exit;
                $dataMaster = json_encode($data['data'][$key]);
                $dataMasterEncode = json_decode($dataMaster, TRUE);
                $dataMasterEncode['province_detail'] = count($dataProvinceDetail['data']) > 0 ? $dataProvinceDetail['data'][0] : [];
                $dataMasterResult = $dataMasterEncode;
                $data['data'][$key] = $dataMasterResult;
            }
        }

        // Get data region
        $dataModelRegionDetail = [
            [
                'className' => 'Regencies',
                'modelName' => 'RegenciesModel',
                'filter' => 'id',
                'filterKey' => '',
                'limit' => null,
                'fieldTarget' => 'name',
                'dataMaster' => []
            ]
        ];

        foreach ($data['data'] as $key => $value) {
            if ($value['district_id'])
            {
                $dataModelRegionDetail[0]['filterKey'] = $value['district_id'];
                $dataRegionDetail = $this->CrudManagement->run($config, $dataModelRegionDetail);

                $dataMaster = json_encode($data['data'][$key]);
                $dataMasterEncode = json_decode($dataMaster, TRUE);
                $dataMasterEncode['region_detail'] = count($dataRegionDetail['data']) > 0 ? $dataRegionDetail['data'][0] : [];
                $dataMasterResult = $dataMasterEncode;
                $data['data'][$key] = $dataMasterResult;
            }
        }

        // Get data village
        $dataModelVillageDetail = [
            [
                'className' => 'Villages',
                'modelName' => 'VillagesModel',
                'filter' => 'id',
                'filterKey' => '',
                'limit' => null,
                'fieldTarget' => 'name',
                'dataMaster' => []
            ]
        ];

        foreach ($data['data'] as $key => $value) {
            if ($value['village_id'])
            {
                $dataModelVillageDetail[0]['filterKey'] = $value['village_id'];
                $dataVillageDetail = $this->CrudManagement->run($config, $dataModelVillageDetail);
                
                $dataMaster = json_encode($data['data'][$key]);
                $dataMasterEncode = json_decode($dataMaster, TRUE);
                $dataMasterEncode['village_detail'] = count($dataVillageDetail['data']) > 0 ? $dataVillageDetail['data'][0] : [];
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

        return $this->response($data, isset($flag) && $flag !== 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_REQUEST);
    }

    public function index_put()
    {
        $flag = 0;
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
                'fieldName' => 'environment_id'
            ]
        ];
        
        $data = $this->CrudManagement->run($config, $dataModel);

        if ($data['status'] === 'Problem')
        {
            $flag = 1;
        }

        return $this->response($data, isset($flag) && $flag !== 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_REQUEST);
    }

    public function report_get()
    {
        $dateNow = Carbon::now();
        $dateNow->timezone = new DateTimeZone('Asia/Jakarta');

        $config = [
            'catIdSegment' => 3,
            'isEditOrDeleteSegment' => 4
        ];

        $dataModel = [
            [
                'className' => $this->className,
                'modelName' => $this->modelName,
                'filter' => 'create_sql',
                'filterKey' => '',
                'limit' => '',
                'fieldTarget' => 'name',
                'queryString' => [],
                'dataMaster' => []
            ]
        ];

        $data = $this->CrudManagement->run($config, $dataModel);

        foreach ($data['data'] as $key => $value) {
            $dataMaster = json_encode($data['data'][$key]);
            $dataMasterEncode = json_decode($dataMaster, TRUE);
            $data['data'][$key] = $dataMasterEncode;
            $provinceId = (int) $data['data'][$key]['province_id'];
            $districtId = (int) $data['data'][$key]['district_id'];
            $villageId = (int) $data['data'][$key]['village_id'];
            $drainase = (int) $data['data'][$key]['drainase'];
            $environmentId = (int) $data['data'][$key]['environment_id'];
            $foodAvail = (int) $data['data'][$key]['food_availability'];
            $fount = (int) $data['data'][$key]['fount'];
            $hygiene = (int) $data['data'][$key]['hygiene'];
            $pollution = (int) $data['data'][$key]['pollution'];
            $landArea = (int) $data['data'][$key]['land_area'];

            $data['data'][$key]['province_id'] = $provinceId;
            $data['data'][$key]['district_id'] = $districtId;
            $data['data'][$key]['village_id'] = $villageId;
            $data['data'][$key]['drainase'] = $drainase;
            $data['data'][$key]['environment_id'] = $environmentId;
            $data['data'][$key]['food_availability'] = $foodAvail;
            $data['data'][$key]['fount'] = $fount;
            $data['data'][$key]['hygiene'] = $hygiene;
            $data['data'][$key]['pollution'] = $pollution;
            $data['data'][$key]['land_area'] = $landArea;
        }

        // Get data province
        $dataModelProvinceDetail = [
            [
                'className' => 'Provinces',
                'modelName' => 'ProvincesModel',
                'filter' => 'id',
                'filterKey' => '',
                'limit' => null,
                'fieldTarget' => 'name',
                'dataMaster' => []
            ]
        ];

        foreach ($data['data'] as $key => $value) {
            if ($value['province_id'])
            {
                $dataModelProvinceDetail[0]['filterKey'] = $value['province_id'];
                $dataProvinceDetail = $this->CrudManagement->run($config, $dataModelProvinceDetail);
                // var_dump($dataProvinceDetail);exit;
                $dataMaster = json_encode($data['data'][$key]);
                $dataMasterEncode = json_decode($dataMaster, TRUE);
                $dataMasterEncode['province_detail'] = count($dataProvinceDetail['data']) > 0 ? $dataProvinceDetail['data'][0] : [];
                $dataMasterResult = $dataMasterEncode;
                $data['data'][$key] = $dataMasterResult;
            }
        }

        // Get data region
        $dataModelRegionDetail = [
            [
                'className' => 'Regencies',
                'modelName' => 'RegenciesModel',
                'filter' => 'id',
                'filterKey' => '',
                'limit' => null,
                'fieldTarget' => 'name',
                'dataMaster' => []
            ]
        ];

        foreach ($data['data'] as $key => $value) {
            if ($value['district_id'])
            {
                $dataModelRegionDetail[0]['filterKey'] = $value['district_id'];
                $dataRegionDetail = $this->CrudManagement->run($config, $dataModelRegionDetail);

                $dataMaster = json_encode($data['data'][$key]);
                $dataMasterEncode = json_decode($dataMaster, TRUE);
                $dataMasterEncode['region_detail'] = count($dataRegionDetail['data']) > 0 ? $dataRegionDetail['data'][0] : [];
                $dataMasterResult = $dataMasterEncode;
                $data['data'][$key] = $dataMasterResult;
            }
        }

        // Get data village
        $dataModelVillageDetail = [
            [
                'className' => 'Villages',
                'modelName' => 'VillagesModel',
                'filter' => 'id',
                'filterKey' => '',
                'limit' => null,
                'fieldTarget' => 'name',
                'dataMaster' => []
            ]
        ];

        foreach ($data['data'] as $key => $value) {
            if ($value['village_id'])
            {
                $dataModelVillageDetail[0]['filterKey'] = $value['village_id'];
                $dataVillageDetail = $this->CrudManagement->run($config, $dataModelVillageDetail);
                
                $dataMaster = json_encode($data['data'][$key]);
                $dataMasterEncode = json_decode($dataMaster, TRUE);
                $dataMasterEncode['village_detail'] = count($dataVillageDetail['data']) > 0 ? $dataVillageDetail['data'][0] : [];
                $dataMasterResult = $dataMasterEncode;
                $data['data'][$key] = $dataMasterResult;
            }
        }

        // var_dump($data['data']);exit;

        $dataContentMain = '';
        $dataTable = $data['data'];

        $dataView = [
            'headerConfig' => [
                'instansi' => [
                    'region' => 'Pemerintah Kota Bogor',
                    'name' => 'Dinas Peternakan dan Kesehatan Hewan',
                    'address' => 'Jl. raya atas bawah agak kesamping kanan <br>
                    Telepon: 021-2222 Fax: 022-8888888888999 <br>
                    website: www.dinkes.com'
                ]
            ],
            'titleContent' => 'Laporan Data Kesehatan Lingkungan',
            'dateMail' => 'Bogor, '.$dateNow->format('d F Y'),
            'contentMain' => $dataContentMain,
            'tableName' => 'Environment',
            'contentTable' => $dataTable,
            'footerConfig' => [
                'assign' => [
                    'instansi' => [
                        'name' => 'Kepala Dinas Kesehatan',
                        'region' => 'Kabupaten Bogor'
                    ],
                    'name' => 'Sukonto Legowo',
                    'nik' => '12345678'
                ]
            ]
        ];
        $view = $this->load->view('mails/templates/DataReport', $dataView, true);
        $configPdf = [
            'setFooterPageNumber' => True,
            'title' => 'Laporan Data Kesehatan Lingkungan',
            // 'withBreak' => true,
            'html' => [
                $view                
            ]
        ];
        $this->pdf->run($configPdf);

        $location = $_SERVER['HTTP_HOST'].'/uploads/pdf/'.$configPdf['title'].'.pdf';

        $data = [
            'status' => 'Ok',
            'urlData' => $location,
            'messages' => 'Hello guys :)'
        ];
        
        return $this->set_response($data, REST_Controller::HTTP_OK);
    }
}