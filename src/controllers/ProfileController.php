<?php
namespace src\controllers;

use \core\Controller;
use \src\handlers\UserHandler;
use \src\handlers\PostHandler;

class ProfileController extends Controller {

    private $loggedUser;

    public function __construct() {
        $this->loggedUser = UserHandler::checkLogin();
        if($this->loggedUser === false) {
            $this->redirect('/login');
        }
    }

    public function index($atts = []) {
        $page = intval(filter_input(INPUT_GET, 'page'));

        //Detectando o usuário acessado
        $id = $this->loggedUser->id;
        if(!empty($atts['id'])) {
            $id = $atts['id'];
        }

        // Pegando informações do usuário
        $user = UserHandler::getUser($id, true);

        if(!$user) {
            $this->redirect('/');
        }

        $dateFrom = new \DateTime($user->birthdate);
        $dateTo = new \DateTime('today');
        $user->ageYears = $dateFrom->diff($dateTo)->y;

        // Pegando o feed do usuário
        $feed = PostHandler::getUserFeed(
            $id, 
            $page, 
            $this->loggedUser->id
        );

        // Verificar se EU sigo o usuário
        $isFollowing = false;
        if($user->id != $this->loggedUser->id) {
            $isFollowing = UserHandler::isFollowing($this->loggedUser->id, $user->id);
        }

        $this->render('profile', [
            'loggedUser' => $this->loggedUser,
            'user' => $user,
            'feed' => $feed,
            'isFollowing' => $isFollowing
        ]);
    }

    public function follow($atts) {
        $to = intval($atts['id']);
        
        if(UserHandler::idExists($to)) {
            if(UserHandler::isFollowing($this->loggedUser->id, $to)) {
                // desseguir
                UserHandler::unfollow($this->loggedUser->id, $to);
            } else {
                // seguir
                UserHandler::follow($this->loggedUser->id, $to);
            }
        }
        $this->redirect('/perfil/'.$to);
    }

    public function friends($atts = []) {
        $id = $this->loggedUser->id;

        if(!empty($atts['id'])) {
            $id = intval($atts['id']);
        }

        $tab_item = '';
        if(!empty($atts['tb'])) {
            $tab_item = $atts['tb'];
        }
        
         // Pegando informações do usuário
        $user = UserHandler::getUser($id, true);

        if(!$user) {
             $this->redirect('/');
         }
 
        $dateFrom = new \DateTime($user->birthdate);
        $dateTo = new \DateTime('today');
        $user->ageYears = $dateFrom->diff($dateTo)->y;

        // Verificar se EU sigo o usuário
        $isFollowing = false;
        if($user->id != $this->loggedUser->id) {
            $isFollowing = UserHandler::isFollowing($this->loggedUser->id, $user->id);
        }

        $this->render('profile_friends', [
            'loggedUser' => $this->loggedUser,
            'user' => $user,
            'isFollowing' => $isFollowing,
            'tab_item' => $tab_item
        ]);
    }

    public function photos($atts = []) {
        $id = $this->loggedUser->id;

        if(!empty($atts['id'])) {
            $id = intval($atts['id']);
        }

        // Pegando informações do usuário
        $user = UserHandler::getUser($id, true);

        if(!$user) {
             $this->redirect('/');
         }
          
        $dateFrom = new \DateTime($user->birthdate);
        $dateTo = new \DateTime('today');
        $user->ageYears = $dateFrom->diff($dateTo)->y;

        // Verificar se EU sigo o usuário
        $isFollowing = false;
        if($user->id != $this->loggedUser->id) {
            $isFollowing = UserHandler::isFollowing($this->loggedUser->id, $user->id);
        }

        $this->render('profile_photos', [
            'loggedUser' => $this->loggedUser,
            'user' => $user,
            'isFollowing' => $isFollowing
        ]);
    }

    public function settings() {
        $id = $this->loggedUser->id;

        $user = UserHandler::getUser($id);

        $flash = '';
        if(!empty($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            $_SESSION['flash'] = '';
        }

        $this->render('profile_settings', [
            'loggedUser' => $user,
            'flash' => $flash
        ]);
    }

    public function settingsAction() {
        $name = filter_input(INPUT_POST, 'name');
        $birthdate = filter_input(INPUT_POST, 'birthdate');
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $city = filter_input(INPUT_POST, 'city');
        $work = filter_input(INPUT_POST, 'work');
        $new_password = filter_input(INPUT_POST, 'new_password');
        $new_password_confirm = filter_input(INPUT_POST, 'new_password_confirm');
        
        if(!empty($name)) {
            UserHandler::editUserData('name', $name, $this->loggedUser->id);
        }

        if($birthdate) {
            $birthdate = explode('/', $birthdate);
            if(count($birthdate) != 3) {
                $_SESSION['flash'] = 'Data de nascimento inválida';
            } else {
                $birthdate = $birthdate[2].'-'.$birthdate[1].'-'.$birthdate[0];

                if(strtotime($birthdate) === false) {
                    $_SESSION['flash'] = 'Data de nascimento inválida'; 
                } else {
                    UserHandler::editUserData('birthdate', $birthdate, $this->loggedUser->id);
                }
            }
        }
        
        if($email) {
            if(UserHandler::emailExists($email) === false) {
                UserHandler::editUserData('email', $email, $this->loggedUser->id);
            } else if($this->loggedUser->email != $email) {
                $_SESSION['flash'] = 'E-mail já cadastrado!';
            }
        }

        if(!empty($city)) {
            UserHandler::editUserData('city', $city, $this->loggedUser->id);
        }

        if(!empty($work)) {
            UserHandler::editUserData('work', $work, $this->loggedUser->id);
        }

        if(!empty($new_password) || !empty($new_password_confirm)) {
            if($new_password === $new_password_confirm) {
                UserHandler::editUserData('password', $new_password, $this->loggedUser->id);
            } else {
                $_SESSION['flash'] = 'Senhas não coincidem!';
            }
        }
        //echo 'teste';
        echo print_r($_FILES['avatar']);
        if(isset($_FILES['avatar']) && !empty($_FILES['avatar']['tmp_name'])) {
            echo print_r($_FILES['avatar']);
            $newAvatar = $_FILES['avatar'];

            if(in_array($newAvatar['type'], ['image/jpeg', 'image/jpg', 'image/png'])) {
                $avatarName = $this->cutImage($newAvatar, 200, 200, 'media/avatars');
                UserHandler::editUserData('avatar', $avatarName, $this->loggedUser->id);
            }
        }

        if(isset($_FILES['cover']) && !empty($_FILES['cover']['tmp_name'])) {
            $newCover = $_FILES['cover'];

            if(in_array($newCover['type'], ['image/jpeg', 'image/jpg', 'image/png'])) {
                $coverName = $this->cutImage($newCover, 850, 310, 'media/covers');
                UserHandler::editUserData('cover', $coverName, $this->loggedUser->id);
            }
        }
        //echo 'teste';
        $this->redirect('/config');
    }

    private function cutImage($file, $w, $h, $folder) {
        list($widthOrig, $heightOrig) = getimagesize($file['tmp_name']);
        $ratio = $widthOrig / $heightOrig;

        $newWidth = $w;
        $newHeigth = $newWidth / $ratio;

        if($newHeigth < $h) {
            $newHeigth = $h;
            $newWidth = $newHeigth * $ratio;
        }

        $x = $w - $newWidth;
        $y = $h - $newHeigth;

        $x = $x < 0 ? $x / 2 : $x;
        $y = $y < 0 ? $y / 2 : $y;

        $finalImage = imagecreatetruecolor($w, $h);
        switch($file['type']) {
            case 'image/jpeg':
            case 'image/jpg':
                $image = imagecreatefromjpeg($file['tmp_name']);
            break;
            case 'image/png':
                $image = imagecreatefrompng($file['tmp_name']);
            break;
        }
        imagecopyresampled(
            $finalImage, $image,
            $x, $y, 0, 0,
            $newWidth, $newHeigth, $widthOrig, $heightOrig
        );

        $fileName = md5(time().rand(0, 9999)).'.jpg';

        imagejpeg($finalImage, $folder.'/'.$fileName);

        return $fileName;
    }

}