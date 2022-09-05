<?php

namespace Auth;

use database\DataBase;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Auth
{

    protected function redirect($url)
    {
        header('Location: ' . trim(CURRENT_DOMAIN, '/ ') . '/' . trim($url, '/ '));
        exit;
    }

    protected function redirectBack()
    {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    private function hash($password)
    {
        $hashPassword = password_hash($password, PASSWORD_DEFAULT);
        return $hashPassword;
    }

    private function random()
    {
        return bin2hex(openssl_random_pseudo_bytes(32));
    }

    public function activationMassage($username , $verifyToken)
    {
        $massage = '
            <h1>فعال سازی حساب کاربری</h1>
            <p> '. $username .'  لطفا روی لینک زیر کلیک نمایید </p>
            <div><a href="'. url('activation/' . $verifyToken).'">لینک فعال سازی</a></div>
            ';
        

        return $massage;
    }


    private function sendEmail($emailAddress, $subject, $body)
    {
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->CharSet = 'UTF-8';                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = MAIL_HOST;                     //Set the SMTP server to send through
            $mail->SMTPAuth   = MAIL_AUTH;                                   //Enable SMTP authentication
            $mail->Username   = MAIL_USERNAME;                     //SMTP username
            $mail->Password   = MAIL_PASSWORD;                               //SMTP password
            $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
            $mail->Port       = MAIL_PORT;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom(SENDER_MAIL);
            $mail->addAddress($emailAddress);     //Add a recipient
            

            
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $result = $mail->send();
            return true;
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return false;
        }
    }

    public function register()
    {
        require_once(BASE_PATH . '/template/auth/register.php');
    }

    public function registerStore($request)
    {
        if(empty($request['email']) || empty($request['username']) || empty($request['password'])){
            flash('register_error' , 'فیلد های زیر را کامل کنید');
            $this->redirectBack();
        } 
        else if(strlen($request['password']) < 8)
        {
            flash('register_error' , 'رمز عبور باید حداقل 8 کارکتر باشد');
            $this->redirectBack();
        } 
        elseif(!filter_var($request['email'] , FILTER_VALIDATE_EMAIL) )
        {
            flash('register_error' , 'ایمیل وارد شده معتبر نمیباشد');
            $this->redirectBack();
        } 
        else
        {
            $db = new DataBase();
            $user = $db->select("SELECT * FROM users WHERE email = ?" , [$request['email']])-> fetch();

            if($user != null)
            {
                flash('register_error' , 'کاربر از قبل وجود دارد');
                $this->redirectBack();
            }
            else
            {
                $randomToken = $this->random();
                $activationToken = $this->activationMassage($request['username'] , $randomToken);
                $result = $this->sendEmail($request['email'] , 'لینک فعال سازی حساب کاربری' , $activationToken);
                
                if($result)
                {
                    
                    $request['verify_token'] = $randomToken;
                    $request['password'] = $this->hash($request['password']);
                    $db->insert('users' , array_keys($request) , $request);
                    $this->redirect('login');
                }
                else
                {
                    flash('register_error' , 'ارسال ایمیل با خطا مواجه شد');
                    $this->redirectBack();
                }
            }
        }
    }

    public function activation($verifyToken)
    {
        $db = new DataBase();
        $user = $db->select("SELECT * FROM users WHERE verify_token = ? AND is_active = 0;" , [$verifyToken])->fetch();

        if($user == null)
        {
            $this->redirect('login');
        }
        else
        {
            $result = $db->update('users' , $user['id'] , ['is_active'] , [1]);
            $this->redirect('login');
        }
    }


    public function login()
    {
        require_once(BASE_PATH . '/template/auth/login.php');
    }

    public function checkLogin($request)
    {
        if(empty($request['email']) || empty($request['password']))
        {
            flash('login_error' , 'تمامی فیلد ها را پر کنید');
            $this->redirectBack();
        }
        else
        {
            $db = new DataBase();
            $user = $db->select('SELECT * FROM users WHERE email = ?', [$request['email']])->fetch();

            if($user != null)
            {
                if(password_verify($request['password'] , $user['password']) && $user['is_active'] == 1 )   
                {
                    $_SESSION['user'] = $user['id'];
                    $this->redirect('admin');
                }
                else{
                    flash('login_error' , 'نام کاربری یا رمز عبور اشتباه است');
                    $this->redirectBack();
                }
            }
            else
            {
                flash('login_error' , 'کاربر وجود ندارد');
                $this->redirectBack();
            }
        }
    }

    public function checkAdmin()
    {
        if (isset($_SESSION['user']))
        {
            $db = new DataBase();
            $user = $db->select("SELECT * FROM users WHERE id = ?;" , [$_SESSION['user']] )->fetch();

            if($user != null)
            {
                if($user['permission'] != 'admin')
                {
                    $this->redirect('home');
                }
            }
            else
            {
                $this->redirect('home');
            }
        }
        else
        {
            $this->redirect('home');
        }
    }

    public function logout ()
    {
        if(isset($_SESSION['user']))
        {
            session_unset();
            session_destroy();
        }
        $this->redirect('home');
    }

    public function forgot()
    {
        require_once(BASE_PATH . '/template/auth/forgot.php');
    }


    public function forgotMassage($username , $forgotToken)
    {
        $massage = '
            <h1>فراموشی رمز عبور</h1>
            <p> '. $username .'  لطفا روی لینک زیر کلیک نمایید </p>
            <div><a href="'. url('reset-password-form/' . $forgotToken).'">لینک فعال سازی</a></div>
            ';
        return $massage;
    }

    public function forgotRequest($request)
    {
        if(empty($request['email']))
        {
            flash('forgot_error' , 'ایمیل الزامی میباشد');
            $this->redirectBack();
        }
        elseif(!filter_var($request['email'] , FILTER_VALIDATE_EMAIL))
        {
            flash('forgot_error' , 'ایمیل صحیح نمیباشد');
            $this->redirectBack();
        }
        else
        {
            $db = new DataBase();
            $user = $db->select("SELECT * FROM users WHERE email = ?;" , [$request['email']])->fetch();
            if($user == null)
            {
                flash('forgot_error' , 'کاربر موجود نمیباشد');
                $this->redirectBack();
            }
            else
            {
                $randomToken = $this->random();
                $forgotToken = $this->forgotMassage($user['username'] , $randomToken);
                $result = $this->sendEmail($request['email'] , 'فراموشی رمز عبور' , $forgotToken);

                if($result)
                {
                    date_default_timezone_set('Asia/Tehran');
                    $db->update("users" , $user['id'] , ['forgot_token' , 'forgot_token_expire'] , [$randomToken ,
                     date('Y-m-d H:i:s' , strtotime('+15 minutes'))]);

                     $this->redirect('login');
                }
                else
                {
                    flash('forgot_error' , 'ایمیل ارسال نشد');
                    $this->redirectBack();  
                }
            }
        }
    }

    public function resetPasswordView($forgot_token)
    {
        require_once(BASE_PATH . '/template/auth/reset-password.php');
    }

    public function resetPassword($request , $forgot_token)
    {
        if(!isset($request['password']) || strlen($request['password']) < 8)
        {
            flash('reset_error' , 'رمز عبور باید بیشتر از 8 کارکتر باشد');
            $this->redirectBack();  
        }
        else
        {
            $db = new DataBase();
            $user = $db->select("SELECT * FROM users WHERE forgot_token = ?;" , [$forgot_token])->fetch();
            if($user == null)
            {
                flash('reset_error' , 'کاربر یافت نشد!');
                $this->redirectBack();
            }
            else
            {
                date_default_timezone_set('Asia/Tehran');
                if($user['forgot_token_expire'] < date('Y-m-d H:i:s'))
                {
                    flash('reset_error' , 'توکن ارسالی منقضی شده است');
                    $this->redirectBack();
                }
                if($user)
                {
                    $db->update("users" , $user['id'] , ['password'] , [$this->hash($request['password'])]);
                    $this->redirect('login');
                }
                else
                {
                    flash('reset_error' , 'کاربر یافت نشد');
                    $this->redirectBack();
                }
            }
        }
    }
}
