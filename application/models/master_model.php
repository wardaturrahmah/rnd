<?php
class Master_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __construct() 
	{
        parent::__construct();
	}
	
	function  get_lp(){
		$sql="		
		select * from lineproduk
		order by id_lp
		";
		return $this->db->query($sql);		
	
	}

	function  get_lp2($id){
		$sql="		
		select * from lineproduk
		where id_lp=$id
		
		";
		return $this->db->query($sql);		
	
	}
	function  get_lp_name($name){
		$sql="		
		select * from lineproduk
		where lineproduk='$name'
		
		";
		return $this->db->query($sql);		
	
	}
	function  get_kategori(){
		$sql="		
		select * from kategori
		
		";
		return $this->db->query($sql);		
	
	}
	function  get_kategori2($id){
		$sql="		
		select * from kategori
		where id_kategori=$id
		";
		return $this->db->query($sql);		
	
	}
	function  cek_kategori($kategori){
		$sql="		
		select * from kategori
		where kategori='$kategori'
		";
		return $this->db->query($sql);		
	
	}
	function  bahan_by_kategori($kategori){
		$sql="		
		select * from bahan
		where kategori=$kategori
		";
		return $this->db->query($sql);		
	
	}
	function  get_kategori_sarana(){
		$sql="		
		select * from kategori_sarana
		
		";
		return $this->db->query($sql);		
	
	}
	function  get_kategori_sarana2($id){
		$sql="		
		select * from kategori_sarana
		where id_kategori=$id
		";
		return $this->db->query($sql);		
	
	}
	function  cek_kategori_sarana($kategori){
		$sql="		
		select * from kategori_sarana
		where kategori='$kategori'
		";
		return $this->db->query($sql);		
	
	}
	function  get_bahan(){
		$sql="		
			select * from bahan a, kategori b
			where a.kategori=b.id_kategori
		
		";
		return $this->db->query($sql);		
	
	}
	function  get_bahan2($kode){
		$sql="		
		select * from bahan
		where id_bahan=$kode
		";
		return $this->db->query($sql);		
	
	}
	function  cek_kode_bahan($kode){
		$sql="		
		select * from bahan
		where kode='$kode'
		";
		return $this->db->query($sql);		
	
	}
	function cek_bahan_formula($bahan)
	{
		$sql="select * from bahan_formula where kode_bahan='$bahan'";
		return $this->db->query($sql);
	}
	function  get_sarana(){
		$sql="		
			select * from sarana a, kategori_sarana b
			where a.kategori=b.id_kategori
		
		";
		return $this->db->query($sql);		
	
	}
	function  get_sarana2($kode){
		$sql="		
		select * from sarana
		where id_sarana=$kode
		";
		return $this->db->query($sql);		
	
	}
	function  cek_kode_sarana($kode){
		$sql="		
		select * from sarana
		where sarana='$kode'
		";
		return $this->db->query($sql);		
	
	}
	function  sarana_by_kategori($id)
	{
		$sql="		
			select * from sarana
			where kategori=$id
		";
		return $this->db->query($sql);		
	
	}
	function  sarana_formula($id)
	{
		$sql="		
			
			select * from sarana_formula
			where id_sarana=$id
		";
		return $this->db->query($sql);		
	
	}
	function  get_risetman(){
		$sql="		
		select * from risetman
		order by risetman
		";
		return $this->db->query($sql);		
	
	}
	function  get_risetman2($id){
		$sql="		
		select * from risetman
		where id_risetman=$id
		";
		return $this->db->query($sql);		
	
	}
	function  get_panelis(){
		$sql="		
		select *, nama_panelis as panelis from master_panelis
		
		";
		return $this->db->query($sql);		
	
	}
	function  get_panelis2($id){
		$sql="		
		select * from master_panelis
		where id_panelis=$id
		";
		return $this->db->query($sql);		
	
	}
	function  cek_produk($id){
		$sql="		
		select * from produk
		where id=$id
		";
		return $this->db->query($sql);		
	
	}
	function  get_masalah(){
		$sql="		
		select * from master_masalah
		
		";
		return $this->db->query($sql);		
	
	}
	function  get_masalah2($id){
		$sql="		
		select * from master_masalah
		where id_masalah=$id
		";
		return $this->db->query($sql);		
	
	}
	function get_produk()
	{
		$sql="		
		select * from produk
		where nama_item is not null
		order by nama_item
		";
		return $this->db->query($sql);		
	}
	function get_produk_akses($user)
	{
		$sql="		
		select a.* from produk a, akses_item b
		where nama_item is not null and a.id=b.item
		and b.username='$user' and akses=1
		order by nama_item
		";
		return $this->db->query($sql);		
	}
	function get_produk2($id_lp)
	{
		$sql="		
		select * from produk
		where line=$id_lp
		";
		return $this->db->query($sql);		
	}
	function get_produk2_akses($user,$id_lp)
	{
		$sql="		
		select a.* from produk a, akses_item b
		where nama_item is not null and a.id=b.item
		and b.username='$user' and akses=1 and a.line=$id_lp
		order by nama_item
		";
		return $this->db->query($sql);		
	}
	function get_produk3($id_lp)
	{
		$sql="		
		select * from produk
		where line in ($id_lp)
		";
		return $this->db->query($sql);		
	}
	function get_produk_terminated()
	{
		$sql="		
		select * from produk
		where nama_item is not null and status=-1
		";
		return $this->db->query($sql);		
	}
	function get_produk_run()
	{
		$sql="		
		select * from produk
		where nama_item is not null and status!=-1
		";
		return $this->db->query($sql);		
	}
	function get_produk_run_akses($username)
	{
		$sql="		
		select * from produk a, akses_item b
		where nama_item is not null and status!=-1 and a.id=b.item
		and b.username='$username' and b.akses=1
		";
		return $this->db->query($sql);		
	}
	function get_ref_link($id_item)
	{
		$sql="select * from ref_link a, produk b
				where a.id_item=$id_item and a.link_item=b.id";
				
		return $this->db->query($sql);		
	}
	function get_ref_formula($id_formula)
	{
		$sql="select * from ref_formula a, formula2 b, produk c
			where a.link_formula=b.id and b.id_item=c.id
			and a.id_formula=$id_formula
		";
				
		return $this->db->query($sql);		
	}
	function cek_panelis($panelis){
		$sql="		
		select * from master_panelis
		where nama_panelis='$panelis'
		";
		return $this->db->query($sql);		
	
	}
	function cek_risetman($risetman){
		$sql="		
		select * from risetman
		where risetman='$risetman'
		";
		return $this->db->query($sql);		
	}
	function risetman_formula($risetman)
	{
		$sql="		
		select * from formula2
		where risetman=$risetman
		";
		return $this->db->query($sql);	
		
	}
	function panelis_hdr($panelis)
	{
		$sql="		
		
			select * from penilaian_kompetitor_hdr 
			where panelis='$panelis'
			union all
			select * from penilaian_hdr
			where panelis='$panelis'
		";
		return $this->db->query($sql);		
	}
	function penilaian_masalah($id)
	{
		$sql="		
			select * from penilaian_masalah
			where id_masalah=$id
		";
		return $this->db->query($sql);		
	}
	function checkaut($id_item)
	{
		$user=$this->session->userdata('id_seas');//ganti
		$sql="		
			select * from akses_item
			where item=$id_item and id_user='$user'
			and akses=1
		";
		return $this->db->query($sql);	
	}
	function checkaut_menu($id_menu)
	{
		$user=$this->session->userdata('group_menu_seas');//ganti
		$sql="		
			select * from akses_menu_group
			where id_group=$user and id_menu=$id_menu --and r=1
		";
		return $this->db->query($sql);	
	}
	function maksiduser()
	{
		$sql="		
				select max(id) as id from login_mkt
		";
		return $this->db->query($sql);	
	}
	function maksidgroup()
	{
		$sql="		
				select max(id) as id from group_menu
		";
		return $this->db->query($sql);	
	}
	function group_menu()
	{
		$sql="		
				select * from group_menu
		";
		return $this->db->query($sql);	
	}
	function list_under_spv($id_spv)
	{
		$sql="		
				WITH cte_org AS (

				SELECT       
					id, 
					Username,id_head
					
				FROM       
					login_mkt
					WHERE ID=$id_spv
				UNION ALL
				SELECT 
					e.id, 
					e.Username,e.id_head
				FROM
					login_mkt e
					INNER JOIN cte_org o 
						ON o.id = e.ID_head
					
				
			)

			SELECT * FROM cte_org a, login_mkt b
			where a.id=b.id
		";
		return $this->db->query($sql);	
	}
	function get_menu()
	{
		$sql="select * from list_menu
		";
		return $this->db->query($sql);	
	}
}