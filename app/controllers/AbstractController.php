<?php


namespace App\Controllers;


use App\functions\Func;
use App\Model\QueryBuilder;
use App\Model\UserModel;
use Delight\Auth\Auth;
use League\Plates\Engine;

class AbstractController
{
    protected $auth;
    protected $templates;
    protected $queryBuilder;
    protected $userModel;
    
    public function __construct(
            Auth $auth,
            Engine $templates,
            QueryBuilder $queryBuilder,
            userModel $userModel)
    {
        
        $this->auth = $auth;
        $this->templates = $templates;
        $this->queryBuilder = $queryBuilder;
        $this->userModel = $userModel;
    }
    
    //Check user for not logining, if him not logged -> redirect to login page
    public function is_not_logged(){
        if(!$this->auth->isLoggedIn() === true){
            Func::redirect_to('login');
            return true;
        }else{
            return false;
        }
    }
    
    //    LOGOUT AND REDIRECT TO LOGIN PAGE
    public function logout(){
        $this->auth->logOut();
        Func::redirect_to('login');
    }
    

}