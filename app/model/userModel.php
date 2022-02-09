<?php
namespace App\Model;

//use Delight\Auth;
use Delight\Auth\Auth;

class userModel
{
    private $auth;
    private $queryBuilder;
    
    
    public function __construct(Auth $auth, QueryBuilder $queryBuilder)
    {
        $this->auth = $auth;
        $this->queryBuilder = $queryBuilder;
    }
    
    public function registerUser(){
        if($userId = $this->auth->register($_POST['email'], $_POST['password'])){
            $data = [
//                'userId' => $userId,
                'username' => $_POST['username'],
                'job_title' => $_POST['job_title'],
                'phone' => $_POST['phone'],
                'address' => $_POST['address'],
                'contactEmail' => $_POST['contactEmail'],
                ];
            $this->queryBuilder->insert('users_information', $data);//Reg user
        }
    }
    
    public function editSecurity(){
        $user = $this->queryBuilder->getOne('users', $_GET['id']);
        if($user['email'] === $_POST['email'] && $this->auth->hasRole(1)){
        $this->auth->admin()->changePasswordForUserById($_GET['id'], $_POST['password']);
        }else if($this->auth->isLoggedIn() && $user['email'] === $_POST['email']){
            $this->auth->changePasswordWithoutOldPassword($_POST['password']);
        }else{
            $data = ['email' => $_POST['email'],
                    "password" => password_hash($_POST['password'], PASSWORD_DEFAULT)];
            $this->queryBuilder->update('users', $data, $_GET['id']);
        }
    }
    
    public function deleteUser(){
    
    }
    
//    public function addUserInfo(){
//        $id = $this->auth->getUserId() ?? $_GET['id'];
//        d($id);
//        $this->queryBuilder->update('users_info', $_POST, $id);
//    }
    
    
}