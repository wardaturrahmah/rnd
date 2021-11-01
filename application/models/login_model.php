<?php
class Login_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __construct() {
        parent::__construct();
	}
	
	// Inisialisasi nama tabel 
	
	
	
	

	
	
	
	function check_user2($username,$password)
	{
		$sql="select * from login_mkt
			where Username='$username' and Password='$password' ";
		$sql=$this->db->query($sql);
		if($sql->num_rows()>0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
			$this->session->set_flashdata('message', 'Maaf, account login anda diblokir');
		}
	}
	

	
	function get_akses2($username)
	{
			$sql="select * from login_mkt
			where Username='$username' ";
		return $this->db->query($sql);
	}
	function get_akses3($id)
	{
			$sql="select * from login_mkt
			where id=$id ";
		return $this->db->query($sql);
	}
	function list_all_user(){
		$sql="select * from login_mkt";
		return $this->db->query($sql);
	}
	function list_user(){
		$user=$this->session->userdata('id_seas');//ganti
		$sql="
			WITH cte_org AS (

				SELECT       
					id, 
					Username,id_head
					
				FROM       
					login_mkt
					WHERE ID=$user
				UNION ALL
				SELECT 
					e.id, 
					e.Username,e.id_head
				FROM
					login_mkt e
					INNER JOIN cte_org o 
						ON o.id = e.ID_head				
			)

			SELECT b.*,c.Username as head,d.group_menu, 
			case when b.group_produk=1 then 'Line Produk' else 'Produk' end as group_produk

			FROM cte_org a, login_mkt b
			left join login_mkt c on b.id_head=c.id 
			left join group_menu d on b.Group_menu=d.id
			where a.id=b.id
			";
		return $this->db->query($sql);
	}
	function list_group(){
		$sql="select * from group_menu
			";
		return $this->db->query($sql);
	}
	function group_id($id){
		$sql="select * from group_menu
			where id=$id
			";
		return $this->db->query($sql);
	}
	
}
