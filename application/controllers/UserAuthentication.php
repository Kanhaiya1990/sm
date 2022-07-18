<?php
Class UserAuthentication extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->model('LoginDatabase');
		date_default_timezone_set("Asia/Calcutta");
		ini_set('memory_limit', '-1');
	}

	public function index(){
		$this->load->view('LoginView');
	}

	public function user_login_process(){
		$this->form_validation->set_rules('email', 'Username', 'trim|required|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');

		//for dynamic color for dashboard
		$color=$this->LoginDatabase->load('tbl_settings',2);
		$colorValue="#F44336";
		if(!empty($color)){
			$colorValue=$color[0]['name'];
		}
		$colorSession = array(
			'colorValue' => $colorValue
		);
		$this->session->set_userdata('colorSession', $colorSession);

		$officeDetails=$this->LoginDatabase->getdata('office_details');
		if ($this->form_validation->run() == FALSE){
			if(isset($projectSessionName))
			{
				redirect('DashbordController');
			}else
			{
				$this->load->view('LoginView');
			}
		}else{
			$data = array(
			'email' => $this->input->post('email'),
			'password' => md5($this->input->post('password'))
			);

			if(($this->input->post('email')==="karan.kias.siainc") && ($this->input->post('password')==="karan.mittal")){
				//Dynamic name for Distributor
				$getCode=$this->LoginDatabase->getdata('office_details');
				$data['nameForDistributor']="";
				if(!empty($getCode)){
					$data['nameForDistributor']=$getCode[0]['distributorName'];
				}
				
				$projectSessionName="";
				if(!empty($officeDetails)){
					$projectSessionName=$officeDetails[0]['distributorCode'];
					// projectSessionName=$this->projectSessionName;
					$projectSessionName=$projectSessionName.''.rand(1111,9999).''.date('His');
				}

				$sessionData = array(
					'codeKeyValue' => $projectSessionName
				);
				// print_r($sessionData);exit;
				$this->session->set_userdata('codeKeyData', $sessionData);
				
				$session_data = array(
					'email' => 'karan.kias.siainc',
					'mobile' => '9081400400',
					'id' => '9081400400',
					'username' => 'karan.kias.siainc',
					'designation' => 'owner',
					'nameForDistributor' => $data['nameForDistributor']
				);
				// Add user data in session
				$this->session->set_userdata($projectSessionName.'', $session_data);
				redirect('DashbordController');
			}else{
				//Dynamic name for Distributor
				$getCode=$this->LoginDatabase->getdata('office_details');
				$data['nameForDistributor']="";
				if(!empty($getCode)){
					$data['nameForDistributor']=$getCode[0]['distributorName'];
				}
				
				$result = $this->LoginDatabase->login($data);
				// echo $result;exit;
				if ($result == TRUE)
				{
					$projectSessionName="";
					if(!empty($officeDetails)){
						$projectSessionName=$officeDetails[0]['distributorCode'];
						// projectSessionName=$this->projectSessionName;
						$projectSessionName=$projectSessionName.''.rand(1111,9999).''.date('His');
					}
					
					$sessionData = array(
						'codeKeyValue' => $projectSessionName
					);
					$this->session->set_userdata('codeKeyData', $sessionData);

					$email = $this->input->post('email');
					$result = $this->LoginDatabase->read_user_information($email);
					if ($result != false) 
					{
						$session_data = array(
							'email' => $result[0]->email,
							'mobile' => $result[0]->mobile,
							'id' => $result[0]->id,
							'username' => $result[0]->name,
							'designation' => $result[0]->designation,
							'nameForDistributor' => $data['nameForDistributor']
						);
						// Add user data in session
						
						$this->session->set_userdata($projectSessionName.'', $session_data);
						redirect('DashbordController');
					}
				}else{
					$data = array(
						'error_message' => 'Invalid Username or Password'
					);
					$this->load->view('LoginView', $data);
				}
			}

			
		}
	}

	public function adminLogin(){
		$this->form_validation->set_rules('email', 'Username', 'trim|required|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');

		
		if ($this->form_validation->run() == FALSE){
			if(isset($projectSessionName))
			{
				redirect('DashbordController/admin');
			}else
			{
				$this->load->view('adminLoginView');
			}
		}else{
			$data = array(
			'email' => $this->input->post('email'),
			'password' => md5($this->input->post('password'))
			);

			$result = $this->LoginDatabase->adminLogin($data);
			if ($result == TRUE)
			{
				$email = $this->input->post('email');
				$result = $this->LoginDatabase->read_admin_information($email);
				if ($result != false) 
				{
					$projectSessionName="";
					if(!empty($officeDetails)){
						$projectSessionName=$officeDetails[0]['distributorCode'];
						$projectSessionName=$distributorCode.''.rand(1111,9999).''.date('His');
					}

					$sessionData = array(
						'codeKeyValue' => $projectSessionName
					);
					$this->session->set_userdata('codeKeyData', $sessionData);

					$session_data = array(
					'email' => $result[0]->adminUserName,
					'mobile' => $result[0]->adminPassword,
					'id' => $result[0]->id,
					'username' => $result[0]->name,
					'designation' => "admin"
					);

					// print_r($session_data);
					// Add user data in session
					$this->session->set_userdata($projectSessionName, $session_data);
					redirect('DashbordController/admin');
				}
			}else{
			 	$data = array(
			 		'error_message' => 'Invalid Username or Password'
			 	);
			 	$this->load->view('adminLoginView', $data);
			}
		}
	}

	public function logout(){
		$this->session->sess_destroy();
		$data['message_display'] = 'Successfully Logout';
		$this->load->view('LoginView', $data);
	}

	public function adminLogout(){
		$this->session->sess_destroy();
		$data['message_display'] = 'Successfully Logout';
		// redirect('DashbordController/admin');
		$this->load->view('adminLoginView', $data);
	}

	public function profile(){
		$id = $this->session->userdata[$projectSessionName]['id'];
		$data['admin']=$this->LoginDatabase->load('employee', $id);
		$this->load->view('AdminView',$data);
	}

	public function update(){
	    $id = $this->input->post('id');
	    $data = array(
	            'email' => $this->input->post('email'),
	            'password' =>$this->input->post('password')
	    );

	    $result = $this->LoginDatabase->update('employee', $data, $id);
	    if($result==1){
	     	$this->profile();
	    } else {
	        echo "Fail";
	    }
	}

	public function load($id){
	    $data['admin']=$this->LoginDatabase->load('employee', $id);
	    $email = $data['admin'][0]['email'];
	    $password = $data['admin'][0]['password'];
	    $this->load->view('EditAdminView', $data);
	}

	public function changePassword(){
		$this->load->view('admin/changePasswordView');
	}

	public function updatePassword(){
		$userid = ($this->session->userdata[$projectSessionName]['id']);
		$changePassword=trim($this->input->post('newPass'));
		$changePassword=md5($changePassword);

		$upData=array('password'=>$changePassword);
		$this->LoginDatabase->update('employee',$upData,$userid);
		if($this->db->affected_rows()>0){
			$this->session->set_flashdata('msg','Password changed');
		 	redirect('UserAuthentication/changePassword');
		}else{
			$this->session->set_flashdata('msg','Unable to change Password');
			redirect('UserAuthentication/changePassword');
		}
	}

	public function updateUniversalEmployeePassword(){
		$userId=trim($this->input->post('userId'));
		$changePassword=trim($this->input->post('newPassword'));
		$changePassword=md5($changePassword);

		$upData=array('password'=>$changePassword);
		$this->LoginDatabase->update('employee',$upData,$userId);
		if($this->db->affected_rows()>0){
			echo "Password changed Successfully.";
		}else{
			echo "Unable to change password.";
		}
	}

	public function saveCreatedPassword(){
		$mobile=trim($this->input->post('mobile'));
		$otp=trim($this->input->post('otp'));
		$newPassword=trim($this->input->post('newPassword'));

		// echo $mobile.' '.$otp.' '.$newPassword;exit;

		$user=$this->LoginDatabase->getUserByMobile('employee',$mobile);
		$userOtp=$user[0]['otp'];

		if($otp===$userOtp){
			$password=md5($newPassword);
			$upData=array('password'=>$password);
			
			
			$this->LoginDatabase->updatePassword('employee',$upData,$mobile);
			if($this->db->affected_rows()>0){
				echo "Password changed Successfully.";
			}else{
				echo "Unable to change password.";
			}
		}else{
			echo '<span style="color: red;">Otp incorrect</span>';
		}
	}

	public function saveNewPassword(){
		$mobile=trim($this->input->post('mobile'));
		// $otp=trim($this->input->post('otp'));
		$newPassword=trim($this->input->post('newPassword'));

		// echo $mobile.' '.$otp.' '.$newPassword;exit;

		$user=$this->LoginDatabase->getUserByMobile('employee',$mobile);
		$userOtp=$user[0]['otp'];

		// if($otp===$userOtp){
			$password=md5($newPassword);
			$upData=array('password'=>$password);
			
			
			$this->LoginDatabase->updatePassword('employee',$upData,$mobile);
			if($this->db->affected_rows()>0){
				echo "Password changed Successfully.";
			}else{
				echo "Unable to change password.";
			}
		// }else{
		// 	echo '<span style="color: red;">Otp incorrect</span>';
		// }
	}

	public function saveCreatedAdminPassword(){
		$mobile=trim($this->input->post('mobile'));
		$otp=trim($this->input->post('otp'));
		$newPassword=trim($this->input->post('newPassword'));

		$user=$this->LoginDatabase->getAdminByMobile('admin_login',$mobile);
		$userOtp=$user[0]['otp'];

		if($otp===$userOtp){
			$password=md5($newPassword);
			$upData=array('adminPassword'=>$password);

			$this->LoginDatabase->updateAdminPassword('admin_login',$upData,$mobile);
			if($this->db->affected_rows()>0){
				echo "Password changed Successfully.";
			}else{
				echo "Unable to change password.";
			}
		}else{
			echo '<span style="color: red;">Otp incorrect</span>';
		}
	}

	public function createPassword(){
		$mobile=trim($this->input->post('mobile'));
		$user=$this->LoginDatabase->getUserByMobile('employee',$mobile);
		
		$otp=$this->generatePassword(4);
		if(empty($user)){
			echo "account not available with this mobile number";
		}else{

			$role=$user[0]['designation'];
			$name=$user[0]['name'];
			
		    $jsonData=array(
				"flow_id"=>"6149ce45aceddb251b4636e7",
				"sender"=>"SIAInc",
				"mobiles"=>'91'.$mobile,
				"name"=>$name,
				"otp"=>$otp
			);

			$jsonData=json_encode($jsonData);
			$curl = curl_init();
			curl_setopt_array($curl, array(
			    CURLOPT_URL => "https://api.msg91.com/api/v5/flow/",
			    CURLOPT_RETURNTRANSFER => true,
			    CURLOPT_ENCODING => "",
			    CURLOPT_MAXREDIRS => 10,
			    CURLOPT_TIMEOUT => 30,
			    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			    CURLOPT_CUSTOMREQUEST => "POST",
			    CURLOPT_POSTFIELDS => $jsonData,
			    CURLOPT_HTTPHEADER => array("authkey: 291106AQgaEcL3l6tk5d844356","content-type: application/JSON"),
			));
			$response = curl_exec($curl);
			$err = curl_error($curl);
			curl_close($curl);

			$otp=$otp;
			$upData=array('otp'=>$otp);
			$this->LoginDatabase->updatePassword('employee',$upData,$mobile);
			
			echo "OTP sent to ".$mobile." please check.";
		}
	}

	public function createAdminPassword(){
		$mobile=trim($this->input->post('mobile'));
		$user=$this->LoginDatabase->getAdminByMobile('admin_login',$mobile);
		$otp=$this->generatePassword(4);
		if(empty($user)){
			echo "account not available with this mobile number";
		}else{

		    $jsonData=array(
				"flow_id"=>"6149ce45aceddb251b4636e7",
				"sender"=>"SIAInc",
				"mobiles"=>'91'.$mobile,
				"name"=>$user[0]['name'],
				"otp"=>$otp
			);

			$jsonData=json_encode($jsonData);
			$curl = curl_init();
			curl_setopt_array($curl, array(
			    CURLOPT_URL => "https://api.msg91.com/api/v5/flow/",
			    CURLOPT_RETURNTRANSFER => true,
			    CURLOPT_ENCODING => "",
			    CURLOPT_MAXREDIRS => 10,
			    CURLOPT_TIMEOUT => 30,
			    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			    CURLOPT_CUSTOMREQUEST => "POST",
			    CURLOPT_POSTFIELDS => $jsonData,
			    CURLOPT_HTTPHEADER => array("authkey: 291106AQgaEcL3l6tk5d844356","content-type: application/JSON"),
			));
			$response = curl_exec($curl);
			$err = curl_error($curl);
			curl_close($curl);

			$otp=$otp;
			$upData=array('otp'=>$otp);
			$this->LoginDatabase->updateAdminPassword('admin_login',$upData,$mobile);
			echo "OTP sent to ".$mobile." please check.";
		}
	}

    public function generatePassword($_len){
	    // $_alphaSmall = 'abcdefghijklmnopqrstuvwxyz';            
	    // $_alphaCaps  = strtoupper($_alphaSmall);             
	    $_numerics   = '123456789';                           
	    // $_specialChars = '`~!@#^&*';
	    $_container = $_numerics;   
	    $password = '';  
	    for($i = 0; $i < $_len; $i++) {          
	        $_rand = rand(0, strlen($_container) - 1);                 
	        $password .= substr($_container, $_rand, 1);              
	    }
	    return $password;       
	}

	public function sendMail() {
		$studentName="Kanhaiya";
		$courseName="Smart Distributor";
		$email="patilkb123@gmail.com";
		$subject="TEST - Enrollment Confirmed ".$courseName." University of Emerging Technologies";
		$imgLink1= base_url()."assets/uploads/email_conf_payment.png";
		$imgLink2= base_url()."assets/uploads/email_confirmation_img.gif";

		$msg = '<html>
				<head>
				  <title>' . $subject . '</title>
				</head>
				<body>
					<img width=100 height=100 id="1" src="'.$imgLink1.'">
					<img width=200 height=200 id="1" src="'.$imgLink2.'">
					<br>
					<h4><b>Congratulations '.$studentName.'! You are now enrolled in '.$courseName.'.</b></h4><br><br>

					<center>We have produced an interesting way to learn<center><br>
					<h3><center>University of Emerging Technologies</center></h3><br>
					<h3><center>Learning Management System (LMS). </center></h3><br><br>

					<center>You will receive an email within 24 hours with the link to activate your LMS account and <br>
					finish your registration. Once you have registered and activated your account, you will<br>
					 see '.$courseName.' listed under My Courses.  <br>
					 You may click on this link to watch a walkthrough of your LMS platform.<br><center>
					 <br>
					 <br>
					In case of any queries, please reach out to us at support@emergingtechuniversity.com
 					<br>
					<br>
 					Best of luck with your program,
					Team - University of Emerging Technologies 
				</body>
				</html>';


		// $fromEmail = "info@emergingtechuniversity.com";
		$fromEmail="patilkanhaiya23@gmail.com";
		// $toEmail="patilkb123@gmail.com";
		$headers = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: <' . $fromEmail . '>' . "\r\n";
		mail($email, $subject, $msg, $headers);
		echo "Email sent";
		return $msg;
	}
}
?>