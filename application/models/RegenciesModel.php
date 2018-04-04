<?php

/**
 * @author f1108k
 * @copyright 2018
 */



?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    class RegenciesModel extends MY_Model {
        private $tableName = 'enem_ids_regencies';

        function __construct(){
            parent::__construct();
        }

        function addDataRegencies ($data) {
            $sql    =   "INSERT INTO {$this->tableName} (name, province_id, regencies_id)
                            VALUES('".strtoupper($data['name'])."', '".$data['province_id']."', '".$data['regencies_id']."')";
            
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

        function getDataRegencies ($filter = NULL, $filter_key = NULL, $limit = NULL, $field_target = NULL) {
            if(!empty($filter) && !empty($filter_key)) {
                if($filter === 'id') {
                    if(is_array($limit)) {
                        $sql    =   "SELECT * FROM {$this->tableName} WHERE regencies_id='".$filter_key."' LIMIT ".$limit['startLimit'].",".$limit['limitData']."";
                    } else {
                        $sql    =   "SELECT * FROM {$this->tableName} WHERE regencies_id='".$filter_key."'";
                    }
                } elseif ($filter === 'search') {
                    if(is_array($limit)) {
                        $sql    =   "SELECT * FROM {$this->tableName} WHERE ".$field_target." LIKE '%".$filter_key."%' LIMIT ".$limit['startLimit'].",".$limit['limitData']."";
                    } else {
                        $sql    =   "SELECT * FROM {$this->tableName} WHERE ".$field_target." LIKE '%".$filter_key."%'";
                    }
                } elseif ($filter === 'name') {
                    if(is_array($limit)) {
                        $sql    =   "SELECT * FROM {$this->tableName} WHERE name='".$filter_key."' LIMIT ".$limit['startLimit'].",".$limit['limitData']."";
                    } else {
                        $sql    =   "SELECT * FROM {$this->tableName} WHERE name='".$filter_key."'";
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

        function updateDataRegencies($data, $findBy = '', $findByValue = '') {
            $name = "name='".strtoupper($data['name'])."'";
            $regencies_id = "regencies_id='".$data['regencies_id']."'";
            $findBy = 'regencies_id';

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
            
            $sql    =   "UPDATE {$this->tableName} SET ".$queryResult." WHERE ".$findBy."='".$findByValue."'";
            
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

        function deleteDataRegencies($field_name, $field_value) {
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