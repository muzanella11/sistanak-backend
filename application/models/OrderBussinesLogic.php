<?php

class OrderBussinesLogic {
    private $CI;

    function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('UserModel');
        $this->CI->load->model('PackageModel');
    }

    public function getPackage($id) {
        // example return
        // $return = array(
        //     'id_package' => 3,
        //     'name_package' => 'Enterprise Package',
        //     'desc_package' => 'Enterprise Package',
        //     'limit_user' => 5,
        //     'price' => 250
        // );
        return $this->CI->PackageModel->findPackageById($id);
    }

    public function getPackageAdditional($id) {
        // example return
        // $return = array(
        //     'id_package_additional' => 1,
        //     'name_package' => 'Add new user',
        //     'desc_package' => 'Add number of user(s) to your current package',
        //     'limit_additional' => 1,
        //     'price' => 10
        // );
        // $return = array(
        //     'id_package_additional' => 2,
        //     'name_package' => '100 Api call',
        //     'desc_package' => 'The number of API call(s) you can make',
        //     'limit_additional' => 100,
        //     'price' => 5
        // );
        return $this->CI->PackageModel->findAdditionalPackage(id);
    }

    public function validatePackage($dataPost = []) {
        $dataUser = $this->CI->UserModel->getUserData('id', $dataPost['user_id']);
        $dataPackage = $this->getPackage($dataPost['package']);

        if (!$dataPost['user_id'])
        {
            $flag = 1;
            $data['status'] = 'Problem';
            $data['messages'] = 'Data user id is empty';
        }
        else if (!$dataUser)
        {
            $flag = 1;
            $data['status'] = 'Problem';
            $data['messages'] = 'Data user id is not found';
        }
        else if (!$dataPackage)
        {
            $flag = 1;
            $data['status'] = 'Problem';
            $data['messages'] = 'Package is not found';
        }
        else
        {
            $flag = 0;
        }

        $data['flag'] = $flag;

        return $data;
    }

    public function orderPackage($dataPost = []) {
        // example dataPost from interface
        // $dataPost = array(
        //     'user_id' => 8,
        //     'package' => 3,
        //     'additional' => array(
        //         array(
        //             'additional_id' => 1,
        //             'amount' => 3
        //         ),
        //         array(
        //             'additional_id' => 2,
        //             'amount' => 2
        //         )
        //     )
        // );
        if ($this->validatePackage($dataPost))
        {
            return $this->validatePackage($dataPost)
        }
        else
        {
            $dataPost['total_payment'] = $this->totalPayment($dataPost);
            $this->createOrderPackage($dataPost);
        }
    }

    public function totalPayment($dataPost = [])
    {
        $price = 0;
        $dataPackage = $this->getPackage($dataPost['package']);
        foreach ($dataPost['additional'] as $key => $value) {
            $dataPackageAdditional = $this->getPackageAdditional($value['additional_id']);
            $price += $value['amount'] * $dataPackageAdditional['price'];
        }
        $price += $price + $dataPackage['price'];
        
        return $price;
    }

    public function createOrderPackage($dataPost = [])
    {
        $this->CI->PackageModel->createOrderPackage($dataPost);
    }
}
