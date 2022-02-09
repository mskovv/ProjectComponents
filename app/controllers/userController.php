<?php
namespace App\Controllers;

use App\functions\Func;
use App\model\QueryBuilder;
use App\Model\userModel;
use Delight\Auth\Auth;
use Delight\Auth\AuthError;
use Delight\Auth\InvalidEmailException;
use Delight\Auth\InvalidPasswordException;
use Delight\Auth\TooManyRequestsException;
use Delight\Auth\UnknownIdException;
use Delight\Auth\UserAlreadyExistsException;
use League\Plates\Engine;
use PDO;

class userController
{
    private $auth;
    private $db;
    private $templates;
    private $queryBuilder;
    private $userModel;
    
    public function __construct(PDO $db, Auth $auth, Engine $templates, QueryBuilder $queryBuilder, userModel $userModel)
    {
        $this->db = $db;
        $this->auth = $auth;
        $this->templates = $templates;
        $this->queryBuilder = $queryBuilder;
        $this->userModel = $userModel;
    }
    
//    ADD USER FROM ADMIN FROM ON USERS PAGE
    public function createUser(){
        $this->is_not_logged();
        try {
            $this->userModel->registerUser();
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
        $id = $_GET['id'];
        $this->queryBuilder->update('users_information', $_POST, $id);
        Func::redirect_to('users');
        flash()->success('Профиль успешно обновлен');

    }
    
    public function editSecurity(){
        $this->is_not_logged();
        $this->userModel->editSecurity();
        Func::redirect_to('users');
        flash()->success('Профиль успешно обновлен');
    }


    public function deleteUser(){
        $this->is_not_logged();
        if($this->auth->hasRole(1)){
            $this->auth->admin()->deleteUserById($_GET['id']);
            $this->queryBuilder->delete('users_information', $_GET['id']);
            Func::redirect_to('users');
            flash()->success('Профиль успешно удалён!');
        }else{
            $this->auth->admin()->deleteUserById($_GET['id']);
            $this->queryBuilder->delete('users_information', $_GET['id']);
            $this->auth->logOut();
            Func::redirect_to('login');
            flash()->success('Профиль успешно удалён!');
        }
    }
    
    public function statusSet(){
        $this->is_not_logged();
        $this->queryBuilder->update('users_information',$_POST, $_GET['id']);
        Func::redirect_to('users');
        flash()->success('Статус обновлен');
    }
    
    public function is_not_logged(){
        if(!$this->auth->isLoggedIn() === true){
            Func::redirect_to('login');
            return true;
        }else{
            return false;
        }
    }
    
    public function mediaUpdate(){
        $user = $this->queryBuilder->getOne('users_information', $_GET['id']);
        $pathAvatar = pathinfo($_FILES['avatar']['name']);
        $filename = uniqid() . "." .$pathAvatar['extension'];
        if(move_uploaded_file($_FILES['avatar']['tmp_name'], "../app/uploads/$filename")){
            unlink("../app/uploads/" .$user['avatar']);
            $this->queryBuilder->update('users_information', ['avatar' => $filename ], $_GET['id']);
        }
        Func::redirect_to('users');
        flash()->success('Аватар обновлен');
    
    }
    
}
