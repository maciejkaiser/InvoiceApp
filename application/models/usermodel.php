<?php
class Usermodel extends CI_Model 
{

    function __construct()
    {
        parent::__construct();
    }

    /**
    * login
    */
    function login($name,$password)
    {

        if($name != "" && $password != ""){
            $result = $this->checkIfUserExists($name, $password);
            if($result){
                foreach($result->result() as $rows)
                {
                    $newdata = array(
                        'user_id'  => $rows->user_id,
                        'user_name'  => $rows->user_name,
                        'logged_in'  => TRUE,
                        );
                }
                $this->session->set_userdata($newdata);
                return true;
            }
            return false;
        }
        
    }

    /**
    * register
    */
    function register($data){
        return $this->add_user($data);
    }
    /**
    * delete
    */
    function delete(){
        return $this->delete_user($this->session->userdata('user_id'));
    }
    /**
    * checkIfUserExists
    */
    private function checkIfUserExists($username, $password){
        if($username != "" && $password != "" ){
            $this->db->where("user_name",$username);
            $this->db->where("user_password",$password);
            $query=$this->db->get("user");

            if($query->num_rows()>0){
                return $query;
            }else{ 
                return false;
            }
        }else{
            return "Username or Password is null";
        }
    }
    /**
    * add_user
    */
    private function add_user($data)
    {
       if(!empty($data) && is_array($data)){
        $result = $this->checkIfUserExists($data['user_name'], $data['user_password']);
        if($result === false){
            $this->db->insert('user',$data);
            return true;
        }else{
            return false;
        }
        
       }
    }
    /**
    * delete_user
    */
    private function delete_user($user_id)
    {
       if($user_id){
        $this->db->where('user_id', $user_id);
        $result = $this->db->delete('user'); 

        if($result){
            return true;
        }else{
            return false;
        }
        
       }
    }


}
?>