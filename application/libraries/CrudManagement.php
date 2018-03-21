<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class CrudManagement {
    
    private $CI;

    function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->library('enem_templates');
        $this->CI->load->model('enem_user_model');
    }

    public function run ($model = [])
    {
        // $model = [
        //     [
        //         'modelName' => 'myModel',
        //         'methodName' => 'methodModel',
        //         'filter' => 'something',
        //         'filterKey' => 'something',
        //         'limit' => [
        //             'startLimit' => 0,
        //             'limitData' => 10
        //         ],
        //         'dataMaster' => []
        //     ]
        // ]

        ini_set('max_execution_time', 0);
        
        $start = microtime(TRUE);

        $getCatOrId = strtolower($this->CI->uri->segment(3));
        $isEditOrDelete = strtolower($this->CI->uri->segment(4));
        
        $flag = 0;
        $data = [
            'status' => 'Ok',
            'messages' => ''
        ];

        if ($getCatOrId === 'create') // if has a category === create
        {
            $dataModel = $this->runModelPost($model, $getCatOrId);

            if ($dataModel['flag'])
            {
                $flag = $dataModel['flag'];
                unset($dataModel['flag']);

                $data = $dataModel;
            }
            else
            {
                $data = [
                    'status' => 'Ok',
                    'messages' => 'Berhasil',
                    'data' => [
                        'getCatOrId' => $getCatOrId
                    ]
                ];
            }
        }
        else 
        {
            if ($getCatOrId === null) // if category null
            {
                $flag = 1;
                $data = [
                    'status' => 'Problem',
                    'messages' => 'Something wrong'
                ];
            }
            else
            {
                if (is_numeric($getCatOrId) && $isEditOrDelete && $isEditOrDelete === 'edit' || $isEditOrDelete === 'delete') // if numeric => action edit or delete
                {
                    $data = [
                        'status' => 'Ok',
                        'messages' => 'Berhasil',
                        'data' => [
                            'getCatOrId' => $getCatOrId
                        ]
                    ];
                }
                elseif (is_numeric($getCatOrId))
                {
                    $newModel = [];
                    foreach ($model as $key => $value) {
                        $value['filter'] = 'id';
                        $value['filterKey'] = $getCatOrId;
                        array_push($newModel, $value);
                    }

                    $dataMaster = $this->runModelGet($newModel, $getCatOrId); // read data by id
                    $data = [
                        'status' => 'Ok',
                        'messages' => 'Berhasil',
                        'totalData' => count($dataMaster),
                        'data' => $dataMaster
                    ];
                }
                else
                {
                    $dataMaster = $this->runModelGet($model, $getCatOrId); // read all data
                    $data = [
                        'status' => 'Ok',
                        'messages' => 'Berhasil',
                        'totalData' => count($dataMaster),
                        'data' => $dataMaster
                    ];
                }
            }
        }

        $end = microtime(TRUE);
        $getRunTime = ($end-$start).' seconds';

        $data['status_process'] = [
            'runtime' => $getRunTime,
        ];

        return $data;
    }

    public function runModelPost ($model = [], $getCatOrId)
    {
        $flag = 0;

        if ($getCatOrId === 'create')
        {
            $getMethodName = 'addData';
        }
        else 
        {
            $flag = 1;
        }

        if (!$flag)
        {
            foreach ($model as $key => $value) {
                $methodName = $getMethodName.$value['className'];
                $value['methodName'] = $methodName;

                $this->CI->load->model($value['modelName']);
                $this->CI->{$value['modelName']}->{$value['methodName']}($value['dataMaster']);
            }

            $data = [
                'flag' => $flag,
                'status' => 'Ok',
                'messages' => 'Berhasil'
            ];
        }
        else
        {
            $data = [
                'flag' => $flag,
                'status' => 'Problem',
                'messages' => 'Something wrong'
            ];
        }

        return $data;
    }

    public function runModelGet ($model = [])
    {
        $getMethodName = 'getData';
        foreach ($model as $key => $value) {
            $methodName = $getMethodName.$value['className'];
            $value['methodName'] = $methodName;

            $this->CI->load->model($value['modelName']);
            $data = $this->CI->{$value['modelName']}->{$value['methodName']}($value['filter'], $value['filterKey'], $value['limit']);
        }

        return $data;
    }
}