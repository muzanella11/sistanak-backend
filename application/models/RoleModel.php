<?php

/**
 * @author f1108k
 * @copyright 2018
 */



?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    class RoleModel extends MY_Model {
        function __construct(){
            parent::__construct();
        }

        function addDataRole ($data) {
            $sql    =   "INSERT INTO enem_user_role (name, status_role, date_created)
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

        function getDataRole ($filter = NULL, $filter_key = NULL, $limit = NULL, $field_target = NULL) {
            if(!empty($filter) && !empty($filter_key)) {
                if($filter === 'id') {
                    if(is_array($limit)) {
                        $sql    =   "SELECT * FROM enem_user_role WHERE role_id='".$filter_key."' LIMIT ".$limit['startLimit'].",".$limit['limitData']."";
                    } else {
                        $sql    =   "SELECT * FROM enem_user_role WHERE role_id='".$filter_key."'";
                    }
                } elseif ($filter === 'search') {
                    if(is_array($limit)) {
                        $sql    =   "SELECT * FROM enem_user_role WHERE ".$field_target." LIKE '%".$filter_key."%' LIMIT ".$limit['startLimit'].",".$limit['limitData']."";
                    } else {
                        $sql    =   "SELECT * FROM enem_user_role WHERE ".$field_target." LIKE '%".$filter_key."%'";
                    }
                } elseif ($filter === 'name') {
                    if(is_array($limit)) {
                        $sql    =   "SELECT * FROM enem_user_role WHERE name='".$filter_key."' LIMIT ".$limit['startLimit'].",".$limit['limitData']."";
                    } else {
                        $sql    =   "SELECT * FROM enem_user_role WHERE name='".$filter_key."'";
                    }
                } elseif ($filter === 'create_sql') {
                    if(is_array($limit)) {
                        $sql    =   "SELECT * FROM enem_user_role WHERE ".$filter_key." LIMIT ".$limit['startLimit'].",".$limit['limitData']."";
                    } else {
                        $sql    =   "SELECT * FROM enem_user_role WHERE ".$filter_key."";
                    }
                }
            } else {
                if(is_array($limit)) {
                    $sql    =   "SELECT * FROM enem_user_role LIMIT ".$limit['startLimit'].",".$limit['limitData']."";
                } else {
                    $sql    =   "SELECT * FROM enem_user_role";
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

        function updateDataRole($data, $findBy = '', $findByValue = '') {
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
            
            $sql    =   "UPDATE enem_user_role SET ".$queryResult.", date_update=now() WHERE ".$findBy."='".$findByValue."'";
            $this->db->query($sql);
        }

        function deleteDataRole($field_name, $field_value) {
            $sql    =   "DELETE FROM enem_user_role WHERE ".$field_name." = '".$field_value."'";
            $this->db->query($sql);
        }
    }
?>