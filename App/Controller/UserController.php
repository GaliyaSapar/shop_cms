<?php


namespace App\Controller;


use App\Http\Response;
use App\Model\User;
use App\Repository\UserRepository;
use App\Service\RequestService;
use App\Service\UserService;

class UserController extends ControllerAbstract
{
    /**
     * @param UserRepository $user_repository
     * @param UserService $user_service
     *
     * @Route(url="/user/login")
     *
     * @return Response
     */
    public function login(UserRepository $user_repository, UserService $user_service) {
        $login = $this->request->getStringFromPost('login');
        $pass = $this->request->getStringFromPost('pass');

        /**
         * @var $user User
         */
        $user = $user_repository->findByName($login);

        $error_msg = 'User not found or data is incorrect';

        if (is_null($user)) {
            echo $error_msg;
            exit;
        }

        $pass = $user_service->generatePassHash($pass);

        if ($user->getPass() !== $pass) {
            echo $error_msg;
            exit;
        }

        $_SESSION['user_id'] = $user->getId();

        return $this->redirect('/');
    }

    /**
     * @return Response
     * @Route(url="/user/logout")
     */
    public function logout() {
        unset($_SESSION['user_id']);
        return $this->redirect('/');
    }

    public static function edit() {
        $user = user();

        smarty()->assign_by_ref('user', $user);
        smarty()->display('user/edit.tpl');
    }

    public static function editing() {

        $user = user();

        $name = RequestService::getStringFromPost('name');
        $email = RequestService::getStringFromPost('email');
        $password = RequestService::getStringFromPost('password');
        $password_repeat = RequestService::getStringFromPost('password_repeat');

        if ($password !== $password_repeat) {
            die('Passwords mismatch');
        }

        $is_email_exist = UserService::isValueExist('email', $email);
        $is_login_exist = UserService::isValueExist('name', $name);

        if($user->getId()) {
            $current_login = $user->getName();
            $current_email = $user->getEmail();

            if (($current_login !== $name) && $is_login_exist) {
                die('login is busy!');
            }
            if (($current_email !== $email) && $is_email_exist) {
                die('email is busy!');
            }

        } else {
            if ($is_login_exist) {
                die('login is busy!');
            }
            if ($is_email_exist) {
                die('email is busy!');
            }
        }

        $password = UserService::generatePassHash($password);

        $user->setEmail($email);
        $user->setName($name);
        $user->setPass($password);

        if (!$user->getId()) {
            mail($email, 'Вы успешно зарегистрировались', 'Вы зарегистрировались как: ' . $name);
        }

        UserService::save($user);

        RequestService::redirect('/');
    }
}