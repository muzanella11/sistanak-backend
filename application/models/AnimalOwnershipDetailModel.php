<?php

/**
 * @author f1108k
 * @copyright 2018
 */



?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    class AnimalOwnershipDetailModel extends MY_Model {
        private $tableName = 'enem_animals_ownership_detail';

        function __construct(){
            parent::__construct();
        }

        function addDataAnimalOwnershipDetail ($data) {
            $sql    =   "INSERT INTO {$this->tableName} (ownership_id, animal_id, group_id, gender_id, amount, date_created)
                            VALUES('".$data['ownership_id']."', '".$data['animal_id']."', '".$data['group_id']."', '".$data['gender_id']."', '".$data['amount']."', now())";
            
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

        function getDataAnimalOwnershipDetail ($filter = NULL, $filter_key = NULL, $limit = NULL, $field_target = NULL) {
            if(!empty($filter) && !empty($filter_key)) {
                if($filter === 'ownership_id') {
                    if(is_array($limit)) {
                        $sql    =   "SELECT * FROM {$this->tableName} WHERE ownership_id='".$filter_key."' LIMIT ".$limit['startLimit'].",".$limit['limitData']."";
                    } else {
                        $sql    =   "SELECT * FROM {$this->tableName} WHERE ownership_id='".$filter_key."'";
                    }
                } elseif($filter === 'id') {
                    if(is_array($limit)) {
                        $sql    =   "SELECT * FROM {$this->tableName} WHERE ownership_detail_id='".$filter_key."' LIMIT ".$limit['startLimit'].",".$limit['limitData']."";
                    } else {
                        $sql    =   "SELECT * FROM {$this->tableName} WHERE ownership_detail_id='".$filter_key."'";
                    }
                } elseif ($filter === 'search') {
                    if(is_array($limit)) {
                        $sql    =   "SELECT * FROM {$this->tableName} WHERE ".$field_target." LIKE '%".$filter_key."%' LIMIT ".$limit['startLimit'].",".$limit['limitData']."";
                    } else {
                        $sql    =   "SELECT * FROM {$this->tableName} WHERE ".$field_target." LIKE '%".$filter_key."%'";
                    }
                } elseif ($filter === 'create_sql') {
                    if(is_array($limit)) {
                        $sql    =   "SELECT * FROM {$this->tableName} WHERE ".$filter_key." LIMIT ".$limit['startLimit'].",".$limit['limitData']."";
                    } else {
                        $sql    =   "SELECT * FROM {$this->tableName} WHERE ".$filter_key."";
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

        function updateDataAnimalOwnershipDetail ($data, $findBy = '', $findByValue = '') {
            $ownership_id = "ownership_id='".ucwords($data['ownership_id'])."'";
            $animal_id = "animal_id='".$data['animal_id']."'";
            $group_id = "group_id='".$data['group_id']."'";
            $gender_id = "gender_id='".$data['gender_id']."'";
            $amount = "amount='".$data['amount']."'";
            $findBy = 'ownership_detail_id';

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

        function deleteDataAnimalOwnershipDetail ($field_name, $field_value) {
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