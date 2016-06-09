<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Credentials:true");
	header("Access-Control-Allow-Origin:*");	
	header("Access-Control-Allow-Methods:GET,POST,PUT,DELETE,OPTIONS");
	header("Access-Control-Allow-Headers:Origin,X-Requested-With,Content-Type,Accept,Authorization");


class PSB extends CI_Controller {

	
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index(){
		$this->load->view("welcome_message");	
	}

	public function getTimetable(){
/*find student*/		$student_id="a11111";
		$this->db->where("student_id",$student_id);     //2 parameters 1)field :student id ...2)value : $student
		
/*get his info*/		$query=$this->db->get("a1_users");     //Get from table "users" //Gets $student(a1111) from users
		$students=$query->result();   //The result of the table....Gives me an array of objects...everything of the table that maatches student id
		
/*get his modules*/		//echo $students[0]->modules."<br/>";  //FIRST ECHO...first item in array, look for module		
/* gets module [1,2]*/		$studentModules=json_decode($students[0]->modules);//decodes into readable json
	//	print_r($studentModules);				//SECOND ECHO
		
/* make timetable*/		$timetable=[]; 

/*zooming into each module itself*/		foreach ($studentModules as $module){   //for each module
/*get booking info for that module*/			$this->db->from("z_bookings b");    // means bookings referred as b...r and m will be declared in join()         
/*info student needs*/			$this->db->select("b.session,b.date,b.room,b.status,m.module_id,m.module_code,m.description,r.room_name");//VALUES I WISH TO DISPLAY
			
			$this->db->join("a4_modules m","m.module_id = b.module"); //means modules referred as m..JOIN MODULE_ID(module) with MODULE(bookings)
			$this->db->join("b_rooms r","r.room_id=b.room");          //means rooms referred as r..JOIN ROOM_IN(room) with ROOM(bookings)
			

			$this->db->where('m.module_id',$module); //*******DONT UNDERSTAND**	
			$qry=$this->db->get();  //*******DONT UNDERSTAND**  What am i getting from get?
			$qry=$qry->result();
			for($i=0;$i<count($qry);$i++){
				array_push($timetable,$qry[$i]); //array:$timetable  add stuff:qry 0
			}
		}
		
	//		print_r($timetable);
			echo json_encode($timetable);    //THIRD ECHO


		/*	$this->db->where("module",$module);
			$query= $this->db->get("bookings");
			$query=$query->result();
			var_dump($query);  */
		}	
	

	public function getTimetable2(){
/*find student*/		$student_id="a11111";
		$this->db->where("student_id",$student_id);     //2 parameters 1)field :student id ...2)value : $student
		
/*get his info*/		$query=$this->db->get("a1_users");     //Get from table "users" //Gets $student(a1111) from users
		$students=$query->result();   //The result of the table....Gives me an array of objects...everything of the table that maatches student id
		
/*get his modules*/		//echo $students[0]->modules."<br/>";  //FIRST ECHO...first item in array, look for module		
/* gets module [1,2]*/		$studentModules=json_decode($students[0]->modules);//decodes into readable json
	//	print_r($studentModules);				//SECOND ECHO
		
/* make timetable*/		$timetable=[]; 

/*zooming into each module itself*/		foreach ($studentModules as $module){   //for each module
/*get booking info for that module*/			$this->db->from("z_bookings b");    // means bookings referred as b...r and m will be declared in join()         
/*info student needs*/			$this->db->select("b.session,b.date,b.room,b.status,m.module_id,m.module_code,m.description,r.room_name");//VALUES I WISH TO DISPLAY
			
			$this->db->join("a4_modules m","m.module_id = b.module"); //means modules referred as m..JOIN MODULE_ID(module) with MODULE(bookings)
			$this->db->join("b_rooms r","r.room_id=b.room");          //means rooms referred as r..JOIN ROOM_IN(room) with ROOM(bookings)
			

			$this->db->where('m.module_id',$module); //*******DONT UNDERSTAND**	
			$qry=$this->db->get();  //*******DONT UNDERSTAND**  What am i getting from get?
			$qry=$qry->result();
			for($i=0;$i<count($qry);$i++){
				array_push($timetable,$qry[$i]); //array:$timetable  add stuff:qry 0
			}
		}
		
			$data["tt"]=$timetable;
			$this->load->view("timetable",$data);


		/*	$this->db->where("module",$module);
			$query= $this->db->get("bookings");
			$query=$query->result();
			var_dump($query);  */
		}		


	public function login(){
		$data=	json_decode(file_get_contents("php://input"));
 //		var_dump($data);
		$student_id=$data->dog->student_id	;
		$password=$data->dog->password; 
//		echo $student_id;
//		echo $password;




		$this->db->where("student_id",$student_id);
		$this->db->where("password",$password);
		$query=$this->db->get("a1_users"); 
		$query=$query->result(); 

		if(count($query)>0){
			echo json_encode($query[0]);
		}
		
		else{
			echo "User not Found!!";
		};
		


	//	var_dump($query);



	}



	public function sendmail(){
		

		$qry=$this->db->get("a1_users");
		//$this->db->where(*,$student_id);   
		$qry=$qry->result();




		$data["name"]="jay";
		

		$message=$this->load->view("something",$data,TRUE);
		$this->email->set_mailtype('html');		
		//$this->email->from('jjpeckk@gmail.com', 'Your Name');
		for($i=0;$i<count($qry);$i++){
				 $this->email->from($qry[$i]->student_id."@limpeh.com", $qry[$i]->student_id);
			
		$this->email->to('jjpeckk@gmail.com');
		$this->email->cc('another@another-example.com'); 
		$this->email->bcc('them@their-example.com'); 

		$this->email->subject('Email Test');
		$this->email->message($message);	

		$this->email->send();
		echo "hello";
		}
	}


	}





