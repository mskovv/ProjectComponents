<?php

namespace App\Controllers;

use App\functions\Func;
use App\Model\QueryBuilder;
use App\Model\userModel;
use Delight\Auth\Auth;
use Delight\Auth\AuthError;
use Delight\Auth\EmailNotVerifiedException;
use Delight\Auth\InvalidEmailException;
use Delight\Auth\InvalidPasswordException;
use Delight\Auth\TooManyRequestsException;
use Delight\Auth\UnknownIdException;
use Delight\Auth\UserAlreadyExistsException;
use League\Plates\Engine;


class pageController
{
    private $auth;
    private $templates;
    private $queryBuilder;
    private $userModel;
    
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
//  VIEW PAGE LOGIN
    public function loginView(){
        if($this->auth->isLoggedIn()){
            Func::redirect_to('users');
        }else{
            echo $this->templates->render('page_login');
        }
    }
    
    public function loginForm(){
        try {
            if($_POST['remember'] == 1){
                $rememberDuration = (int) (60 * 60); //remember user for one hour
            }else{
                $rememberDuration = null;
            }
            $this->auth->login($_POST['email'], $_POST['password'], $rememberDuration);
            Func::redirect_to('users');
        }
        catch (InvalidEmailException $e) {
            Func::redirect_to('login');
            flash()->error('Неправильно введен Email');
        }
        catch (InvalidPasswordException $e) {
            Func::redirect_to('login');
            flash()->error('Неправильно введен пароль');
        }
        catch (EmailNotVerifiedException $e) {
            Func::redirect_to('login');
            flash()->error('Email не подтверждён');
        }
        catch (TooManyRequestsException $e) {
            Func::redirect_to('login');
            flash()->error('Слишком много запросов');
        }
    }
    
    
    //VIEW PAGE REGISTRATION
    public function register(){
        echo $this->templates->render('page_register');
    }
    
    
//    ADD USER FROM FORM REGISTER ON START PAGE
    public function registerUser(){
        $this->is_not_logged();
        try {
//            $user_id = $this->auth->register($_POST['email'], $_POST['password']);//Reg user
            $user_id = $this->userModel->registerUser();
//            $this->queryBuilder->insert('users_information',['id' => $user_id]);
            Func::redirect_to('login');
            flash()->success("Регистрация прошла успешно");
        }
        catch (InvalidEmailException $e) {
            Func::redirect_to('register');
            flash()->error("Неподходящий формат эл.адреса");
        }
        catch (InvalidPasswordException $e) {
            Func::redirect_to('register');
            flash()->error("Неподходящий формат пароля");
        }
        catch (UserAlreadyExistsException $e) {
            Func::redirect_to('register');
            flash()->error("Пользователь уже зарегистрирован");
        }
        catch (TooManyRequestsException $e) {
            Func::redirect_to('register');
            flash()->error("Слишком много запросов на регистрацию");
        } catch (UnknownIdException $e) {
            Func::redirect_to('register');
            flash()->error("Неизвестная ошибка: обратитесь в службу поддержки");
        } catch (AuthError $e) {
            Func::redirect_to('register');
            flash()->error("AuthError: обратитесь в службу поддержки");
        }

    }
    
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
    
//    LOGOUT AND REDIRECT TO LOGIN PAGE
    public function logout(){
        $this->auth->logOut();
        Func::redirect_to('login');
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