<?php


namespace App\Controllers;


use App\functions\Func;
use Delight\Auth\AuthError;
use Delight\Auth\InvalidEmailException;
use Delight\Auth\InvalidPasswordException;
use Delight\Auth\TooManyRequestsException;
use Delight\Auth\UnknownIdException;
use Delight\Auth\UserAlreadyExistsException;

class UsersControllers extends AbstractController
{
    //    ADD USER FROM ADMIN FROM ON USERS PAGE
    public function createUser(){
        $this->is_not_logged();
        try {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $this->userModel->registerUser($email, $password);
            Func::redirect_to('users');
            flash()->success("Регистрация прошла успешно");
        }
        catch (InvalidEmailException $e) {
            Func::redirect_to('createUser');
            flash()->error("Неподходящий формат эл.адреса");
        }
        catch (InvalidPasswordException $e) {
            Func::redirect_to('createUser');
            flash()->error("Неподходящий формат пароля");
        }
        catch (UserAlreadyExistsException $e) {
            Func::redirect_to('createUser');
            flash()->error("Пользователь уже зарегистрирован");
        }
        catch (TooManyRequestsException $e) {
            Func::redirect_to('createUser');
            flash()->error("Слишком много запросов на регистрацию");
        } catch (UnknownIdException $e) {
            Func::redirect_to('createUser');
            flash()->error("Неизвестная ошибка: обратитесь в службу поддержки");
        } catch (AuthError $e) {
            Func::redirect_to('createUser');
            flash()->error("AuthError: обратитесь в службу поддержки");
        }
    }
    
    public function updateUserInfo(){
        $this->is_not_logged();
        $this->queryBuilder->update('users_information', $_POST, $_GET['id']);
        Func::redirect_to('users');
        flash()->success('Профиль успешно обновлен');
        
    }
    
    public function editSecurity(){
        $this->is_not_logged();
        $this->userModel->editSecurity('users',$_GET['id']);
        Func::redirect_to('users');
        flash()->success('Профиль успешно обновлен');
    }
    
    public function deleteUser(){
        $this->is_not_logged();
        if($this->auth->hasRole(1)){
            $this->userModel->deleteUser($_GET['id']);
            Func::redirect_to('users');
            flash()->success('Профиль успешно удалён!');
        }else{
            $this->userModel->deleteUser($_GET['id']);
            $this->auth->logOut();
            Func::redirect_to('login');
            flash()->success('Профиль успешно удалён!');
        }
    }
    
    public function setStatus(){
        $this->is_not_logged();
        $this->queryBuilder->update('users_information',$_POST, $_GET['id']);
        Func::redirect_to('users');
        flash()->success('Статус обновлен');
    }
    
    
    public function updateMedia(){
        $this->userModel->addUserAvatar($_GET['id']);
        Func::redirect_to('profile?id='.$_GET['id']);
        flash()->success('Аватар обновлен');
        
    }
}