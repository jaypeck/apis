<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Credentials:true");
	header("Access-Control-Allow-Origin:*");	
	header("Access-Control-Allow-Methods:GET,POST,PUT,DELETE,OPTIONS");
	header("Access-Control-Allow-Headers:Origin,X-Requested-With,Content-Type,Accept,Authorization");


class Movies extends CI_Controller {

	
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
	public function getMovies(){ 
		$query=$this->db->get("movies");
		$query=$query->result();
	/*	echo "<h1>Hello,it's me</h1>";
		echo "<table style='border:1px solid black;color:red'>";

			foreach ($query as $row){
				echo "<tr><td>".$row->title."</td></tr> <tr><td>".$row->description."</td></tr>";
			}
			//$movies=json_encode($query);
			//echo $movies;
		echo "</table>"; */

			$movies=json_encode($query);
			echo $movies;
			
	}
	public function insertMovie(){
		$newMovie= array(
			"title"=>"Batman",
			"description"=>"hey ho let's go",
			"image"=>"img/ssd.jpg"
		);

		$this->db->insert("movies",$newMovie);

	}	
	public function updateMovie(){
		$newmovieinfo=array(
			"description"=>"Twink Wikle"
		);
		$this->db->where("title","Batman");
		$this->db->update("movies",$newmovieinfo); 		
		$affectedrows=$this->db->affected_rows();
		if($affectedrows>0){echo "updated";}
		else{echo"not updated";};
	}	

}
