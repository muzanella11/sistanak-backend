<?php

/**
 * @author f1108k
 * @copyright 2018
 */



?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    class ProvincesModel extends MY_Model {
        private $tableName = 'enem_ids_provinces';

        function __construct(){
            parent::__construct();
        }

        function addDataProvinces ($data) {
            $sql    =   "INSERT INTO {$this->tableName} (name, status_role, date_created)
                            VALUES('".$data['name']."', '".$data['status_role']."', now())";
            
            $this->db->query($sql);

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
            }
            else
            {
                $this->db->trans_commit();
            }
        }

        function getDataProvinces ($filter = NULL, $filter_key = NULL, $limit = NULL, $field_target = NULL) {
            if(!empty($filter) && !empty($filter_key)) {
                if($filter === 'id') {
                    if(is_array($limit)) {
                        $sql    =   "SELECT * FROM {$this->tableName} WHERE role_id='".$filter_key."' LIMIT ".$limit['startLimit'].",".$limit['limitData']."";
                    } else {
                        $sql    =   "SELECT * FROM {$this->tableName} WHERE role_id='".$filter_key."'";
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

        function updateDataProvinces($data, $findBy = '', $findByValue = '') {
            $name = "name='".$data['name']."'";
            $status_role = "status_role='".$data['status_role']."'";
            $findBy = 'role_id';

            $query = [];
            foreach ($data as $key => $value) {
                if ($value) {
                    array_push($query, ${$key});
                }
            }

            $queryResult = implode(',', $query);
            
            $sql    =   "UPDATE {$this->tableName} SET ".$queryResult.", date_update=now() WHERE ".$findBy."='".$findByValue."'";
            $this->db->query($sql);
        }

        function deleteDataProvinces($field_name, $field_value) {
            $sql    =   "DELETE FROM {$this->tableName} WHERE ".$field_name." = '".$field_value."'";
            $this->db->query($sql);
        }
    }
?>