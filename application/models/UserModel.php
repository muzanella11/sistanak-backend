<?php

/**
 * @author f1108k
 * @copyright 2018
 */



?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    class UserModel extends MY_Model {
        function __construct(){
            parent::__construct();
        }

        function addDataUser ($data) {
            $sql    =   "INSERT INTO enem_user (name, nik, username, password, email, user_role, date_created)
                            VALUES('".$data['name']."', '".$data['nik']."', '".$data['username']."', '".$data['password']."', '".$data['email']."', '".$data['user_role']."', now())";
            
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

        function getDataUser ($filter = NULL, $filter_key = NULL, $limit = NULL, $field_target = NULL) {
            if(!empty($filter) && !empty($filter_key)) {
                if($filter === 'id') {
                    if(is_array($limit)) {
                        $sql    =   "SELECT * FROM enem_user WHERE user_id='".$filter_key."' LIMIT ".$limit['startLimit'].",".$limit['limitData']."";
                    } else {
                        $sql    =   "SELECT * FROM enem_user WHERE user_id='".$filter_key."'";
                    }
                } elseif ($filter === 'search') {
                    if(is_array($limit)) {
                        $sql    =   "SELECT * FROM enem_user WHERE ".$field_target." LIKE '%".$filter_key."%' LIMIT ".$limit['startLimit'].",".$limit['limitData']."";
                    } else {
                        $sql    =   "SELECT * FROM enem_user WHERE ".$field_target." LIKE '%".$filter_key."%'";
                    }
                } elseif ($filter === 'username') {
                    if(is_array($limit)) {
                        $sql    =   "SELECT * FROM enem_user WHERE username='".$filter_key."' LIMIT ".$limit['startLimit'].",".$limit['limitData']."";
                    } else {
                        $sql    =   "SELECT * FROM enem_user WHERE username='".$filter_key."'";
                    }
                } elseif ($filter === 'create_sql') {
                    if(is_array($limit)) {
                        $sql    =   "SELECT * FROM enem_user WHERE ".$filter_key." LIMIT ".$limit['startLimit'].",".$limit['limitData']."";
                    } else {
                        $sql    =   "SELECT * FROM enem_user WHERE ".$filter_key."";
                    }
                }
            } else {
                if(is_array($limit)) {
                    $sql    =   "SELECT * FROM enem_user LIMIT ".$limit['startLimit'].",".$limit['limitData']."";
                } else {
                    $sql    =   "SELECT * FROM enem_user";
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

        function updateDataUser($data, $findBy = 'user_id', $findByValue = '') {
            $name = "name='".$data['name']."'";
            $nik = "nik='".$data['nik']."'";
            $username = "username='".$data['username']."'";
            $password = "password='".$data['password']."'";
            $email = "email='".$data['email']."'";
            $user_role = "user_role='".$data['user_role']."'";

            $query = [];
            foreach ($data as $key => $value) {
                if ($value) {
                    array_push($query, ${$key});
                }
            }

            $queryResult = implode(',', $query);

            $sql    =   "UPDATE enem_user SET ".$queryResult.", date_update=now() WHERE ".$findBy."='".$findByValue."'";
            $this->db->query($sql);
        }

        function deleteDataUser($field_name, $field_value) {
            $sql    =   "DELETE FROM enem_user WHERE ".$field_name." = '".$field_value."'";
            $this->db->query($sql);
        }
    }
?>