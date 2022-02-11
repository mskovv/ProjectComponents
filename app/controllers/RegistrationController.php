<?php
namespace App\Controllers;


use App\functions\Func;
use Delight\Auth\AuthError;
use Delight\Auth\InvalidEmailException;
use Delight\Auth\InvalidPasswordException;
use Delight\Auth\TooManyRequestsException;
use Delight\Auth\UnknownIdException;
use Delight\Auth\UserAlreadyExistsException;

class RegistrationController extends  AbstractController
{
    
    //VIEW PAGE REGISTRATION
    public function register(){
        echo $this->templates->render('page_register');
    }

//    ADD USER FROM FORM REGISTER ON START PAGE
    public function registerUser(){
        $this->is_not_logged();
        try {
            $this->userModel->registerUser();
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
}