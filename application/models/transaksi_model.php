<?php
class Transaksi_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __construct() 
	{
        parent::__construct();
	}
	
	function add_form($form,$table)
	{
		$this->db->insert($table, $form);
	}
	
	function edit_form($idp,$id,$waktu_jeda,$table)
	{
		$this->db->where($idp, $id);
		$this->db->update($table, $waktu_jeda);
	}
	
	function edit_form2($id,$waktu_jeda,$table)
	{
		$this->db->where($id);
		$this->db->update($table, $waktu_jeda);
	}
	
	function delete_form($id,$table)
	{
		$this->db->delete($table, array('id' => $id));
	}	
	
	function delete_form2($id,$table,$kolom)
	{
		$this->db->delete($table, array($kolom  => $id));
	}	
	function delete_form3($table,$id)
	{
		$this->db->delete($table,$id);
	}	
	function id_item()
	{
		$sql="select top 1 * from produk order by id desc";
		return $this->db->query($sql);
	}
	function penilaian($id_item,$subvar)
	{
		$sql="select *,a.id as id,b.id as id_item
		 from penilaian a, produk b
		 where a.id_item=b.id and a.id_item=$id_item and subvar='$subvar'
		 ";
		return $this->db->query($sql);
	}
	function penilaian2($id)
	{
		$sql="select *,a.id as id,b.id as id_item
		 from penilaian a, produk b
		 where a.id_item=b.id and a.id=$id";
		return $this->db->query($sql);
	}
	function cek_penilaian($id)
	{
		$sql="select * from penilaian_dtl
		 where id_penilaian=$id";
		return $this->db->query($sql);

	}
	function cek_penilaian2($id)
	{
		$sql="select * from penilaian_kompetitor_dtl
		 where id_penilaian=$id";
		return $this->db->query($sql);

	}
	function panelis($id_item,$panelis)
	{
		$sql="select * from panelis where id_item=$id_item and panelis='$panelis'";
		return $this->db->query($sql);
	}
	function penilaian_all($id_item)
	{
		$sql="select *,a.id as id,b.id as id_item
		 from penilaian a, produk b
		 where a.id_item=b.id and a.id_item=$id_item
		 order by varr,a.id
			";
		return $this->db->query($sql);	
	}
	function panelis_all($id_item)
	{
		$sql="select * from panelis where id_item=$id_item
				order by id desc";
		return $this->db->query($sql);	
	}
	function resume_item($id_item)
	{
		$sql="
			select a.*,b.*,STUFF((SELECT distinct ',' + z.risetman
					FROM  risetman_hdr z
					 where z.id_item=a.id
					FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)') 
				,1,1,'') as risetman,
				STUFF((SELECT distinct ',' + x.nama
					FROM  kompetitor x
					 where x.id_produk=a.id and x.status=1
					FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)') 
				,1,1,'') as kompetitor_list,
				d.id as id_konsep_sebelumnya,d.nama_item as nama_konsep_sebelumnya 
			from produk a
			join lineproduk b on a.line=b.id_lp 
			left join produk d on a.konsep_sebelumnya=d.id
							where a.id=$id_item";
		return $this->db->query($sql);
	}
	function resume_kriteria($id_item,$varr)
	{
		$sql="select * from penilaian where id_item=$id_item and varr='$varr'";
		return $this->db->query($sql);
	}
	function panelis_list($id_item)
	{
		$sql="select * from panelis where id_item=$id_item";
		return $this->db->query($sql);
	}
	function panelis_list2($id_item,$ke)
	{
		$sql="select * from panelis where id_item=$id_item and ke=$ke";
		return $this->db->query($sql);
	}
	function risetman_hdr($id_item)
	{
		$sql="select * from risetman_hdr
				where id_item='$id_item'";
		return $this->db->query($sql);
	}
	function risetman_formula_hdr($id_item)
	{
		$sql="
			
			select a.risetman from risetman_formula a, formula2 b
			where a.id_formula=b.id and b.id_item=$id_item
			group by a.risetman";
		return $this->db->query($sql);
	}
	function risetman_formula($id_formula)
	{
		$sql="select * from risetman_formula
				where id_formula='$id_formula'";
		return $this->db->query($sql);
	}
	/* function id_formula($item,$kode)
	{
		$sql="select * from formula where id_item=$item and kode='$kode'";
		return $this->db->query($sql);
	} */
	function id_formula2()
	{
		$sql="select top 1 * from formula2 order by id desc";
		return $this->db->query($sql);
	}
	function formula($id_formula)
	{
		$sql="select *,a.risetman as risetman from formula2 a, produk b
				where a.id=$id_formula
				and a.id_item=b.id
				
				";
		return $this->db->query($sql);
	}
	function kompetitor($id_formula)
	{
		$sql="select *,a.status as status_kompetitor from kompetitor a, produk b
			where a.id_produk=b.id and a.id_kompetitor=$id_formula";
		return $this->db->query($sql);
	}
	function formula_bahan($id_formula,$bahan)
	{
		$sql="select * from bahan_formula where id_formula=$id_formula and kode_bahan='$bahan'";
		return $this->db->query($sql);
	}
	function formula_bahan2($id)
	{
		$sql="select * from bahan_formula where id=$id";
		return $this->db->query($sql);
	}
	function formula_bahan_all($id_formula)
	{
		$sql="select a.*,b.kode,b.tgl_riset,b.tujuan,c.risetman
				,b.approve1,b.approve2,b.approve3,e.kategori,
				STUFF((SELECT distinct ',' + z.risetman
					FROM  risetman_formula z
					 where z.id_formula=b.id
					FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)') 
				,1,1,'') as risetman_hdr
				 from bahan_formula a
				 join bahan d on a.kode_bahan=d.kode 
				 join kategori e on d.kategori=e.id_kategori
				 left join formula2 b on a.id_formula=b.id
				 left join risetman c on b.risetman=c.id_risetman
				 where a.id_formula=$id_formula";
		return $this->db->query($sql);
	}
	function resume_formula($id_formula)
	{
		$sql="select a.*,b.*,STUFF((SELECT distinct ',' + z.risetman
					FROM  risetman_hdr z
					 where z.id_item=a.id
					FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)') 
				,1,1,'') as risetman,
				STUFF((SELECT distinct ',' + x.nama
					FROM  kompetitor x
					 where x.id_produk=a.id and x.status=1
					FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)') 
				,1,1,'') as kompetitor_list,
				d.id as id_konsep_sebelumnya,d.nama_item as nama_konsep_sebelumnya ,
				c.kode,c.tgl_riset,c.id,c.id_item,c.tujuan,c.id as id_formula
			from produk a
			join lineproduk b on a.line=b.id_lp 
			join formula2 c  on a.id=c.id_item
			left join produk d on a.konsep_sebelumnya=d.id
							where  c.id=$id_formula";
		return $this->db->query($sql);
	}
	function resume_kompetitor($id_formula)
	{
		$sql="select a.*,b.*,STUFF((SELECT distinct ',' + z.risetman
					FROM  risetman_hdr z
					 where z.id_item=a.id
					FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)') 
				,1,1,'') as risetman,
				c.nama,c.status,c.id_kompetitor,c.id_produk as id_item,c.id_kompetitor as id_formula,c.foto
			from produk a
			join lineproduk b on a.line=b.id_lp 
			join kompetitor c  on a.id=c.id_produk
			
							where  c.id_kompetitor=$id_formula";
		return $this->db->query($sql);
	}
	function list_formula($id_item)
	{
		$sql="
			
			select a.*,b.risetman ,STUFF((SELECT distinct ',' + z.risetman
					FROM  risetman_formula z
					 where z.id_formula=a.id
					FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)') 
				,1,1,'') as risetman_hdr,c.nama_item
			from formula2 a, risetman b, produk c
			where a.risetman=b.id_risetman and a.id_item=$id_item
			and a.id_item=c.id
			order by kode,tgl_riset desc ";
		return $this->db->query($sql);
	}
	function list_link_formula($id_item)
	{
		$sql="
			
			select a.*,b.risetman ,STUFF((SELECT distinct ',' + z.risetman
					FROM  risetman_formula z
					 where z.id_formula=a.id
					FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)') 
				,1,1,'') as risetman_hdr,c.nama_item
			from formula2 a, risetman b, produk c
			where a.risetman=b.id_risetman and a.id_item in ($id_item)
			and a.id_item=c.id
			order by c.nama_item,kode,tgl_riset desc ";
		return $this->db->query($sql);
	}
	function list_kompetitor($id_item)
	{
		$sql="
			select *,a.status as status_kompetitor from kompetitor a, produk b
			where a.id_produk=b.id
			and a.id_produk=$id_item
			";
		return $this->db->query($sql);
	}
	function list_formula_by($id_item)
	{
		$sql="select b.risetman,COUNT(b.risetman) as jumlah
			from formula2 a, risetman b
			where a.risetman=b.id_risetman and a.id_item=$id_item
			group by b.risetman";
		return $this->db->query($sql);
	}
	function list_formula_by2($id_item,$tgl,$tgl2)
	{
		$sql="
				exec [rekap_panelis_dtd] $id_item,'$tgl','$tgl2'

			
			";
		return $this->db->query($sql);
	}
	function list_formula2($id_item,$tgl,$ke)
	{
		$sql="
			select a.*,MAX(c.kesimpulan) as kesimpulan
			from formula2 a
			--left join penilaian_hdr c on a.id=c.id_formula and c.ke=$ke
			left join kesimpulan_internal c on a.id=c.id_formula and c.ke=$ke
			where a.id_item=$id_item
			and a.tgl_riset='$tgl'
			group by id,id_item,kode,tgl_riset
			,risetman,tujuan,approve1,approve2,approve3,
			keterangan1,keterangan2,keterangan3,risetman_hdr";
		return $this->db->query($sql);
	}
	function list_item()
	{
		$sql="select a.* ,b.lineproduk,
			STUFF((SELECT distinct ',' + z.risetman FROM  risetman_hdr z
				where z.id_item=a.id FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)'),1,1,'') as risetman
				from produk a, lineproduk b
				where a.line=b.id_lp";
		return $this->db->query($sql);
	}
	function list_item_akses($user)
	{
		$sql="
				select a.* ,b.lineproduk,
				STUFF((SELECT distinct ',' + z.risetman FROM  risetman_hdr z
				where z.id_item=a.id FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)'),1,1,'') as risetman
				from produk a, lineproduk b,akses_item c
				where a.line=b.id_lp and a.id=c.item and c.id_user=$user and c.akses=1
				";
		return $this->db->query($sql);
	}
	function list_line_akses($user)
	{
		$sql="
				select * from akses_lp a, lineproduk b
				where a.id_lp=b.id_lp and a.id_user=$user and a.akses=1
				";
		return $this->db->query($sql);
	}
	function list_line_item_akses($user)
	{
		$sql="select c.id_lp,c.lineproduk
				from akses_item a,produk b, lineproduk c
				where a.item=b.id and c.id_lp=b.line and a.akses=1 and a.id_user=$user
				group by c.id_lp,c.lineproduk";
		return $this->db->query($sql);
	}
	function list_item_line($id_lp)
	{
		$sql="select a.* ,b.lineproduk,
			STUFF((SELECT distinct ',' + z.risetman FROM  risetman_hdr z
				where z.id_item=a.id FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)'),1,1,'') as risetman
				from produk a, lineproduk b
				where a.line=b.id_lp and b.id_lp=$id_lp";
		return $this->db->query($sql);
	}
	function list_item_risetman($risetman)
	{
		$sql="
			select a.* ,b.lineproduk,
			STUFF((SELECT distinct ',' + z.risetman FROM  risetman_hdr z
				where z.id_item=a.id FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)'),1,1,'') as risetman
				from produk a, lineproduk b
				where a.line=b.id_lp 

				and STUFF((SELECT distinct ',' + z.risetman FROM  risetman_hdr z
				where z.id_item=a.id and z.risetman='$risetman' FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)'),1,1,'') is not null
			";
		return $this->db->query($sql);
	}
	function transfer_bahan($id_formula,$id_t_formula)
	{
		$sql="insert into bahan_formula
			select $id_formula as id_formula,kode_bahan,kadar 
			from bahan_formula
			where id_formula=$id_t_formula";
		return $this->db->query($sql);
	}
	function delete_produk($id_item)
	{
		$sql="
			delete a
			from stage_formula a, formula2 b
			where a.id_formula=b.id and b.id_item=$id_item
			
			delete a
			from kesimpulan_ts a, formula2 b
			where a.id_formula=b.id and b.id_item=$id_item
		
			delete a
			from kesimpulan_internal a, formula2 b
			where a.id_formula=b.id and b.id_item=$id_item
		
			delete a
			from penilaian_masalah a, formula2 b
			where a.id_hdr=b.id and b.id_item=$id_item
		
			delete b
			from penilaian_hdr a , penilaian_dtl b, formula2 c
			where a.id_penilai=b.id_hdr and a.id_formula=c.id and c.id_item=$id_item
			
			delete a
			from penilaian_hdr a, formula2 b
			where a.id_formula=b.id and b.id_item=$id_item
			
			delete b
			from formula2 a , sarana_formula b
			where a.id_item=$id_item and b.id_formula=a.id
			
			delete b
			from formula2 a , bahan_formula b 
			where a.id_item=$id_item and b.id_formula=a.id

			delete b
			from formula2 a , risetman_formula b 
			where a.id_item=$id_item and b.id_formula=a.id

			
			delete a
			from ref_formula a, formula2 b
			where a.id_formula=b.id and b.id_item=$id_item
			
			delete from formula2
			where id_item=$id_item

			delete from panelis
			where id_item=$id_item

			delete from penilaian
			where id_item=$id_item

			delete a from penilaian_kompetitor_hdr a, kompetitor b
			where a.id_formula=b.id_kompetitor and b.id_produk=$id_item

			delete c from penilaian_kompetitor_hdr a, kompetitor b, penilaian_kompetitor_dtl c
			where a.id_formula=b.id_kompetitor and a.id_penilai=c.id_hdr and b.id_produk=$id_item 
		
			delete from kompetitor
			where id_produk=$id_item
			
			delete from ref_link
			where id_item=$id_item
			
			delete from risetman_hdr
			where id_item=$id_item
			
			delete from akses_item
			where item=$id_item
			
			delete from produk
			where id=$id_item
			";
		return $this->db->query($sql);
	}
	
	function delete_formula($id_formula)
	{
		$sql="			
		
			delete from stage_formula
			where id_formula=$id_formula;
			
			delete a
			from kesimpulan_ts a, formula2 b
			where a.id_formula=b.id and b.id=$id_formula
			
			delete a
			from kesimpulan_internal a, formula2 b
			where a.id_formula=b.id and b.id=$id_formula
		
			
			delete a
			from penilaian_masalah a, formula2 b
			where a.id_hdr=b.id and b.id=$id_formula
		
						
			delete b
			from penilaian_hdr a , penilaian_dtl b 
			where a.id_formula=$id_formula and a.id_penilai=b.id_hdr;
			
			delete from penilaian_hdr
			where id_formula=$id_formula;
			
			delete from sarana_formula
			where id_formula=$id_formula;
			
			delete from bahan_formula
			where id_formula=$id_formula;
			
			delete from ref_formula
			where id_formula=$id_formula;

			delete from risetman_formula
			where id_formula=$id_formula;
			
			delete from formula2
			where id=$id_formula;
			";
		return $this->db->query($sql);
	}
	
	function delete_kompetitor($id_formula)
	{
		$sql="
		delete b 
		from penilaian_kompetitor_hdr a, penilaian_kompetitor_dtl b
		where a.id_penilai=b.id_hdr and a.id_formula=$id_formula;
		delete from penilaian_kompetitor_hdr
		where id_formula=$id_formula;
		delete from kompetitor
		where id_kompetitor=$id_formula;
		";
		return $this->db->query($sql);

	}
	function list_nilai($id_formula)
	{
		$sql="select c.*,a.id as id_formula
				 from formula2 a, produk b, penilaian c
				where a.id_item=b.id and c.id_item=b.id
				and a.id=$id_formula
				order by c.varr,c.id";
		return $this->db->query($sql);
	}
	function list_nilai2($id_formula)
	{
		$sql="select c.*,a.id_kompetitor as id_formula
				 from kompetitor a, produk b, penilaian c
				where a.id_produk=b.id and c.id_item=b.id
				and a.id_kompetitor=$id_formula
				order by c.id";
		return $this->db->query($sql);
	}
	function rekap_panelis($id_formula,$ke)
	{
		$sql="
			select *
			from penilaian_hdr
			where id_formula=$id_formula and ke=$ke";
		return $this->db->query($sql);
	}
	function rekap_formula_terbaik($id_item)
	{
		$sql="
			select *
			from formula_terbaik_hdr
			where id_item=$id_item
			order by tanggal desc";
		return $this->db->query($sql);
	}
	function rekap_formula_terbaik_date($id_item,$tgl,$tgl2)
	{
		$sql="
			select *
			from formula_terbaik_hdr
			where id_item=$id_item
			and tanggal between '$tgl' and '$tgl2'
			order by tanggal
			";
		return $this->db->query($sql);
	}
	
	function rekap_formula_terbaik_date2($id_item,$tgl,$tgl2)
	{
		$sql="
			
			EXEC tabel_terbaik $id_item,'$tgl','$tgl2'
			";
		return $this->db->query($sql);
	}
	function rekap_formula_5terbaik($id_item)
	{
		$sql="
			
			SELECT  *
				FROM
				(
					 SELECT TOP 5
						  * from formula_terbaik_hdr
						  where id_item=$id_item
						  ORDER BY tanggal desc
				) SQ
					  ORDER BY tanggal asc
			";
		return $this->db->query($sql);
	}
	
	function rekap_formula_5terbaik2($id_item)
	{
		$sql="
			
			EXEC tabel_5terbaik $id_item
			";
		return $this->db->query($sql);
	}
	
	function rekap_panelis_kompetitor($id_formula,$ke)
	{
		$sql="
			select *
			from penilaian_kompetitor_hdr
			where id_formula=$id_formula --and ke=$ke";
		return $this->db->query($sql);
	}
	function kesimpulan($id_formula,$ke)
	{
		$sql="
			select *
			from kesimpulan_internal
			where id_formula=$id_formula and ke=$ke";
		return $this->db->query($sql);
	}
		function list_panelis($id_formula)
	{
		$sql="
			select b.* from formula2 a,panelis b
			where a.id_item=b.id_item and a.id=$id_formula ";
		return $this->db->query($sql);
	}
	function list_panelis2($id_formula)
	{
		$sql="
			select b.* from kompetitor a,panelis b
			where a.id_produk=b.id_item and a.id_kompetitor=$id_formula ";
		return $this->db->query($sql);
	}
	function penilaian_hdr($nama,$tgl,$id_formula,$ke)
	{
		$sql="
			select * from penilaian_hdr
			where panelis='$nama' and tanggal='$tgl' and ke=$ke and id_formula=$id_formula
			order by id_penilai desc
			";
			
		return $this->db->query($sql);
	}
	function formula_terbaik_hdr($tgl,$id_item)
	{
		$sql="
			select * from formula_terbaik_hdr
			where tanggal='$tgl' and id_item=$id_item
			order by id desc
			";
			
		return $this->db->query($sql);
	}
	function penilaian_kompetitor_hdr($nama,$tgl,$id_formula,$ke)
	{
		$sql="
			select * from penilaian_kompetitor_hdr
			where panelis='$nama' and tanggal='$tgl' and ke=$ke and id_formula=$id_formula
				order by id_penilai desc
			";
		return $this->db->query($sql);
	}
	function hdr_formula($id_formula)
	{
		$sql="
		
			select * from formula2 a,produk b,lineproduk c
			where a.id_item=b.id and a.id=$id_formula and b.line=c.id_lp";
		return $this->db->query($sql);
	}
	function cek_status($kolom,$id_formula)
	{
			$sql="		
					select $kolom as status from formula2 a
					where a.id=$id_formula";
		return $this->db->query($sql);
	}
	function hdr_penilaian2($id)
	{
		$sql="
			select * from penilaian_hdr a, formula2 b, produk c,lineproduk d
			where a.id_formula=b.id and b.id_item=c.id 
			and c.line=d.id_lp and a.id_penilai=$id
			";
		return $this->db->query($sql);
	}
	function hdr_penilaian_kompetitor2($id)
	{
		$sql="
			select *,b.status as status_kompetitor from penilaian_kompetitor_hdr a, kompetitor b, produk c,lineproduk d
			where a.id_formula=b.id_kompetitor and b.id_produk=c.id 
			and c.line=d.id_lp and a.id_penilai=$id
			";
		return $this->db->query($sql);
	}
	function dtl_penilaian2($id)
	{
		$sql="
			select d.*, b.id_item,a.subvar,a.id,a.varr,a.skala as skala_std,c.*
			from penilaian a
			join formula2 b on a.id_item=b.id_item
			join penilaian_hdr c on b.id=c.id_formula and id_penilai=$id
			left join penilaian_dtl d on c.id_penilai=d.id_hdr and d.id_penilaian=a.id
			order by a.varr, a.id
			";
		return $this->db->query($sql);
	}
	function penilaian_formula_list($id_formula,$ke)
	{
			$sql="
			
			select c.*,a.*,b.* from penilaian_dtl a, penilaian b,penilaian_hdr c
			where a.id_penilaian=b.id
			and a.id_hdr=c.id_penilai and c.id_formula=$id_formula and ke=$ke
			order by tanggal,panelis,id_penilaian
			";
		return $this->db->query($sql);
	}
	function penilaian_formula_list2($id_formula,$ke)
	{
			$sql="
			
			
			select c.panelis,c.tanggal,c.tgl_real,a.varr,a.subvar,d.nilai,d.skala,d.keterangan
			from penilaian a
			join formula2 b on a.id_item=b.id_item and b.id=$id_formula
			join penilaian_hdr c on c.id_formula=b.id and ke=$ke
			left join penilaian_dtl d on a.id=d.id_penilaian and d.id_hdr=c.id_penilai
			order by c.tanggal,c.panelis,a.varr,a.id
			";
		return $this->db->query($sql);
	}
	function penilaian_formula_list3($id_formula,$ke)
	{
			$sql="
			
			
				select c.panelis,c.tanggal,c.tgl_real,a.varr,a.subvar,d.nilai,d.skala,d.keterangan
				from penilaian a
				join formula2 b on a.id_item=b.id_item and b.id=$id_formula
				join penilaian_hdr c on c.id_formula=b.id and ke=$ke
				left join penilaian_dtl d on a.id=d.id_penilaian and d.id_hdr=c.id_penilai
				order by a.varr,a.id,c.tanggal,c.panelis
			";
		return $this->db->query($sql);
	}
	function penilaian_kompetitor_list($id_formula)
	{
			$sql="
			
			
			select c.panelis,c.tanggal,c.tgl_real,a.varr,a.subvar,d.nilai,d.skala,d.keterangan,c.kesimpulan
			from penilaian a
			join kompetitor b on a.id_item=b.id_produk and b.id_kompetitor=$id_formula
			join penilaian_kompetitor_hdr c on b.id_kompetitor=c.id_formula
			left join penilaian_kompetitor_dtl d on d.id_hdr=c.id_penilai and d.id_penilaian=a.id
			order by c.tanggal,c.panelis,a.varr,a.id

			";
		return $this->db->query($sql);
	}
	function dtl_penilaian3($id,$ke)
	{
		$sql="select  a.*,b.id_item,b.subvar,b.varr,b.skala as skala_std,c.*,c.action_plan as saran,d.*
			, STUFF((SELECT ',' + y.masalah FROM penilaian_masalah  x, master_masalah y
			where x.id_hdr=c.id_formula and x.id_masalah=y.id_masalah and x.ke=$ke FOR XML PATH('')),1,1,'') as masalah
			 from penilaian_dtl a, penilaian b,penilaian_hdr c
			 left join kesimpulan_internal d on c.id_formula=d.id_formula and d.ke=$ke
			where a.id_hdr=$id and a.id_penilaian=b.id 
			and a.id_hdr=c.id_penilai 
			order by varr,id_penilaian";
					return $this->db->query($sql);

	}
	function dtl_formula_terbaik($id)
	{
		$sql="
				select a.id,a.subvar,a.varr,e.nama_item,b.tanggal,b.id_item,c.id_formula,c.id_penilaian,d.kode
				from penilaian a
				join produk e on a.id_item=e.id
				join formula_terbaik_hdr b on a.id_item=b.id_item and b.id=$id
				left join formula_terbaik_dtl c on a.id=c.id_penilaian and b.id=c.id_hdr
				left join formula2 d on c.id_formula=d.id
				order by varr,a.id";
					return $this->db->query($sql);

	}
	function dtl_subvar_formula_terbaik($id_formula,$id_penilaian)
	{
		$sql="
		select *,c.skala as skala_std,b.skala as skala from penilaian_hdr a, penilaian_dtl b,penilaian c
		where b.id_penilaian=$id_penilaian and a.id_formula=$id_formula 
		and b.id_penilaian=c.id and a.id_penilai=b.id_hdr
		order by a.ke,a.tanggal,a.panelis";
					return $this->db->query($sql);

	}
	function dtl_penilaian_kompetitor($id,$ke)
	{
		$sql="			
			select *,d.skala as skala,a.skala as skala_std 
			from penilaian a
			join kompetitor b on a.id_item=b.id_produk
			join penilaian_kompetitor_hdr c on c.id_formula=b.id_kompetitor and c.id_penilai=$id --and c.ke=$ke
			left join penilaian_kompetitor_dtl d on d.id_hdr=c.id_penilai and d.id_penilaian=a.id
			order by c.tanggal,c.panelis,a.varr,a.id
			";
		return $this->db->query($sql);
	}
	function get_penilaian_masalah($id,$ke)
	{
		$sql="
			
			select * from penilaian_masalah a, master_masalah b
			where a.id_masalah=b.id_masalah and a.id_hdr=$id
			and a.ke=$ke
			";
		return $this->db->query($sql);
	}
	function get_kesimpulan($id,$ke)
	{
		$sql="
			select * from kesimpulan_internal
			where id_formula=$id and ke=$ke";
		return $this->db->query($sql);
	}
	function delete_masalah($id,$ke)
	{
		$sql="
			delete from penilaian_masalah
			where id_hdr=$id and ke=$ke";
		return $this->db->query($sql);
	}
	function id_kesimpulan_ts()
	{
		$sql="select top 1 id from kesimpulan_ts order by id desc";
		return $this->db->query($sql);
	}

	function hdr_kesimpulan_ts($id_formula,$ke)
	{
		$sql="
			select id_formula,panelis,tanggal,ke,id
			from kesimpulan_ts
			where id_formula=$id_formula and ke=$ke
			group by id_formula,panelis,tanggal,ke,id";
		return $this->db->query($sql);
	}
	function kesimpulan_ts($id)
	{
		$sql="
			select *
			from kesimpulan_ts
			where id=$id ";
		return $this->db->query($sql);
	}
	function hdr_kesimpulan_ts2($id)
	{
		$sql="
			select panelis,parameter from kesimpulan_ts a, formula2 b
			where a.id_formula=b.id
			and b.id_item=$id
			group by panelis,parameter
			order by panelis";
		return $this->db->query($sql);
	}
	function edit_kesimpulan_ts($id,$parameter)
	{
		$sql="
			select*
			from kesimpulan_ts
			where id=$id
			and parameter='$parameter'
			";
		return $this->db->query($sql);
	}
	function kesimpulan_ts_dtl($id,$panelis,$parameter)
	{
		$sql="
			select * from kesimpulan_ts
			where id_formula=$id and panelis='$panelis' and parameter='$parameter'
			";
		return $this->db->query($sql);
	}
	function delete_kesimpulan_ts($id)
	{
		$sql="
			delete
			from kesimpulan_ts
			where id=$id
			";
		return $this->db->query($sql);
	}
	function tabel_temp1($item,$ke)
	{
		$sql="select panelis,varr,subvar
			from penilaian_hdr a, penilaian_dtl b
			,penilaian c,produk d,formula2 e
			where a.id_penilai=b.id_hdr and ke=$ke and b.id_penilaian=c.id
			and c.id_item=d.id and a.id_formula=e.id and d.id=$item
			group by panelis,varr,subvar";
		return $this->db->query($sql);
	}
	function tabel_kode($item,$ke)
	{
		$sql="SELECT *
				FROM
				(
					 SELECT TOP 3
						  b.id as id_formula,b.kode,a.tanggal
					 FROM
						penilaian_hdr a, formula2 b
								where a.id_formula=b.id and b.id_item=$item
								and ke=$ke
					group by b.id,b.kode,a.tanggal
					 ORDER BY tanggal DESC,kode DESC
				) SQ
				ORDER BY tanggal ASC,kode asc
				";
		return $this->db->query($sql);
	}
	function tabel_kode10($item,$ke)
	{
		$sql="SELECT *
				FROM
				(
					 SELECT TOP 10
						  b.id as id_formula,b.kode,a.tanggal
					 FROM
						penilaian_hdr a, formula2 b
								where a.id_formula=b.id and b.id_item=$item
								and ke=$ke
					group by b.id,b.kode,a.tanggal
					 ORDER BY tanggal DESC,kode DESC
				) SQ
				ORDER BY tanggal ASC,kode ASC
				";
		return $this->db->query($sql);
	}
	
	function tabel_kode_kompetitor($item)
	{
		$sql="SELECT
					b.id_kompetitor as id_formula,b.nama as kode,a.tanggal,b.status,b.keterangan
					FROM
					penilaian_kompetitor_hdr a, kompetitor b
					where a.id_formula=b.id_kompetitor and b.id_produk=$item
					and b.status=1
					group by b.id_kompetitor,b.nama,a.tanggal,b.status,b.keterangan
					ORDER BY tanggal DESC
				
				";
		return $this->db->query($sql);
	}
	function tabel_temp3($kode,$panelis,$subvar,$item,$ke,$tgl)
	{
		$sql="
				
				select e.kode,a.tanggal,a.panelis,c.varr,c.subvar,b.nilai,b.keterangan,b.skala
				from penilaian_hdr a, penilaian_dtl b
				,penilaian c,produk d,formula2 e
				where a.id_penilai=b.id_hdr and ke=$ke and b.id_penilaian=c.id
				and c.id_item=d.id and a.id_formula=e.id and d.id=$item
				and kode='$kode' and panelis='$panelis' and subvar='$subvar'
				and a.tanggal='$tgl'
				";
		return $this->db->query($sql);
	}
	function tabel_temp3_kompetitor($kode,$panelis,$subvar,$item,$ke,$tgl)
	{
		$sql="
				
				select e.nama as kode,a.tanggal,a.panelis,c.varr,c.subvar,b.nilai,b.keterangan
				from penilaian_kompetitor_hdr a, penilaian_kompetitor_dtl b
				,penilaian c,produk d,kompetitor e
				where a.id_penilai=b.id_hdr and b.id_penilaian=c.id
				and c.id_item=d.id and a.id_formula=e.id_kompetitor and d.id=$item
				and e.nama='$kode' and panelis='$panelis' and subvar='$subvar'
				and a.tanggal='$tgl'
				";
		return $this->db->query($sql);
	}
	function tabel_avg_param($item,$ke,$subvar,$kode,$tgl)
	{
		$sql="select e.kode,c.subvar,avg(b.nilai) as nilai
				from penilaian_hdr a, penilaian_dtl b
				,penilaian c,produk d,formula2 e
				where a.id_penilai=b.id_hdr and a.ke=$ke
				and b.id_penilaian=c.id
				and c.id_item=d.id and a.id_formula=e.id and d.id=$item
				and kode='$kode' and subvar='$subvar' and a.tanggal='$tgl'
				group by  e.kode,c.subvar ";
		return $this->db->query($sql);
	}
	function tabel_avg_param_kompetitor($item,$subvar,$kode,$tgl)
	{
		$sql="select e.nama as kode,c.subvar,avg(b.nilai) as nilai
				from penilaian_kompetitor_hdr a, penilaian_kompetitor_dtl b
				,penilaian c,produk d,kompetitor e
				where a.id_penilai=b.id_hdr
				and b.id_penilaian=c.id
				and c.id_item=d.id and a.id_formula=e.id_kompetitor and d.id=$item
				and e.nama='$kode' and subvar='$subvar' and b.nilai!=0 and a.tanggal='$tgl'
				group by  e.nama,c.subvar
				";
		return $this->db->query($sql);
	}
	function tabel_dtl($item,$ke,$subvar)
	{
		$sql="select panelis,varr,subvar
			from penilaian_hdr a, penilaian_dtl b
			,penilaian c,produk d,formula2 e
			where a.id_penilai=b.id_hdr and ke=$ke and b.id_penilaian=c.id
			and c.id_item=d.id and a.id_formula=e.id and d.id=$item 
			and subvar='$subvar'
			group by panelis,varr,subvar";
		return $this->db->query($sql);
	}
	function tabel_dtl2($item,$ke,$kode,$tgl)
	{
		$sql="								
			
			select d.id,d.nama_item,e.kode,a.ke,a.panelis,c.varr,c.subvar,b.nilai,b.skala
			,b.keterangan,e.tgl_riset,e.risetman_hdr,f.risetman,e.tujuan
			from penilaian c
			join formula2 e on c.id_item=e.id_item and e.kode='$kode'
			join produk d on e.id_item=d.id and d.id=$item
			join risetman f on e.risetman=f.id_risetman
			join penilaian_hdr a on a.id_formula=e.id and ke=$ke and a.tanggal='$tgl'
			left join penilaian_dtl b on  b.id_penilaian=c.id and b.id_hdr=a.id_penilai	
			order by panelis,varr,subvar
";
		return $this->db->query($sql);
	}
	function tabel_dtl2_kompetitor($id_formula,$tgl)
	{
		$sql="	
			
				select d.id,d.nama_item,e.nama,a.ke,a.panelis,c.varr,c.subvar,b.nilai,b.skala
			,b.keterangan,e.keterangan as tujuan
			from penilaian c
			join kompetitor e on c.id_item=e.id_produk and e.id_kompetitor=$id_formula
			join produk d on e.id_produk=d.id
			join penilaian_kompetitor_hdr a on a.id_formula=e.id_kompetitor and a.tanggal='$tgl'
			left join penilaian_kompetitor_dtl b on  b.id_penilaian=c.id and b.id_hdr=a.id_penilai	
			order by panelis,varr,subvar";
		return $this->db->query($sql);
	}
	function hdr_date($tgl,$tgl2,$item)
	{
		$sql="select id_item,nama_item,id_formula,kode,ke,c.tanggal as tanggal
			from produk a,formula2 b,penilaian_hdr c
			where a.id=$item and a.id=b.id_item and b.id=c.id_formula
			and c.tanggal between '$tgl' and '$tgl2'
			and c.ke=3
			group by id_item,nama_item,id_formula,kode,ke,c.tanggal
			order by tanggal
			";
			return $this->db->query($sql);
	}
	function hdr_date2($tgl,$tgl2,$item)
	{
		$sql="select id_item,nama_item,b.id as id_formula,kode,MAX(ke) as ke,MAX(c.tanggal) as tanggal
			from produk a,formula2 b
			left join penilaian_hdr c on b.id=c.id_formula
			where a.id=$item and a.id=b.id_item
			and b.tgl_riset between '$tgl' and '$tgl2'
			group by id_item,nama_item,b.id,kode
			order by tanggal
			";
			return $this->db->query($sql);
	}
	function hdr_date3($tgl,$tgl2,$item,$risetman,$ke)
	{
		if($ke!=0)
		{
			$c="and c.ke=$ke";
		}
		else
		{
			$c="";
		}
		$sql="select id_item,nama_item,b.id as id_formula,kode,MAX(ke) as ke,MAX(c.tanggal) as tanggal
			from produk a,formula2 b
			left join penilaian_hdr c on b.id=c.id_formula
			where a.id=$item and a.id=b.id_item $c
			and b.tgl_riset between '$tgl' and '$tgl2' and b.risetman_hdr='$risetman'
			group by id_item,nama_item,b.id,kode
			order by tanggal
			";
			return $this->db->query($sql);
	}
	function hdr_dates3($tgl,$tgl2,$item,$risetman,$ke)
	{
		if($ke!=0)
		{
			$c="and c.ke=$ke";
		}
		else
		{
			$c="";
		}
		$sql="select id_item,nama_item,b.id as id_formula,kode,MAX(ke) as ke,MAX(c.tanggal) as tanggal
			from produk a,formula2 b
			join risetman_formula e on b.id=e.id_formula
			left join penilaian_hdr c on b.id=c.id_formula
			where a.id=$item and a.id=b.id_item $c
			and b.tgl_riset between '$tgl' and '$tgl2' and e.risetman='$risetman'
			group by id_item,nama_item,b.id,kode
			order by tanggal
			";
			return $this->db->query($sql);
	}
	function nilai_ldr($item,$ke,$tgl,$tgl2,$risetman)
	{
			$sql=" exec tabel_ldr $item,$ke,'$tgl','$tgl2','$risetman'

			";
			return $this->db->query($sql);
	}
	function hdr_penilaian_risetman($tgl,$tgl2)
	{
		$sql="select a.status,b.risetman_hdr as risetman,b.id_item,a.nama_item,c.lineproduk,a.line, COUNT(b.kode) as jumlah
from produk a, formula2 b, lineproduk c, risetman d
where a.id=b.id_item and a.line=c.id_lp and a.risetman=d.id_risetman
and b.tgl_riset between '$tgl' and '$tgl2'
group by a.status,b.risetman_hdr,b.id_item,a.nama_item,c.lineproduk,a.line
			";
			return $this->db->query($sql);
	}
	function hdr_penilaian_risetmans($tgl,$tgl2)
	{
		$sql="select a.status,d.risetman as risetman,b.id_item,a.nama_item,c.lineproduk,a.line, COUNT(b.kode) as jumlah
				from produk a, formula2 b, lineproduk c, risetman_formula d
				where a.id=b.id_item and a.line=c.id_lp and b.id=d.id_formula
				and b.tgl_riset between '$tgl' and '$tgl2'
				group by a.status,d.risetman,b.id_item,a.nama_item,c.lineproduk,a.line				
							";
			return $this->db->query($sql);
	}
	function hdr_penilaian_risetmans_akses($tgl,$tgl2)
	{
		$user=$this->session->userdata('nama_seas');//ganti
		$sql="select a.status,d.risetman as risetman,b.id_item,a.nama_item,
				c.lineproduk,a.line, COUNT(b.kode) as jumlah 
				from produk a, formula2 b, lineproduk c, risetman_formula d, akses_item e
				
				where a.id=b.id_item and a.line=c.id_lp and b.id=d.id_formula
				and b.tgl_riset between '$tgl' and '$tgl2' and e.item=a.id and e.akses=1 
				and e.username='$user'
				group by a.status,d.risetman,b.id_item,a.nama_item,c.lineproduk,a.line				
							";
			return $this->db->query($sql);
	}
	function hdr_penilaian_risetman2($tgl,$tgl2,$item,$risetman,$ke)
	{
		if($ke!=0)
		{
			$c="and d.ke=$ke";
		}
		else
		{
			$c="";
		}
		$sql="select a.id,a.nama_item,a.awal_riset,
			b.lineproduk,c.risetman_hdr,a.kompetitor,
			min(c.tgl_riset) as tgl_awal,
			MAX(d.tanggal) as tgl_panelis
			from produk a,  lineproduk b, formula2 c
			left join penilaian_hdr d on c.id=d.id_formula $c
			where a.line=b.id_lp and a.id=c.id_item
			and a.id=$item
			and c.tgl_riset between '$tgl' and '$tgl2' 
			and c.risetman_hdr='$risetman'			
			group by a.id,a.nama_item,a.awal_riset,
			b.lineproduk,c.risetman_hdr,a.kompetitor";
			return $this->db->query($sql);
	}
	function hdr_penilaian_risetmans2($tgl,$tgl2,$item,$risetman,$ke)
	{
		if($ke!=0)
		{
			$c="and d.ke=$ke";
		}
		else
		{
			$c="";
		}
		$sql="select a.id,a.nama_item,a.awal_riset,
			b.lineproduk,e.risetman,a.kompetitor,
			min(c.tgl_riset) as tgl_awal,
			MAX(d.tanggal) as tgl_panelis
			from produk a,  lineproduk b, formula2 c
			join risetman_formula e on c.id=e.id_formula and e.risetman='$risetman'			
			left join penilaian_hdr d on c.id=d.id_formula  $c
			where a.line=b.id_lp and a.id=c.id_item
			and a.id=$item  
			and c.tgl_riset between '$tgl' and '$tgl2' 
			
			group by a.id,a.nama_item,a.awal_riset,
			b.lineproduk,e.risetman,a.kompetitor";
			return $this->db->query($sql);
	}
	function jum_panelis_date($tgl,$tgl2,$item,$risetman,$ke)
	{
		$sql="select a.nama_item,b.id,c.ke
			from produk a, formula2 b, penilaian_hdr c 
			where a.id=b.id_item and b.id=c.id_formula and
			a.id=$item and b.tgl_riset between '$tgl' and '$tgl2' 
			and ke=$ke and b.risetman_hdr='$risetman'
			group by a.nama_item,b.id,c.ke";
			return $this->db->query($sql);
	}
	function jum_panelis_dates($tgl,$tgl2,$item,$risetman,$ke)
	{
		$sql="select a.nama_item,b.id,c.ke
			from produk a, formula2 b, penilaian_hdr c , risetman_Formula d
			where a.id=b.id_item and b.id=c.id_formula and b.id=d.id_Formula and
			a.id=$item and b.tgl_riset between '$tgl' and '$tgl2' 
			and ke=$ke and d.risetman='$risetman'
			group by a.nama_item,b.id,c.ke";
			return $this->db->query($sql);
	}
	function lama_waktu($line)
	{
		$sql="
select d.lineproduk,a.id,a.nama_item,a.awal_riset, DateDiff (Day,a.awal_riset,GETDATE()) as lama,
				MAX(c.tanggal) as tgl_panelis
				from produk a, lineproduk d, formula2 b
				left join penilaian_hdr c on  b.id=c.id_formula  and c.ke=3
				where a.id=b.id_item and a.line=d.id_lp and 
				a.line=$line
				group by d.lineproduk,a.id,a.nama_item,a.awal_riset
				";
		return $this->db->query($sql);
	}
		function lama_waktu_($line)
	{
		$sql="select d.lineproduk,a.id,a.nama_item,a.awal_riset, DateDiff (Day,a.awal_riset,GETDATE()) as lama,
				MAX(c.tanggal) as tgl_panelis,MAX(c.tgl_real) as tgl_real
				,a.status,a.tgl_status
				,STUFF((SELECT distinct ',' + z.risetman
					FROM  risetman_hdr z
					 where z.id_item=a.id
					FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)') 
				,1,1,'') as risetman
				from produk a , lineproduk d, formula2 b
				left join penilaian_hdr c on  b.id=c.id_formula  and c.ke=3
				where a.id=b.id_item and  a.line=d.id_lp 
				and	a.line in ($line)
				group by d.lineproduk,a.id,a.nama_item,a.awal_riset,a.status,a.tgl_status
				";
		return $this->db->query($sql);
	}
		function lama_waktu_akses($line)
	{
		$user=$this->session->userdata('nama_seas');//ganti
		$sql="
				select d.lineproduk,a.id,a.nama_item,a.awal_riset, DateDiff (Day,a.awal_riset,GETDATE()) as lama,
				MAX(c.tanggal) as tgl_panelis,MAX(c.tgl_real) as tgl_real
				,a.status,a.tgl_status
				,STUFF((SELECT distinct ',' + z.risetman
					FROM  risetman_hdr z
					 where z.id_item=a.id
					FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)') 
				,1,1,'') as risetman
				from produk a , lineproduk d, akses_item e, formula2 b
				left join penilaian_hdr c on  b.id=c.id_formula  and c.ke=3
				where a.id=b.id_item and  a.line=d.id_lp and a.id=e.item and akses=1
				and e.username='$user'
				and	a.line in ($line)
				group by d.lineproduk,a.id,a.nama_item,a.awal_riset,a.status,a.tgl_status	
				";
		return $this->db->query($sql);
	}
	function lama_waktu2($item,$ke)
	{
		if($ke==0)
		{
			$c="";
		}
		else
		{
			$c=" and c.ke=$ke";
		}
		$sql="select a.id,a.nama_item,a.awal_riset, DateDiff (Day,a.awal_riset,GETDATE()) as lama,
				MAX(c.tanggal) as tgl_panelis
				from produk a, formula2 b
				left join penilaian_hdr c on  b.id=c.id_formula $c
				where a.id=b.id_item and
				a.id=$item
				group by a.id,a.nama_item,a.awal_riset
				";
		return $this->db->query($sql);
	}
	function rekap_bahan_produk($id_kategori,$tgl,$tgl2)
	{
		$sql="
			select a.kode_bahan,b.kategori,
		STUFF((SELECT distinct ',' + z.nama_item
					FROM  bahan_formula x, formula2 y, produk z
					 where x.id_formula=y.id and y.id_item=z.id
					and x.kode_bahan=a.kode_bahan 
					FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)') 
				,1,1,'') as produk
		from bahan_formula a, bahan b,kategori c, formula2 d, produk e
		where a.kode_bahan=b.kode and b.kategori=c.id_kategori
		and a.id_formula=d.id and d.id_item=e.id
		and b.kategori=$id_kategori
		and d.tgl_riset between '$tgl' and '$tgl2'
		group by a.kode_bahan,b.kategori
			";
		return $this->db->query($sql);
	}
	function rekap_progress()
	{
		$sql="select a.nama_item,max(b.tgl_riset) as tgl_formula
			,MAX( c.tanggal) as tgl_panelis
			from produk a, formula2 b
			left join penilaian_hdr c on b.id=c.id_formula
			where a.id=b.id_item
			group by a.nama_item";
		return $this->db->query($sql);
	}
	function panelis_ts($id_item,$ke)
	{
		$sql="select a.panelis from penilaian_hdr a, formula2 b
		where ke=$ke and a.id_formula=b.id and b.id_item=$id_item
		group by a.panelis";
		return $this->db->query($sql);
	}
	function saran($id_formula,$ke,$panelis)
	{
		$sql="select a.* from penilaian_hdr a, formula2 b
where ke=$ke and a.id_formula=b.id and a.id_formula=$id_formula and a.panelis='$panelis'";
		return $this->db->query($sql);
	}
	function rekap_bahan($id_item)
	{
		$sql="select a.kode_bahan,d.kategori
		from bahan_formula a, formula2 b, bahan c,kategori d
		where a.id_formula=b.id and a.kode_bahan=c.kode 
		 and d.id_kategori=c.kategori and b.id_item=$id_item
		group by a.kode_bahan,d.kategori";
		return $this->db->query($sql);
	}
	function kadar_bahan($id_formula,$kode_bahan)
	{
		$sql="
			select* from bahan_formula
			where id_formula=$id_formula and kode_bahan='$kode_bahan'";
		return $this->db->query($sql);
	}
	function formula_list_ex($id_item,$id_formula)
	{
		$sql="
			  select id from formula2
          where id_item=$id_item and kode is not NULL
          and id!=$id_formula";
		return $this->db->query($sql);
	}
	function cek_formula($id_formula,$id)
	{
		$sql="
			 select kode_bahan,kadar 
          from bahan_formula
          where id_formula=$id_formula
          and kode_bahan+' '+cast(kadar  as varchar(10))
          not in (select x.kode_bahan+' '+cast(x.kadar  as varchar(10))
          from bahan_formula x
          where id_formula=$id)";
		return $this->db->query($sql);
	}
	function cek_formula2($id_formula)
	{
		$sql="
			  select * from cek_formula a, formula2 b
			where a.id_sama=b.id and a.id_formula=$id_formula";
		return $this->db->query($sql);
	}
	
	function formula_sarana($id_formula,$id_sarana)
	{
		$sql="select * from sarana_formula a, sarana b
			where a.id_sarana=b.id_sarana
			and a.id_formula=$id_formula and a.id_sarana='$id_sarana'";
		return $this->db->query($sql);
	}
	function formula_sarana2($id)
	{
		$sql="select * from sarana_formula a, sarana b
			where a.id_sarana=b.id_sarana and id=$id";
		return $this->db->query($sql);
	}
	function formula_sarana_all($id_formula)
	{
		$sql="select * from sarana_formula a, sarana b
			where a.id_sarana=b.id_sarana
			and a.id_formula=$id_formula";
		return $this->db->query($sql);
	} 
	function sarana_formula($id_formula)
	{
		$sql="select STUFF((SELECT distinct ',' + y.sarana
					FROM  sarana_formula z, sarana y
					 where z.id_formula=$id_formula and z.id_sarana=y.id_sarana
					FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)') 
				,1,1,'') as sarana
";
		return $this->db->query($sql);
	} 
	function transfer_sarana($id_formula,$id_t_formula)
	{
		$sql="insert into sarana_formula
			select $id_formula as id_formula,id_sarana 
			from sarana_formula
			where id_formula=$id_t_formula";
		return $this->db->query($sql);
	}
	function cek_panelis($nama,$item)
	{
		$sql="select * from panelis
		where panelis='$nama' and id_item=$item";
		return $this->db->query($sql);
	}
	function formula_panelis($item,$ke)
	{
		$sql="
				select id_formula from penilaian_hdr a, formula2 b
				where  a.id_formula=b.id and b.id_item=$item and a.ke=$ke
				group by id_formula";
		return $this->db->query($sql);
	}
	function sumber_masalah($item,$ke,$tgl,$tgl2)
	{
		$sql="
				select a.id_masalah,masalah,count(id_hdr) as jum
				from penilaian_masalah a, master_masalah b, formula2 c
				where a.id_masalah=b.id_masalah and a.id_hdr=c.id and a.ke=$ke
				and c.id_item=$item and c.tgl_riset between '$tgl' and '$tgl2'
				group by a.id_masalah,masalah";
		return $this->db->query($sql);
		
	}
	function dtl_masalah($item,$ke,$masalah,$tgl,$tgl2)
	{
		$sql="
				select d.masalah,c.kode,b.deskripsi 
				from penilaian_masalah a, kesimpulan_internal b, formula2 c, master_masalah d
				where a.id_hdr=b.id_formula and a.ke=b.ke and a.id_hdr=c.id and b.id_formula=c.id and a.id_masalah=d.id_masalah
				and id_item=$item and a.ke=$ke and a.id_masalah=$masalah and c.tgl_riset between '$tgl' and '$tgl2'
		";
		return $this->db->query($sql);
	}
	function rekap_harian_risetman($risetman,$tgl,$tgl2)
	{
		$sql="
				exec rekap_panelis_harian '$risetman','$tgl','$tgl2'";
		return $this->db->query($sql);
	}
	function rekap_kontribusi_harian_risetman($risetman,$tgl,$tgl2)
	{
		$sql="
				exec rekap_kontribusi_panelis_harian '$risetman','$tgl','$tgl2'";
		return $this->db->query($sql);
	}
	function rekap_harian_risetman2($risetman,$tgl,$tgl2)
	{
		$sql="
				exec rekap_panelis_harian2 '$risetman','$tgl','$tgl2'";
		return $this->db->query($sql);
	}
	function rekap_harian_risetman3($risetman,$tgl,$tgl2)
	{
		$user=$this->session->userdata('nama_seas');//ganti
		$sql="
				exec rekap_panelis_harian3 '$risetman','$tgl','$tgl2','$user'";
		return $this->db->query($sql);
	}
	function rekap_kontribusi_harian_risetman2($risetman,$tgl,$tgl2)
	{
		$sql="
				exec rekap_kontribusi_panelis_harian2 '$risetman','$tgl','$tgl2'";
		return $this->db->query($sql);
	}
	function rekap_kontribusi_harian_risetman3($risetman,$tgl,$tgl2)
	{
		$user=$this->session->userdata('nama_seas');//ganti
		$sql="
				exec rekap_kontribusi_panelis_harian3 '$risetman','$tgl','$tgl2','$user'";
		return $this->db->query($sql);
	}
	function rekap_harian_produk($produk,$tgl,$tgl2)
	{
		$sql="
				exec rekap_produk_harian '$produk','$tgl','$tgl2'";
		return $this->db->query($sql);
	}
	function rekap_harian_produk2($produk,$tgl,$tgl2)
	{
		$sql="
				exec rekap_produk_harian2 '$produk','$tgl','$tgl2'";
		return $this->db->query($sql);
	}
	function rekap_kontribusi_harian_produk($produk,$tgl,$tgl2)
	{
		$sql="
				exec rekap_kontribusi_produk_harian '$produk','$tgl','$tgl2'";
		return $this->db->query($sql);
	}
	function rekap_kontribusi_harian_produk2($produk,$tgl,$tgl2)
	{
		$sql="
				exec rekap_kontribusi_produk_harian2 '$produk','$tgl','$tgl2'";
		return $this->db->query($sql);
	}
	function rekap_mingguan_risetman($risetman,$tgl,$tgl2)
	{
		$sql="
				exec rekap_risetman_mingguan '$risetman','$tgl','$tgl2'";
		return $this->db->query($sql);
	}
	function rekap_kontribusi_mingguan_risetman($risetman,$tgl,$tgl2)
	{
		$sql="
				exec rekap_kontribusi_risetman_mingguan '$risetman','$tgl','$tgl2'";
		return $this->db->query($sql);
	}
	function rekap_mingguan_risetman2($risetman,$tgl,$tgl2)
	{
		$sql="
				exec rekap_risetman_mingguan2 '$risetman','$tgl','$tgl2'";
		return $this->db->query($sql);
	}
	function rekap_mingguan_risetman3($risetman,$tgl,$tgl2)
	{
		$user=$this->session->userdata('nama_seas');//ganti
		$sql="
				exec rekap_risetman_mingguan3 '$risetman','$tgl','$tgl2','$user'";
		return $this->db->query($sql);
	}
	function rekap_kontribusi_mingguan_risetman2($risetman,$tgl,$tgl2)
	{
		$sql="
				exec rekap_kontribusi_risetman_mingguan2 '$risetman','$tgl','$tgl2'";
		return $this->db->query($sql);
	}
	function rekap_kontribusi_mingguan_risetman3($risetman,$tgl,$tgl2)
	{
		$user=$this->session->userdata('nama_seas');//ganti
		$sql="
				exec rekap_kontribusi_risetman_mingguan3 '$risetman','$tgl','$tgl2','$user'";
		return $this->db->query($sql);
	}
	function rekap_mingguan_produk($produk,$tgl,$tgl2)
	{
		$sql="
				exec rekap_produk_mingguan '$produk','$tgl','$tgl2'";
		return $this->db->query($sql);
	}
	function rekap_kontribusi_mingguan_produk($produk,$tgl,$tgl2)
	{
		$sql="
				exec rekap_kontribusi_produk_mingguan '$produk','$tgl','$tgl2'";
		return $this->db->query($sql);
	}
	function rekap_mingguan_produk2($produk,$tgl,$tgl2)
	{
		$sql="
				exec rekap_produk_mingguan2 '$produk','$tgl','$tgl2'";
		return $this->db->query($sql);
	}
	function rekap_kontribusi_mingguan_produk2($produk,$tgl,$tgl2)
	{
		$sql="
				exec rekap_kontribusi_produk_mingguan2 '$produk','$tgl','$tgl2'";
		return $this->db->query($sql);
	}
	function rekap_bulanan_risetman($risetman,$tgl,$tgl2)
	{
		$sql="
				exec rekap_risetman_bulanan '$risetman','$tgl','$tgl2'";
		return $this->db->query($sql);
	}
	function rekap_kontribusi_bulanan_risetman($risetman,$tgl,$tgl2)
	{
		$sql="
				exec rekap_kontribusi_risetman_bulanan '$risetman','$tgl','$tgl2'";
		return $this->db->query($sql);
	}
	function hdr_mingguan_risetman($panelis,$tgl,$tgl2)
	{
		$sql="
			select DATEADD(DAY, 2 - DATEPART(WEEKDAY, a.tgl_riset), CAST(a.tgl_riset AS DATE)) as tgl
			from formula2 a, risetman_formula b,splitstring('$panelis') d
			where a.id=b.id_formula and a.tgl_riset between '$tgl' and '$tgl2'
			and b.risetman=d.Name
			group by DATEADD(DAY, 2 - DATEPART(WEEKDAY, a.tgl_riset), CAST(a.tgl_riset AS DATE)) 
			order by DATEADD(DAY, 2 - DATEPART(WEEKDAY, a.tgl_riset), CAST(a.tgl_riset AS DATE))
			";
		return $this->db->query($sql);
	}
	function hdr_mingguan_risetman2($panelis,$tgl,$tgl2)
	{
		$user=$this->session->userdata('nama_seas');//ganti
		$sql="
			select DATEADD(DAY, 2 - DATEPART(WEEKDAY, a.tgl_riset), CAST(a.tgl_riset AS DATE)) as tgl
			from formula2 a, risetman_formula b,splitstring('$panelis') d,akses_item e
			where a.id=b.id_formula and a.tgl_riset between '$tgl' and '$tgl2'
			and b.risetman=d.Name and a.id_item=e.item and e.akses=1
			and e.username='$user'
			group by DATEADD(DAY, 2 - DATEPART(WEEKDAY, a.tgl_riset), CAST(a.tgl_riset AS DATE)) 
			order by DATEADD(DAY, 2 - DATEPART(WEEKDAY, a.tgl_riset), CAST(a.tgl_riset AS DATE))
			";
		return $this->db->query($sql);
	}
	function hdr_mingguan_kontribusi_risetman($panelis,$tgl,$tgl2)
	{
		$sql="
			
			select  DATEADD(DAY, 2 - DATEPART(WEEKDAY, a.tgl_riset), CAST(a.tgl_riset AS DATE)) as tgl
			from formula2 a,risetman b,splitstring('$panelis') d
			where a.risetman=b.id_risetman and b.risetman=d.Name
			 and a.tgl_riset between '$tgl' and '$tgl2'
			group by DATEADD(DAY, 2 - DATEPART(WEEKDAY, a.tgl_riset), CAST(a.tgl_riset AS DATE))
			order by DATEADD(DAY, 2 - DATEPART(WEEKDAY, a.tgl_riset), CAST(a.tgl_riset AS DATE))
			";
		return $this->db->query($sql);
	}
	function hdr_mingguan_kontribusi_risetman2($panelis,$tgl,$tgl2)
	{
		$user=$this->session->userdata('nama_seas');//ganti
		$sql="
			
			select  DATEADD(DAY, 2 - DATEPART(WEEKDAY, a.tgl_riset), CAST(a.tgl_riset AS DATE)) as tgl
			from formula2 a,risetman b,splitstring('$panelis') d,akses_item e
			where a.risetman=b.id_risetman and b.risetman=d.Name
			 and a.tgl_riset between '$tgl' and '$tgl2'
			 and a.id_item=e.item and e.akses=1
			 and e.username='$user'
			group by DATEADD(DAY, 2 - DATEPART(WEEKDAY, a.tgl_riset), CAST(a.tgl_riset AS DATE))
			order by DATEADD(DAY, 2 - DATEPART(WEEKDAY, a.tgl_riset), CAST(a.tgl_riset AS DATE))
			";
		return $this->db->query($sql);
	}
	function hdr_bulanan_risetman($panelis,$tgl,$tgl2)
	{
		$sql="
			select DATEFROMPARTS(year(tgl_riset),month(tgl_riset),1) as tgl
			from formula2 a, risetman_formula b,splitstring('$panelis') d
			where a.id=b.id_formula and a.tgl_riset between '$tgl' and '$tgl2'
			and b.risetman=d.Name
			group by DATEFROMPARTS(year(tgl_riset),month(tgl_riset),1)
			order by DATEFROMPARTS(year(tgl_riset),month(tgl_riset),1)
			";
		return $this->db->query($sql);
	}
	function hdr_bulanan_risetman2($panelis,$tgl,$tgl2)
	{
		$user=$this->session->userdata('nama_seas');//ganti
		$sql="
			select DATEFROMPARTS(year(tgl_riset),month(tgl_riset),1) as tgl
			from formula2 a, risetman_formula b,splitstring('$panelis') d,akses_item e
			where a.id=b.id_formula and a.tgl_riset between '$tgl' and '$tgl2'
			and b.risetman=d.Name and a.id_item=e.item and e.akses=1 and e.username='$user'
			group by DATEFROMPARTS(year(tgl_riset),month(tgl_riset),1)
			order by DATEFROMPARTS(year(tgl_riset),month(tgl_riset),1)
			";
		return $this->db->query($sql);
	}
	function hdr_bulanan_kontribusi_risetman($panelis,$tgl,$tgl2)
	{
		$sql="
			select  DATEFROMPARTS(year(tgl_riset),month(tgl_riset),1) as tgl 
			from formula2 a,risetman b,splitstring('$panelis') d
			where a.risetman=b.id_risetman and b.risetman=d.Name
			 and a.tgl_riset between '$tgl' and '$tgl2'
			group by DATEFROMPARTS(year(tgl_riset),month(tgl_riset),1)
			order by DATEFROMPARTS(year(tgl_riset),month(tgl_riset),1)
			";
		return $this->db->query($sql);
	}
	function hdr_bulanan_kontribusi_risetman2($panelis,$tgl,$tgl2)
	{
		$user=$this->session->userdata('nama_seas');//ganti
		$sql="
			select  DATEFROMPARTS(year(tgl_riset),month(tgl_riset),1) as tgl 
			from formula2 a,risetman b,splitstring('$panelis') d,akses_item e
			where a.risetman=b.id_risetman and b.risetman=d.Name
			 and a.tgl_riset between '$tgl' and '$tgl2' and a.id_item=e.item
			 and e.akses=1 and e.username='$user'
			group by DATEFROMPARTS(year(tgl_riset),month(tgl_riset),1)
			order by DATEFROMPARTS(year(tgl_riset),month(tgl_riset),1)
			";
		return $this->db->query($sql);
	}
	
	function rekap_bulanan_risetman2($risetman,$tgl,$tgl2)
	{
		$sql="
				exec rekap_risetman_bulanan2 '$risetman','$tgl','$tgl2'";
		return $this->db->query($sql);
	}
	function rekap_bulanan_risetman3($risetman,$tgl,$tgl2)
	{
		$user=$this->session->userdata('nama_seas');//ganti
		$sql="
				exec rekap_risetman_bulanan3 '$risetman','$tgl','$tgl2','$user'";
		return $this->db->query($sql);
	}
	function rekap_kontribusi_bulanan_risetman2($risetman,$tgl,$tgl2)
	{
		$sql="
				exec rekap_kontribusi_risetman_bulanan2 '$risetman','$tgl','$tgl2'";
		return $this->db->query($sql);
	}
	function rekap_kontribusi_bulanan_risetman3($risetman,$tgl,$tgl2)
	{
		$user=$this->session->userdata('nama_seas');//ganti
		$sql="
				exec rekap_kontribusi_risetman_bulanan3 '$risetman','$tgl','$tgl2','$user'";
		return $this->db->query($sql);
	}
	function rekap_bulanan_produk($produk,$tgl,$tgl2)
	{
		$sql="
				exec rekap_produk_bulanan '$produk','$tgl','$tgl2'";
		return $this->db->query($sql);
	}
	function rekap_kontribusi_bulanan_produk($produk,$tgl,$tgl2)
	{
		$sql="
				exec rekap_kontribusi_produk_bulanan '$produk','$tgl','$tgl2'";
		return $this->db->query($sql);
	}
	function hdr_mingguan_produk($produk,$tgl,$tgl2)
	{
		$sql="
			SELECT DATEADD(DAY, 2 - DATEPART(WEEKDAY, tgl_riset), CAST(tgl_riset AS DATE)) as tgl
			from formula2
			where tgl_riset between '$tgl' and '$tgl2' and id_item in ($produk)
			group by DATEADD(DAY, 2 - DATEPART(WEEKDAY, tgl_riset), CAST(tgl_riset AS DATE))
			";
		return $this->db->query($sql);
	}
	function hdr_bulanan_produk($produk,$tgl,$tgl2)
	{
		$sql="
			SELECT DATEFROMPARTS(year(tgl_riset),month(tgl_riset),1) as tgl
			from formula2
			where tgl_riset between '$tgl' and '$tgl2' and id_item in ($produk)
			group by DATEFROMPARTS(year(tgl_riset),month(tgl_riset),1)
			order by DATEFROMPARTS(year(tgl_riset),month(tgl_riset),1)";
		return $this->db->query($sql);
	}
	function rekap_bulanan_produk2($produk,$tgl,$tgl2)
	{
		$sql="
				exec rekap_produk_bulanan2 '$produk','$tgl','$tgl2'";
		return $this->db->query($sql);
	}
	function rekap_kontribusi_bulanan_produk2($produk,$tgl,$tgl2)
	{
		$sql="
				exec rekap_kontribusi_produk_bulanan2 '$produk','$tgl','$tgl2'";
		return $this->db->query($sql);
	}
	
	function laporan_all($id_item,$ke)
	{
		$sql="
				exec laporan_all $id_item,$ke";
		return $this->db->query($sql);
	}
	function awal_konsep($id_item)
	{
		$sql="exec awal_konsep $id_item";
		return $this->db->query($sql);
	}
	function konsep_sebelumnya($id_item)
	{
		$sql="exec konsep_sebelumnya $id_item";
		return $this->db->query($sql);
	}
	function hdr_kode_vs($ke,$kode1,$kode2,$kode3,$kode4,$kode5)
	{
		$sql="
			select id_formula,tanggal,b.kode
			 from penilaian_hdr a, formula2 b
			where 
			a.id_formula in ($kode1,$kode2,$kode3,$kode4,$kode5)
			and  ke=$ke and a.id_formula=b.id 
			group by  id_formula,tanggal,b.kode
			order by tanggal,kode";
			return $this->db->query($sql);
	}
	function hdr_kode_terbaik($ke,$id_item)
	{
		$sql="
			
				SELECT  b.id_formula,d.tanggal,c.kode
				FROM
				(
					 SELECT TOP 5
						  * from formula_terbaik_hdr
						  where id_item=$id_item
						  ORDER BY tanggal desc
				) SQ, formula_terbaik_dtl b, formula2 c, penilaian_hdr d
				where b.id_hdr=sq.id and c.id=b.id_formula and d.ke=$ke and d.id_formula=c.id
				group by b.id_formula,d.tanggal,c.kode";
			return $this->db->query($sql);
	}
	function hdr_kode_vs_komp($komp1,$komp2)
	{
		$sql="
			select id_kompetitor,a.tanggal,b.nama
			 from penilaian_kompetitor_hdr a, kompetitor b
			where 
			id_kompetitor in ($komp1,$komp2)
			and a.id_formula=b.id_kompetitor 
			group by  id_kompetitor,tanggal,b.nama
			order by tanggal,nama";
			return $this->db->query($sql);
	}
	function hdr_kode_vs_komp2($panelis_selected,$komp1,$komp2,$komp3,$komp4,$komp5)
	{
		$sql="
			select id_kompetitor,a.tanggal,b.nama
			 from penilaian_kompetitor_hdr a, kompetitor b
			where 
			id_kompetitor in ($komp1,$komp2,$komp3,$komp4,$komp5)
			and a.id_formula=b.id_kompetitor and a.ke=$panelis_selected
			group by  id_kompetitor,tanggal,b.nama
			order by tanggal,nama";
			return $this->db->query($sql);
	}
	function nilai_lpa($item,$ke)
	{
		$sql="
			exec tabel_lpa $item,$ke
			";
			return $this->db->query($sql);
	}
	function nilai_lpa2($item,$ke)
	{
		$sql="
			exec tabel_lpa2 $item,$ke
			";
			return $this->db->query($sql);
	}
	function nilai_kode_vs($item,$ke,$kode1,$kode2,$kode3,$kode4,$kode5)
	{
		$sql="
			exec tabel_vs $item,$ke,$kode1,$kode2,$kode3,$kode4,$kode5";
			return $this->db->query($sql);
	}
	
	function nilai_kode_vs2($item,$ke,$kode1,$kode2,$kode3,$kode4,$kode5)
	{
		$sql="
			exec tabel_vs2 $item,$ke,$kode1,$kode2,$kode3,$kode4,$kode5";
			return $this->db->query($sql);
	}
	function nilai_kode_vs_kompetitor($item,$ke,$komp1,$komp2,$kode1,$kode2,$kode3,$kode4,$kode5)
	{
		$sql="
			exec tabel_vs_kompetitor $item,$ke,$komp1,$komp2,$kode1,$kode2,$kode3,$kode4,$kode5";
			return $this->db->query($sql);
	}
	function nilai_kode_vs_kompetitor3($item,$ke,$komp1,$komp2,$komp3,$komp4,$komp5,$kode1,$kode2,$kode3,$kode4,$kode5)
	{
		$sql="
			exec tabel_vs_kompetitor3 $item,$ke,$komp1,$komp2,$komp3,$komp4,$komp5,$kode1,$kode2,$kode3,$kode4,$kode5";
			return $this->db->query($sql);
	}
	function nilai_kode_vs_kompetitor4($item,$ke,$komp1,$komp2,$komp3,$komp4,$komp5,$kode1,$kode2,$kode3,$kode4,$kode5)
	{
		$sql="
			exec tabel_vs_kompetitor4 $item,$ke,$komp1,$komp2,$komp3,$komp4,$komp5,$kode1,$kode2,$kode3,$kode4,$kode5";
			return $this->db->query($sql);
	}
	function nilai_kode_terbaik($item,$ke)
	{
		$sql="exec tabel_terbaik_nilai $item,$ke";
		return $this->db->query($sql);
	}
	function nilai_kode_terbaik2($item,$ke)
	{
		$sql="exec tabel_terbaik_nilai2 $item,$ke";
		return $this->db->query($sql);
	}
	function nilai_kode_vs_kompetitor2($item,$ke,$komp1,$komp2,$kode1,$kode2,$kode3,$kode4,$kode5)
	{
		$sql="
			exec tabel_vs_kompetitor2 $item,$ke,$komp1,$komp2,$kode1,$kode2,$kode3,$kode4,$kode5";
			return $this->db->query($sql);
	}
	function rekap_kode_vs($item,$ke,$kode1,$kode2,$kode3,$kode4,$kode5)
	{
		$sql="
			exec tabel_vs_rekap $item,$ke,$kode1,$kode2,$kode3,$kode4,$kode5";
			return $this->db->query($sql);
	}
	function range_panelis($item,$tgl,$tgl2)
	{
		$sql="select id_formula,b.kode,b.tgl_riset,
			max(CASE WHEN  ke=1 THEN a.tanggal ELSE null END) 'risetman',
			max(CASE WHEN  ke=2 THEN a.tanggal ELSE null END) 'internal',
			max(CASE WHEN  ke=3 THEN a.tanggal ELSE null END) 'ts'
			from penilaian_hdr a, formula2 b
			where id_item=$item and b.tgl_riset between '$tgl' and '$tgl2' and 
			a.id_formula=b.id
			group by id_formula,b.kode,b.tgl_riset
			";
		return $this->db->query($sql);
	}
	function rekap_all_kompetitor($line)
	{
		$sql="
				SELECT * FROM PRODUK b
				JOIN lineproduk c on b.line=c.id_lp and c.id_lp in ($line)
				left join kompetitor a on a.id_produk=b.id
			";
		return $this->db->query($sql);
	}
	function rekap_all_kompetitor_akses($line)
	{
		$user=$this->session->userdata('nama_seas');//ganti
		$sql="
				SELECT * FROM PRODUK b
				JOIN lineproduk c on b.line=c.id_lp and c.id_lp in ($line)
				join akses_item d on b.id=d.item and d.akses=1 and d.username='$user'
				left join kompetitor a on a.id_produk=b.id
			";
		return $this->db->query($sql);
	}
	function produk_by_line($line)
	{
		$sql="
				SELECT * FROM PRODUK 
				where line=$line
			";
		return $this->db->query($sql);
	}
	function act_panelis($tgl,$tgl2,$panelis)
	{
		$sql="
			
			SELECT * FROM LOG_ACT a,splitstring('$panelis') b
			where cast(tgl as date) between '$tgl' and '$tgl2'
			and a.pic=b.Name
			order by a.tgl
		";
		return $this->db->query($sql);
	}
	function pending_item($id_item)
	{
		$sql="
			
			SELECT * FROM pending where id_produk=$id_item
		";
		return $this->db->query($sql);
	}
	function pending_by_id($id)
	{
		$sql="
			
			SELECT * FROM pending where id=$id
		";
		return $this->db->query($sql);
	}
	function hari_pending($id)
	{
		$sql="
			select id_produk,
			sum(DateDiff (Day,tgl_awal ,case when tgl_akhir>GETDATE() then getdate() else tgl_akhir end)+1) as totalp
			from pending 
			where id_produk=$id and  tgl_awal<=GETDATE()
			group by id_produk";
			return $this->db->query($sql);

	}
	function pending_now($id)
	{
		$sql="
			select *
				from pending 
			where id_produk=$id and  GETDATE() between tgl_awal and tgl_akhir
			";
			return $this->db->query($sql);

	}
	function akses_item($id,$id_head)
	{
		$sql="
					
			WITH cte_org AS (

				SELECT       
					id, 
					Username,id_head
					
				FROM       
					login_mkt
					WHERE ID=$id_head
				UNION ALL
				SELECT 
					e.id, 
					e.Username,e.id_head
				FROM
					login_mkt e
					INNER JOIN cte_org o 
						ON o.id = e.ID_head
					
				
			)

			SELECT * FROM cte_org a, akses_item b,produk c
			where a.id=b.id_user and item=$id and b.item=c.id
			";
			return $this->db->query($sql);

	}
	function akses_lp2($id,$id_head)
	{
		$sql="
			WITH cte_org AS (

				SELECT       
					id, 
					Username,id_head,Group_produk
					
				FROM       
					login_mkt
					WHERE ID=$id_head
				UNION ALL
				SELECT 
					e.id, 
					e.Username,e.id_head,e.Group_produk
				FROM
					login_mkt e
					INNER JOIN cte_org o 
						ON o.id = e.ID_head
					
				
			)

			SELECT * FROM cte_org a, akses_lp b,lineproduk c
			where a.id=b.id_user and b.id_lp=$id and b.id_lp=c.id_lp
			and a.Group_produk=1
			";
			return $this->db->query($sql);

	}
	function akses_item2($user)
	{
		$sql="select a.*,b.* from akses_item a,produk b, akses_item c, login_mkt d
			where a.item=b.id and a.id_user=$user
			and a.id_user=d.id and c.id_user=d.id_head
			and c.item=b.id 
			and c.akses=1";
			return $this->db->query($sql);

	}
	function akses_lp($user)
	{
		$sql="select a.*,b.*,b.lineproduk as nama_item,b.id_lp as id from akses_lp a,lineproduk b, akses_lp c, login_mkt d
			where a.id_lp=b.id_lp and a.id_user=$user
			and a.id_user=d.id and c.id_user=d.id_head
			and c.id_lp=b.id_lp 
			and c.akses=1
				";
			return $this->db->query($sql);

	}
	function in_akses($id)
	{
		$sql="
			insert into akses_item
			select b.Username,$id,0,b.id from login_mkt b
			";
			return $this->db->query($sql);

	}
	function akses_menu($id_group)
	{
		$sql="
					SELECT * FROM list_menu a, akses_menu_group b
					where a.id=b.id_menu and b.id_group=$id_group
			";
			return $this->db->query($sql);
	}
	function update_akses_item($id_lp,$id_user,$flag)
	{
		$sql="
				update b set b.akses=$flag
				from produk a, akses_item b
				where line=$id_lp and a.id=b.item and b.id_user=$id_user
					
			";
			return $this->db->query($sql);
	}
	function update_akses_item2($id_item)
	{
		$sql="
			update a set a.akses=1
			from akses_item a, produk b, akses_lp c
			where a.item=b.id  and b.line=c.id_lp and a.id_user=c.id_user
			and c.akses=1 and b.id=$id_item
			";
		return $this->db->query($sql);

			
	}
	function update_lp_akses($id_lp,$id_item,$akses)
	{
		//id_lp lama gunakan akses=0
		//id_lp baru gunakan akses=1
		$sql="
			update b set akses=$akses
			from akses_lp a, akses_item b
			where a.id_lp=$id_lp and a.akses=1 and a.id_user=b.id_user
			and b.item=$id_item
			";
		return $this->db->query($sql);

			
	}
}
