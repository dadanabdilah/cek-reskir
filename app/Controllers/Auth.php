<?php

namespace App\Controllers;
use App\Models\AdminModel;

class Auth extends BaseController
{
    function __construct()
    {
        $this->Admin = new AdminModel;
    }

    public function index()
    {
        if($_SERVER["REQUEST_METHOD"] == "POST"){
			$aturan =[
				'username' => 'required',
				'password' => 'required'
			];
			
			if ($this->validate($aturan)) {
				$dataUser = $this->Admin->where('username', $this->request->getPost('username'))->first();
				if (isset($dataUser)) {
					// jika password benar
					if ($dataUser->password == md5($this->request->getPost('password')) ) {
						$session_data = [
							'admin_id'  => $dataUser->admin_id,
							'username'	=> $dataUser->username,
							'role'      => $dataUser->role,
							'sudah_login'  => TRUE
						];
						session()->set($session_data);
						return redirect()->to(site_url('dashboard'));
						
					} else {
						$flash_data = [
							'error' => 'Password salah',
							'username' => $this->request->getPost('username'),
						];
						session()->setFlashdata($flash_data);
						return redirect()->to('/');
					}
				} else {
					$flash_data = [
							'error' => 'Username salah',
						];
					session()->setFlashdata($flash_data);
					return redirect()->to('/');
				}
			}else{
				return redirect()->to('/');
			}
		} else {
			return view('auth/login');
		}
    }

    public function logout()
	{
		session()->destroy();
		return redirect()->to('/');
	}
}
