<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Register extends Controller
{
    public function process()
    {
        $AuthUser               = $this->getVariable("AuthUser");
        $Route                  = $this->getVariable("Route");
        $Settings               = $this->getVariable("Settings");    
        $isValid                = $this->getVariable("isValid"); 
 

        if(Input::cleaner($_POST['_ACTION']) == 'register' AND $isValid) {
            $this->check();
        } elseif ($AuthUser['id'] and \Delight\Cookie\Cookie::exists('Auth')) {
            header("location: " . APP);
        }
        $Config['title']        = __('Register').' - '.get($Settings, "data.title", "general");
        $Config['description']  = get($Settings, "data.description", "general"); 
        $Config['url']          = APP.'/kaydol'; 
        $this->setVariable("Config", $Config);  
        $this->view('register', 'app');
    }

    public function check()
    {
        $Email      = Input::cleaner($_POST['email']);
        $Password   = Input::cleaner($_POST['password']);
        $Name       = Input::cleaner($_POST['name']);
        $Username   = Input::cleaner($_POST['username']);
        if (!$Email || !$Password || !$Name || !$Username) {
            $Notify['type'] = 'warning';
            $Notify['text'] = __('Fill in all fields'); 
        }
        if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
            $Notify['type'] = 'warning';
            $Notify['text'] = __('Error'); 
        }

        if (empty($Notify)) {
            $EmailCheck      = $this->db->from('users')->where('email',$Email,'=','AND')->first();
            $UsernameCheck      = $this->db->from('users')->where('username',$Username,'=','AND')->first();
            if ($Email == $EmailCheck['email']) {
                $Notify['type']     = 'warning';
                $Notify['text']     = __('Email already registered !'); 
            }
            if ($Username == $UsernameCheck['username']) {
                $Notify['type']     = 'warning';
                $Notify['text']     = __('Username already registered !'); 
            }
            if (mb_strlen($Password) < 6) {
                $Notify['type']     = 'warning';
                $Notify['text']     = __('Password must be at least 6 characters'); 
            }
            if (mb_strlen($Username) < 4) {
                $Notify['type']     = 'warning';
                $Notify['text']     = __('Username must be at least 4 characters'); 
            }
            if (mb_strlen($Name) < 3) {
                $Notify['type']     = 'warning';
                $Notify['text']     = __('Your name must be at least 3 characters'); 
            }
            
            if (empty($Notify)) {
                $dataarray        = array(
                    "account_type"              => 'user',
                    "name"                      => Input::cleaner($_POST['name']),
                    "username"                  => Input::cleaner($_POST['username']),
                    "email"                     => Input::cleaner($_POST['email']),
                    "password"                  => Input::cryptor($Password),
                    "created"                   => date("Y-m-d H:i:s")
                );
                $this->db->insert('users')->set($dataarray); 
                $AuthToken = Input::cryptor($Email . $Password . $this->db->lastId());
                setcookie('Auth', $AuthToken, time() + (86400 * 365), "/");
                $this->db->update('users')->where('id', $this->db->lastId(), '=')->set(array('token' => $AuthToken));
                header("location: " . APP);
            }else{
                $this->notify($Notify);
            }
        } else {
            $this->notify($Notify);
        }
        return $this;
    }
}
