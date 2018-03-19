<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/RestManager.php';
require APPPATH . '/libraries/BalanceManagement.php';

class Balance extends RestManager {
    private $BalanceManagement;

    function __construct () 
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('enem_user_model');
        $this->load->model('BalanceModel');
        $this->BalanceManagement = new BalanceManagement();
    }

    public function index_get() 
    {
        $queryString = $this->input->get();
        $dataBalance = $this->BalanceManagement->getListBalance($queryString);

        $data = [
            'status' => 'Ok',
            'messages' => 'Hello guys :)',
            'data' => $dataBalance
        ];
        
        return $this->set_response($data, REST_Controller::HTTP_OK);
    }

    public function create_post()
    {
        $userId = $this->post('user_id');
        $amount = $this->post('amount');

        $statusToken = $this->checkToken();

        $data = [
            'status' => 'Problem',
            'messages' => 'Unauthorize'
        ];

        if ($statusToken === 0)
        {
            $dataPost = [
                'user_id' => $userId,
                'amount_balance' => $amount
            ];

            $dataValidate = $this->BalanceManagement->validateBalance($dataPost);

            if ($dataValidate['flag'] === 0)
            {
                $db = $dataPost;
                $this->BalanceModel->addUserBalance($db);
                $dataBalance = $this->BalanceModel->getDataUserBalance('id', $dataPost['user_id']);
                $data['status'] = 'Ok';
                $data['data'] = $dataBalance;
            }
            else
            {
                unset($dataValidate['flag']);
                $data = $dataValidate;
            }
        }

        return $this->set_response($data, $flag === 0 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_REQUEST);
    }

    public function update_post()
    {
        $keyId = $this->uri->segment(3); // must be id
        $userId = (int) $this->uri->segment(4);
        $amount = (int) $this->post('amount');
        $statusBalance = $this->post('addBalance');

        $statusToken = $this->checkToken();

        $data = [
            'status' => 'Problem',
            'messages' => 'Unauthorize'
        ];

        if ($statusToken === 0)
        {
            $dataPost = [
                'user_id' => $userId,
                'amount_balance' => $amount
            ];

            $dataValidate = $this->BalanceManagement->validateUpdateBalance($dataPost);

            if ($dataValidate['flag'] === 0)
            {
                $db = $dataPost;
                $this->BalanceModel->updateUserBalance($db);
                $dataBalance = $this->BalanceModel->getDataUserBalance('id', $dataPost['user_id']);
                $data['status'] = 'Ok';
                $data['data'] = $dataBalance;
            }
            else
            {
                unset($dataValidate['flag']);
                $data = $dataValidate;
            }
        }

        return $this->set_response($data, $dataValidate['flag'] === 0 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_REQUEST);
    }
}