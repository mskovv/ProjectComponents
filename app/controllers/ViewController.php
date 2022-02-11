<?php


namespace App\Controllers;


class ViewController extends AbstractController
{
    //VIEW PAGE USERS
    public function users(){
        $this->is_not_logged();
        $users = $this->queryBuilder->getAll('users_information');
        $this->templates->registerFunction('canAddUser', function (){
            if($this->auth->hasRole(1)){
                echo '<a class="btn btn-success" href="/ProjectComponents/public/createUser">Добавить</a>';
            }
        });
        $this->templates->registerFunction('canEditUser', function (){
            if($this->auth->hasAnyRole(1,2)){
                return true;
            }
        });
        $currentUserId = $this->auth->getUserId();
        echo $this->templates->render('users',['users' => $users, "currentUserId" => $currentUserId]);
        
    }
    
    //    VIEW PAGE USER EDIT
    public function viewEdit(){
        $this->is_not_logged();
        $id = $_GET['id'];
        $user = $this->queryBuilder->getOne('users_information', $id);
        echo $this->templates->render('edit', ['user' => $user]);
    }
    
    //    VIEW PAGE SECURITY USER EDIT
    public function viewSecurity(){
        $this->is_not_logged();
        $user = $this->queryBuilder->getOne('users', $_GET['id']);
        echo $this->templates->render('security', ['user' => $user]);
    }

//    VIEW PAGE CREATE USER
    public function createUserView(){
        $this->is_not_logged();
        echo $this->templates->render('create_user');
    }
    
    public function statusView(){
        $this->is_not_logged();
        echo $this->templates->render('status');
    }
    
    public function mediaView(){
        $this->is_not_logged();
        $user = $this->queryBuilder->getOne('users_information', $_GET['id']);
        echo $this->templates->render('media', ['user' => $user]);
    }
    
    public function profileView(){
        $this->is_not_logged();
        $user = $this->queryBuilder->getOne('users_information', $_GET['id']);
        echo $this->templates->render('page_profile', ['user' => $user]);
    }
}