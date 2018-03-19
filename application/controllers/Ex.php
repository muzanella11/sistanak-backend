<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/RestManager.php';

class Ex extends RestManager {
    function __construct () 
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('enem_user_model');
    }

    public function index_get() 
    {
        $data = [
            'status' => 'Ok',
            'messages' => 'Hello guys :)'
        ];
        
        return $this->set_response($data, REST_Controller::HTTP_OK);
    }
}