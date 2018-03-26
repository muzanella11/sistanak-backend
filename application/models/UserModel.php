<?php

/**
 * @author f1108k
 * @copyright 2018
 */



?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    class UserModel extends MY_Model {
        private $tableName = 'enem_user';

        function __construct(){
            parent::__construct();
        }

        function addDataUser ($data) {
            $sql    =   "INSERT INTO {$this->tableName} (name, nik, username, password, email, user_role, date_created)
                            VALUES('".$data['name']."', '".$data['nik']."', '".$data['username']."', '".$data['password']."', '".$data['email']."', '".$data['user_role']."', now())";
            
            $query  =   $this->db->query($sql);

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

        function getDataUser ($filter = NULL, $filter_key = NULL, $limit = NULL, $field_target = NULL) {
            if(!empty($filter) && !empty($filter_key)) {
                if($filter === 'id') {
                    if(is_array($limit)) {
                        $sql    =   "SELECT * FROM {$this->tableName} WHERE user_id='".$filter_key."' LIMIT ".$limit['startLimit'].",".$limit['limitData']."";
                    } else {
                        $sql    =   "SELECT * FROM {$this->tableName} WHERE user_id='".$filter_key."'";
                    }
                } elseif ($filter === 'search') {
                    if(is_array($limit)) {
                        $sql    =   "SELECT * FROM {$this->tableName} WHERE ".$field_target." LIKE '%".$filter_key."%' LIMIT ".$limit['startLimit'].",".$limit['limitData']."";
                    } else {
                        $sql    =   "SELECT * FROM {$this->tableName} WHERE ".$field_target." LIKE '%".$filter_key."%'";
                    }
                } elseif ($filter === 'username') {
                    if(is_array($limit)) {
                        $sql    =   "SELECT * FROM {$this->tableName} WHERE username='".$filter_key."' LIMIT ".$limit['startLimit'].",".$limit['limitData']."";
                    } else {
                        $sql    =   "SELECT * FROM {$this->tableName} WHERE username='".$filter_key."'";
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
                return [
                    'flag' => 1,
                    'messages' => 'Gagal menambahkan data'
                ];
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

        function deleteDataUser($field_name, $field_value) {
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