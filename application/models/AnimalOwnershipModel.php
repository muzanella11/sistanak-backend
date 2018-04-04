<?php

/**
 * @author f1108k
 * @copyright 2018
 */



?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    class AnimalOwnershipModel extends MY_Model {
        private $tableName = 'enem_animals_ownership';

        function __construct(){
            parent::__construct();
        }

        function addDataAnimalOwnership ($data) {
            $sql    =   "INSERT INTO {$this->tableName} (fullname, identity_number, identity_type, phone, province_id, region_id, village_id, address, birth_date, date_created)
                            VALUES('".ucwords($data['fullname'])."', '".$data['identity_number']."', '".$data['identity_type']."', '".$data['phone']."', '".$data['province_id']."', '".$data['region_id']."', '".$data['village_id']."', '".$data['address']."', '".$data['birth_date']."', now())";
            
            $query  =   $this->db->query($sql);
            $latestId = $this->db->insert_id();

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
            }
            else
            {
                $this->db->trans_commit();
            }

            $getError = $this->db->error();

            if (!$getError['message']) {
                return [
                    'latest_create_id' => $latestId,
                    'flag' => 0,
                    'messages' => 'Berhasil menambahkan data'
                ];
            } else {
                return [
                    'flag' => 1,
                    'messages' => 'Gagal menambahkan data'
                ];
            }
        }

        function getDataAnimalOwnership ($filter = NULL, $filter_key = NULL, $limit = NULL, $field_target = NULL) {
            if(!empty($filter) && !empty($filter_key)) {
                if($filter === 'id') {
                    if(is_array($limit)) {
                        $sql    =   "SELECT * FROM {$this->tableName} WHERE ownership_id='".$filter_key."' LIMIT ".$limit['startLimit'].",".$limit['limitData']."";
                    } else {
                        $sql    =   "SELECT * FROM {$this->tableName} WHERE ownership_id='".$filter_key."'";
                    }
                } elseif ($filter === 'search') {
                    if(is_array($limit)) {
                        $sql    =   "SELECT * FROM {$this->tableName} WHERE ".$field_target." LIKE '%".$filter_key."%' LIMIT ".$limit['startLimit'].",".$limit['limitData']."";
                    } else {
                        $sql    =   "SELECT * FROM {$this->tableName} WHERE ".$field_target." LIKE '%".$filter_key."%'";
                    }
                } elseif ($filter === 'create_sql') {
                    if(is_array($limit)) {
                        $sql    =   "SELECT * FROM {$this->tableName} ".$filter_key." LIMIT ".$limit['startLimit'].",".$limit['limitData']."";
                    } else {
                        $sql    =   "SELECT * FROM {$this->tableName} ".$filter_key."";
                    }
                }
            } else {
                if(is_array($limit)) {
                    $sql    =   "SELECT * FROM {$this->tableName} LIMIT ".$limit['startLimit'].",".$limit['limitData']."";
                } else {
                    $sql    =   "SELECT * FROM {$this->tableName}";
                }
            }

            $query  =   $this->db->query($sql);
            $getError = $this->db->error();

            if (!$getError['message'] && $query->num_rows() > 0) {
                return $query->result();
            } else {
                return [];
            }
        }

        function updateDataAnimalOwnership ($data, $findBy = '', $findByValue = '') {
            $fullname = "fullname='".ucwords($data['fullname'])."'";
            $identity_number = "identity_number='".$data['identity_number']."'";
            $identity_type = "identity_type='".$data['identity_type']."'";
            $phone = "phone='".$data['phone']."'";
            $province_id = "province_id='".$data['province_id']."'";
            $region_id = "region_id='".$data['region_id']."'";
            $village_id = "village_id='".$data['village_id']."'";
            $address = "address='".$data['address']."'";
            $birth_date = "birth_date='".$data['birth_date']."'";
            $findBy = 'ownership_id';

            $query = [];
            foreach ($data as $key => $value) {
                if (isset(${$key}) && $value) {
                    array_push($query, ${$key});
                }
            }

            $queryResult = implode(',', $query);

            if (!$queryResult)
            {
                return [
                    'flag' => 1,
                    'messages' => 'Gagal mengubah data'
                ];
            }

            $sql    =   "UPDATE {$this->tableName} SET ".$queryResult.", date_update=now() WHERE ".$findBy."='".$findByValue."'";
            
            $query  =   $this->db->query($sql);
            $getError = $this->db->error();

            if (!$getError['message']) {
                return [
                    'flag' => 0,
                    'messages' => 'Berhasil mengubah data'
                ];
            } else {
                return [
                    'flag' => 1,
                    'messages' => 'Gagal mengubah data'
                ];
            }
        }

        function deleteDataAnimalOwnership ($field_name, $field_value) {
            $sql    =   "DELETE FROM {$this->tableName} WHERE ".$field_name." = '".$field_value."'";
            
            $query  =   $this->db->query($sql);
            $getError = $this->db->error();

            if (!$getError['message']) {
                return [
                    'flag' => 0,
                    'messages' => 'Berhasil menghapus data'
                ];
            } else {
                return [
                    'flag' => 1,
                    'messages' => 'Gagal menghapus data'
                ];
            }
        }
    }
?>