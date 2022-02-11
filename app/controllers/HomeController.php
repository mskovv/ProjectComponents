<?php

namespace App\Controllers;

use App\functions\Func;
use Delight\Auth\EmailNotVerifiedException;
use Delight\Auth\InvalidEmailException;
use Delight\Auth\InvalidPasswordException;
use Delight\Auth\TooManyRequestsException;

class HomeController extends AbstractController
{
    
    //  VIEW PAGE LOGIN
    public function loginView(){
        if($this->auth->isLoggedIn()){
            Func::redirect_to('users');
        }else{
            echo $this->templates->render('page_login');
        }
    }
    
//    LOGGING ACTION
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
}
