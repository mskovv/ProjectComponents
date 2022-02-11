<?php
namespace App\Model;

use App\functions\Func;
use Delight\Auth\Auth;

class UserModel
{
    private $auth;
    private $queryBuilder;
    
    
    public function __construct(Auth $auth, QueryBuilder $queryBuilder)
    {
        $this->auth = $auth;
        $this->queryBuilder = $queryBuilder;
    }
    
    public function registerUser($email, $password){
        if($userId = $this->auth->register($email, $password)){
            $data = [
                'username' => $_POST['username'],
                'job_title' => $_POST['job_title'],
                'phone' => $_POST['phone'],
                'address' => $_POST['address'],
                'contactEmail' => $_POST['contactEmail'],
                ];
            $this->queryBuilder->insert('users_information', $data);//Reg user
            $this->addUserAvatar($userId);
        }
        
    }
    
    public function editSecurity($table,$id){
        $user = $this->queryBuilder->getOne($table, $id);
        if($user['email'] === $_POST['email'] && $this->auth->hasRole(1)){
        $this->auth->admin()->changePasswordForUserById($id, $_POST['password']);
        }else if($this->auth->isLoggedIn() && $user['email'] === $_POST['email']){
            $this->auth->changePasswordWithoutOldPassword($_POST['password']);
        }else{
            $data = ['email' => $_POST['email'],
                    "password" => password_hash($_POST['password'], PASSWORD_DEFAULT)];
            $this->queryBuilder->update($table, $data, $id);
        }
    }
    
    public function addUserAvatar($id){
        if($this->haveUserAvatar($id)){
            $user = $this->getOneUser($id);
            unlink("../app/uploads/" .$user['avatar']);
        }
        $pathAvatar = pathinfo($_FILES['avatar']['name']);// take avatar name
        $filename = uniqid() . "." .$pathAvatar['extension'];//new uniq filename
        if(move_uploaded_file($_FILES['avatar']['tmp_name'], "../app/uploads/$filename")){
            $this->queryBuilder->update('users_information', ['avatar' => $filename ], $id);//add avatar name in database
        }
    }
    
    public function haveUserAvatar($id){
        $user = $this->getOneUser($id);
        if(isset($user['avatar'])){
            return true;
        }else{
            return false;
        }
    }
    
    public function deleteAvatar($id){
        if($this->haveUserAvatar($id)){
            $user = $this->getOneUser($id);
            unlink("../app/uploads/" .$user['avatar']);
        }
    }
    
    public function deleteUser($id){
            $this->deleteAvatar($id);
            $this->auth->admin()->deleteUserById($id);
            $this->queryBuilder->delete('users_information', $id);
    }
    
    public function getOneUser($id){
        return $user = $this->queryBuilder->getOne('users_information', $id);
    }

    
}