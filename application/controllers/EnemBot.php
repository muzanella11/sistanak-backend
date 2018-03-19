<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/RestManager.php';

class EnemBot extends RestManager {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->model('enem_user_model');
    }

    public function botuser_post() 
    {
        ini_set('max_execution_time', 0);
        
        $start = microtime(TRUE);

        $enemKey       = $this->enem_templates->anti_injection(strtolower($this->post('enemKey')));
        $enemAmountOrId    = $this->enem_templates->anti_injection(strtolower($this->post('enemAmountOrId')));

        $flag = 0;
        $data = [
            'status' => 'Ok',
            'messages' => ''
        ];

        if ($enemKey && $enemAmountOrId) 
        {
            if ($enemKey === 'ebot') 
            {
                if ($enemAmountOrId === 'delete') 
                {
                    // $this->load->model('enem_user_model');

                    $dataBot = $this->enem_user_model->deleteBotEnem('enem_user', 'name', 'enem');
                    $enem_last_data = count($dataBot);
                    $enem_bot_total = $enemAmountOrId;

                    $end = microtime(TRUE);
                    $getRunTime = ($end-$start).' seconds';

                    $data = [
                        'status' => 'Ok',
                        'messages' => 'Success delete bot user',
                        'data' => [
                            'lastData' => $enem_last_data,
                            'botTotal' => $enem_bot_total,
                            'runtime' => $getRunTime,
                        ],
                    ];

                } 
                elseif (is_numeric($enemAmountOrId)) 
                {

                    /** For Generate Bot User **/
                    $enem_prefix = 'enem';
                    $enem_password = $this->enem_templates->enem_secret('enem123');
                    $enem_role = 2;


                    // $this->load->model('enem_user_model');

                    $dataBot = $this->enem_user_model->checkBotEnem('enem_user', 'name', 'enem');
                    // $enem_last_data = 0;
                    $enem_last_data = count($dataBot);
                    $enem_bot_total = $enemAmountOrId;
                    // var_dump(count($dataBot)); exit();

                    if ($enem_last_data) 
                    {
                        $enem_bot_total_now = $enem_last_data + $enem_bot_total;
                        for ($i=$enem_last_data; $i < $enem_bot_total_now; $i++) {

                            $nomer = $i + 1;
                            $name = $enem_prefix.$nomer;
                            $username = $name;
                            $email = $name.'@enem.com';

                            $db = array(
                                'name' => $name,
                                'username' => $username,
                                'password' => $enem_password,
                                'email' => $email,
                                'role' => $enem_role,
                            );

                            $this->enem_user_model->addDataUserEnem($db);

                            $dataBot = $this->enem_user_model->checkBotEnem('enem_user', 'name', 'enem');
                        }
                    } 
                    else 
                    {
                        for ($i=0; $i < $enem_bot_total; $i++) {

                            $nomer = $i + 1;
                            $name = $enem_prefix.$nomer;
                            $username = $name;
                            $email = $name.'@enem.com';

                            $db = array(
                                'name' => $name,
                                'username' => $username,
                                'password' => $enem_password,
                                'email' => $email,
                                'role' => $enem_role,
                            );

                            $this->enem_user_model->addDataUserEnem($db);

                            $dataBot = $this->enem_user_model->checkBotEnem('enem_user', 'name', 'enem');
                        }
                    }


                    /** End Generate Bot **/

                    $end = microtime(TRUE);
                    $getRunTime = ($end-$start).' seconds';

                    $data = [
                        'status' => 'Ok',
                        'messages' => 'Success add '.$enem_bot_total.' bot user',
                        'data' => [
                            'lastData' => $enem_last_data,
                            'botTotalAdd' => $enem_bot_total,
                            'allBotData' => count($dataBot),
                            'runtime' => $getRunTime,
                        ],
                    ];

                }
                elseif ($enemAmountOrId === 'checktotaldata')
                {
                    $dataBot = $this->enem_user_model->checkBotEnem('enem_user', 'name', 'enem');

                    $end = microtime(TRUE);
                    $getRunTime = ($end-$start).' seconds';
                    
                    $data = [
                        'status' => 'Ok',
                        'messages' => 'Success read '.count($dataBot).' bot user',
                        'data' => [
                            'allBotData' => count($dataBot),
                            'runtime' => $getRunTime,
                        ],
                    ];
                } 
                else 
                {
                    $flag = 1;
                    $data = [
                        'status' => 'Problem',
                        'messages' => 'Not found enemAmountOrId'
                    ];
                }

            } 
            elseif ($enemKey === 'tlbot') 
            {
                // For Type Log Bot
                var_dump('type log bot '.$enemAmountOrId); exit();
            } 
            else 
            {
                $flag = 1;
                $data = [
                    'status' => 'Problem',
                    'messages' => 'Not found enemKey'
                ];
            }
        } 
        else 
        {
            $flag = 1;
            $data = [
                'status' => 'Problem',
                'messages' => 'enemKey or enemAmountOrId not found'
            ];
        }

        return $this->response($data, $flag !== 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_REQUEST);

    }

}
