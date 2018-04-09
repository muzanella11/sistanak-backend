<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/RestManager.php';
require APPPATH . '/libraries/CrudManagement.php';
require APPPATH . '/libraries/PdfManagement.php';

use Carbon\Carbon;

class Ownership extends RestManager {
    private $className = 'AnimalOwnership';
    private $modelName = 'AnimalOwnershipModel';

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
                    'startLimit' => isset($queryString['offset']) ? $queryString['offset'] : 0,
                    'limitData' => isset($queryString['limit']) ? $queryString['limit'] : 10000
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
            $dataModel[0]['filterKey'] = $queryString['q'] !== 'null' ? 'WHERE fullname like "%'.$queryString['q'].'%" or ownership_id like "%'.$queryString['q'].'%"' : null;
            $dataModel[0]['fieldTarget'] = null;
        }
        
        $data = $this->CrudManagement->run($config, $dataModel);

        $dataModel[0]['filter'] = 0;
        $dataModel[0]['filterKey'] = null;
        $dataModel[0]['limit'] = null;

        $getTotalData = $this->CrudManagement->run($config, $dataModel);

        $data['totalData'] = count($getTotalData['data']);

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
            if ($value->province_id)
            {
                $dataModelProvinceDetail[0]['filterKey'] = $value->province_id;
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
            if ($value['region_id'])
            {
                $dataModelRegionDetail[0]['filterKey'] = $value['region_id'];
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

        // Get data ownership detail
        $dataModelOwnerDetail = [
            [
                'className' => 'AnimalOwnershipDetail',
                'modelName' => 'AnimalOwnershipDetailModel',
                'filter' => 'ownership_id',
                'filterKey' => '',
                'limit' => null,
                'fieldTarget' => 'name',
                'dataMaster' => []
            ]
        ];

        foreach ($data['data'] as $key => $value) {
            if ($value['ownership_id'])
            {
                $dataModelOwnerDetail[0]['filterKey'] = $value['ownership_id'];
                $dataOwnerDetail = $this->CrudManagement->run($config, $dataModelOwnerDetail);
                
                $dataMaster = json_encode($data['data'][$key]);
                $dataMasterEncode = json_decode($dataMaster, TRUE);
                $dataMasterEncode['animal_list'] = $dataOwnerDetail['data'];
                $dataMasterResult = $dataMasterEncode;
                $data['data'][$key] = $dataMasterResult;
            }
        }

        // Get data animal detail
        $dataModelAnimalDetail = [
            [
                'className' => 'Animal',
                'modelName' => 'AnimalModel',
                'filter' => 'id',
                'filterKey' => '',
                'limit' => null,
                'fieldTarget' => 'name',
                'dataMaster' => []
            ]
        ];

        foreach ($data['data'] as $key => $value) {
            $keyData = $key;
            foreach ($value['animal_list'] as $key => $animal) {
                $keyAnimal = $key;
                $animalMaster = json_encode($animal);
                $animalMasterDecode = json_decode($animalMaster, TRUE);

                $data['data'][$keyData]['animal_list'][$keyAnimal] = $animalMasterDecode;
                $animalListDetail = $data['data'][$keyData]['animal_list'][$keyAnimal];
                
                if ($animalListDetail['animal_id'])
                {
                    $dataModelAnimalDetail[0]['filterKey'] = $animalListDetail['animal_id'];
                    $dataAnimalDetail = $this->CrudManagement->run($config, $dataModelAnimalDetail);
                    
                    $animalDetailMaster = json_encode($dataAnimalDetail['data'][0]);
                    $animalDetailMasterDecode = json_decode($animalDetailMaster, TRUE);
                    
                    $data['data'][$keyData]['animal_list'][$keyAnimal]['animal_detail'] = $animalDetailMasterDecode;
                }
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

                        $dataModelDetail[0]['dataMaster'] = $value;

                        $data = $this->CrudManagement->run($config, $dataModelDetail);

                        if ($data['status'] === 'Problem')
                        {
                            $flag = 1;
                        }
                    }
                    else 
                    {
                        $config = [
                            'catIdSegment' => (int) $value['ownership_detail_id'],
                            'isEditOrDeleteSegment' => 'edit',
                            'customParam' => true
                        ];

                        $dataModelDetail[0]['dataMaster'] = $value;

                        $data = $this->CrudManagement->run($config, $dataModelDetail);

                        if ($data['status'] === 'Problem')
                        {
                            $flag = 1;
                        }
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
                'filter' => '',
                'filterKey' => '',
                'limit' => '',
                'fieldTarget' => 'fullname',
                'queryString' => '',
                'dataMaster' => []
            ]
        ];

        $dataModel[0]['filter'] = 'create_sql';
        $dataModel[0]['filterKey'] = null;
        $dataModel[0]['limit'] = null;

        $data = $this->CrudManagement->run($config, $dataModel);

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
            if ($value->province_id)
            {
                $dataModelProvinceDetail[0]['filterKey'] = $value->province_id;
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
            if ($value['region_id'])
            {
                $dataModelRegionDetail[0]['filterKey'] = $value['region_id'];
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

        // Get data ownership detail
        $dataModelOwnerDetail = [
            [
                'className' => 'AnimalOwnershipDetail',
                'modelName' => 'AnimalOwnershipDetailModel',
                'filter' => 'ownership_id',
                'filterKey' => '',
                'limit' => null,
                'fieldTarget' => 'name',
                'dataMaster' => []
            ]
        ];

        foreach ($data['data'] as $key => $value) {
            if ($value['ownership_id'])
            {
                $dataModelOwnerDetail[0]['filterKey'] = $value['ownership_id'];
                $dataOwnerDetail = $this->CrudManagement->run($config, $dataModelOwnerDetail);
                
                $dataMaster = json_encode($data['data'][$key]);
                $dataMasterEncode = json_decode($dataMaster, TRUE);
                $dataMasterEncode['animal_list'] = $dataOwnerDetail['data'];
                $dataMasterResult = $dataMasterEncode;
                $data['data'][$key] = $dataMasterResult;
            }
        }

        // Get data animal detail
        $dataModelAnimalDetail = [
            [
                'className' => 'Animal',
                'modelName' => 'AnimalModel',
                'filter' => 'id',
                'filterKey' => '',
                'limit' => null,
                'fieldTarget' => 'name',
                'dataMaster' => []
            ]
        ];

        foreach ($data['data'] as $key => $value) {
            $keyData = $key;
            foreach ($value['animal_list'] as $key => $animal) {
                $keyAnimal = $key;
                $animalMaster = json_encode($animal);
                $animalMasterDecode = json_decode($animalMaster, TRUE);

                $data['data'][$keyData]['animal_list'][$keyAnimal] = $animalMasterDecode;
                $animalListDetail = $data['data'][$keyData]['animal_list'][$keyAnimal];
                
                if ($animalListDetail['animal_id'])
                {
                    $dataModelAnimalDetail[0]['filterKey'] = $animalListDetail['animal_id'];
                    $dataAnimalDetail = $this->CrudManagement->run($config, $dataModelAnimalDetail);
                    
                    $animalDetailMaster = json_encode($dataAnimalDetail['data'][0]);
                    $animalDetailMasterDecode = json_decode($animalDetailMaster, TRUE);
                    
                    $data['data'][$keyData]['animal_list'][$keyAnimal]['animal_detail'] = $animalDetailMasterDecode;
                }
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
            'titleContent' => 'Laporan Data Ownership',
            'dateMail' => 'Bogor, '.$dateNow->format('d F Y'),
            'contentMain' => $dataContentMain,
            'tableName' => 'Ownership',
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
            'title' => 'Laporan Data Ownership',
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