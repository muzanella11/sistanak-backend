<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/RestManager.php';
require APPPATH . '/libraries/CrudManagement.php';
require APPPATH . '/libraries/PdfManagement.php';

use Carbon\Carbon;

class Data extends RestManager {
    private $className = 'Animal';
    private $modelName = 'AnimalModel';

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
            $dataModel[0]['filterKey'] = $queryString['q'] !== 'null' ? 'WHERE name like "%'.$queryString['q'].'%" or animal_id like "%'.$queryString['q'].'%"' : null;
            $dataModel[0]['fieldTarget'] = null;
        }
        
        $data = $this->CrudManagement->run($config, $dataModel);

        $dataModel[0]['filter'] = 0;
        $dataModel[0]['filterKey'] = null;
        $dataModel[0]['limit'] = null;

        $getTotalData = $this->CrudManagement->run($config, $dataModel);

        $data['totalData'] = count($getTotalData['data']);

        foreach ($data['data'] as $key => $value) {
            $dataMaster = json_encode($data['data'][$key]);
            $dataMasterEncode = json_decode($dataMaster, TRUE);
            $data['data'][$key] = $dataMasterEncode;
            $animalId = (int) $data['data'][$key]['animal_id'];
            $data['data'][$key]['animal_id'] = $animalId;
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
                    'description' => $description
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
                    'description' => $description
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
                'fieldName' => 'animal_id'
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
            'catIdSegment' => 2,
            'isEditOrDeleteSegment' => 3
        ];

        $dataModel = [
            [
                'className' => 'Animal',
                'modelName' => 'AnimalModel',
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
            $animalId = (int) $data['data'][$key]['animal_id'];
            $data['data'][$key]['animal_id'] = $animalId;
        }

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
            'titleContent' => 'Laporan Data Hewan',
            'dateMail' => 'Bogor, '.$dateNow->format('d F Y'),
            'contentMain' => $dataContentMain,
            'tableName' => 'Hewan',
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
            'title' => 'Laporan Data Hewan',
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