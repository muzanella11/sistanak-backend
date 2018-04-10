<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/RestManager.php';
require APPPATH . '/libraries/CrudManagement.php';
require APPPATH . '/libraries/PdfManagement.php';

use Carbon\Carbon;

class User extends RestManager {
    function __construct () 
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('enem_user_model');
        $this->CrudManagement = new CrudManagement();
        $this->Carbon = new \Carbon\Carbon();
        $this->pdf = new PdfManagement();
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

        // Get data role detail
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
        // $password = $this->enem_templates->enem_secret($this->post('password'));
        $password = $this->enem_templates->enem_secret('enem123');
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
                'className' => 'User',
                'modelName' => 'UserModel',
                'filter' => 'create_sql',
                'filterKey' => '',
                'limit' => '',
                'fieldTarget' => 'name',
                'queryString' => [],
                'dataMaster' => []
            ]
        ];

        $data = $this->CrudManagement->run($config, $dataModel);

        // Get data role detail
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

        $dataContentMain = 'ini report';
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
            'titleContent' => 'Laporan Data User',
            'dateMail' => 'Bogor, '.$dateNow->format('d F Y'),
            'contentMain' => $dataContentMain,
            'tableName' => 'User',
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
            'title' => 'Laporan Data User',
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

    public function penugasan_post()
    {
        $dateNow = Carbon::now();
        $dateNow->timezone = new DateTimeZone('Asia/Jakarta');

        $pemberi = $this->post('pemberi_id');
        $penerima = $this->post('penerima_id');

        $config = [
            'catIdSegment' => 2,
            'isEditOrDeleteSegment' => 3
        ];

        // Get Data Pemberi
        $dataModelUserDetail = [
            [
                'className' => 'User',
                'modelName' => 'UserModel',
                'filter' => 'id',
                'filterKey' => '',
                'limit' => null,
                'fieldTarget' => 'name',
                'dataMaster' => []
            ]
        ];

        $dataModelUserDetail[0]['filterKey'] = $pemberi;
        $dataPemberiDetail = $this->CrudManagement->run($config, $dataModelUserDetail);
        $dataPemberi = $dataPemberiDetail['data'];
        $dataMasterPemberi = json_encode($dataPemberi);
        $dataMasterPemberiEncode = json_decode($dataMasterPemberi, TRUE);

        // Get Role Detail Pemberi
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

        foreach ($dataMasterPemberiEncode as $key => $value) {
            $dataMasterPemberiEncode[$key]['user_id'] = (int) $value['user_id'];
            $dataMasterPemberiEncode[$key]['nik'] = (int) $value['nik'];
            $dataMasterPemberiEncode[$key]['user_role'] = (int) $value['user_role'];

            // Get Data Role Pemberi
            $dataModelRoleDetail[0]['filterKey'] = $dataMasterPemberiEncode[$key]['user_role'];
            $dataRoleDetail = $this->CrudManagement->run($config, $dataModelRoleDetail);
            $dataRole = $dataRoleDetail['data'][0];
            $dataMasterRole = json_encode($dataRole);
            $dataMasterRoleEncode = json_decode($dataMasterRole, TRUE);

            $dataMasterPemberiEncode[$key]['role_detail'] = $dataMasterRoleEncode;
        }

        $dataPemberiResult = $dataMasterPemberiEncode[0]; // result Pemberi

        // Get Data Penerima
        $dataModelUserDetail[0]['filterKey'] = $penerima;
        $dataPenerimaDetail = $this->CrudManagement->run($config, $dataModelUserDetail);
        $dataPenerima = $dataPenerimaDetail['data'];
        $dataPenerima = $dataPenerimaDetail['data'];
        $dataMasterPenerima = json_encode($dataPenerima);
        $dataMasterPenerimaEncode = json_decode($dataMasterPenerima, TRUE);

        // Get Role Detail Penerima
        foreach ($dataMasterPenerimaEncode as $key => $value) {
            $dataMasterPenerimaEncode[$key]['user_id'] = (int) $value['user_id'];
            $dataMasterPenerimaEncode[$key]['nik'] = (int) $value['nik'];
            $dataMasterPenerimaEncode[$key]['user_role'] = (int) $value['user_role'];

            // Get Data Role Penerima
            $dataModelRoleDetail[0]['filterKey'] = $dataMasterPenerimaEncode[$key]['user_role'];
            $dataRoleDetail = $this->CrudManagement->run($config, $dataModelRoleDetail);
            $dataRole = $dataRoleDetail['data'][0];
            $dataMasterRole = json_encode($dataRole);
            $dataMasterRoleEncode = json_decode($dataMasterRole, TRUE);

            $dataMasterPenerimaEncode[$key]['role_detail'] = $dataMasterRoleEncode;
        }

        $dataPenerimaResult = $dataMasterPenerimaEncode[0]; // result Penerima

        // Mapping Content Master
        $contenMaster = [
            'pemberi' => [
                'title' => 'Pejabat yang berwenang memberi perintah',
                'name' => 'Si pemberi'
            ],
            'penerima' => [
                'title' => 'Nama Pegawai yang di perintah',
                'name' => 'Si penerima'
            ],
            'tujuan' => [
                'title' => 'Maksud Perjalanan Dinas',
                'name' => 'Dalam rangka pendataan hewan atau lingkungan kesehatan'
            ]
        ];

        $contenMaster['pemberi']['name'] = $dataPemberiResult['name'];
        $contenMaster['penerima']['name'] = $dataPenerimaResult['name'];

        $contentMain = [];
        $number = 1;
        foreach ($contenMaster as $key => $value) {
            $no = $number;
            $dataContent = "<div style='position: relative; width: 100%; padding: 5px 0; border-top: 1px solid black; display: inline-block; font-size: 15px;'>
                <div style='position: relative; width: 5%; float: left;'>
                    {$no}.
                </div>
                <div style='position: relative; width: 45%; float: left;'>
                    {$value['title']}
                </div>
                <div style='position: relative; width: 48%; float: left; padding-left: 8px;'>
                    <span style='margin-right: 15px; display:block;'>:</span> {$value['name']}
                </div>
            </div>";
            array_push($contentMain, $dataContent);
            $number++;
        }

        $dataContentMain = implode(' ', $contentMain);
        // var_dump(implode(' ', $contentMain));exit;
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
            'dateMail' => 'Bogor, '.$dateNow->format('d F Y'),
            'contentMain' => $dataContentMain,
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
        $view = $this->load->view('mails/templates/SuratPerjalananDinas', $dataView, true);
        $configPdf = [
            'title' => 'Surat Perjalanan Dinas',
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