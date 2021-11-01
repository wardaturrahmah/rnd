<!DOCTYPE html>
<html>
<head>
	<title>PRINT FORMULA</title>
</head>
<body>
 
	<center>
		<h2>FORMULA <?PHP echo $formula[0]->kode;?></h2>
	</center>
 
	<br/>
 
	<p>
		Kode Formula : <?PHP echo $formula[0]->kode;?>
	</p>
	<p>
		Tanggal Riset Formula : <?PHP echo date('d-m-Y',strtotime($formula[0]->tgl_riset));?>
	</p>
	<p>
		Risetman : <?PHP echo $formula[0]->risetman_hdr;?>
	</p>
	<p>
		Formula By : <?PHP echo $formula[0]->risetman;?>
	</p>
	<p>
		Tujuan : <?PHP echo $formula[0]->tujuan;?>
	</p>
	<p>
		Status : <?PHP 
		if($formula[0]->approve1==1)
		{
			$status="approve by risetman";
		}
		else if($formula[0]->approve1==-1)
		{
			$status="Drop by risetman";
		}
		if($formula[0]->approve2==1)
		{
			$status="approve by internal";
		}
		else if($formula[0]->approve1==-1)
		{
			$status="Drop by internal";
		}
		if($formula[0]->approve3==1)
		{
			$status="approve by Taste Specialist";
		}
		else if($formula[0]->approve3==-1)
		{
			$status="Drop by Taste Specialist";
		}
		echo $status;?>
	</p>
	<table border="1" width="100%">
		<thead>
		<th>Kode Bahan</th>
		<th>Kategori</th>
		<th>Kadar</th>
		</thead>
		<?php 
			foreach($formula as $formula)
			{
		?>
		<tr>
		<td><?php echo $formula->kode_bahan; ?></td>
		<td><?php echo $formula->kategori; ?></td>
		<td><?php echo round($formula->kadar,3); ?></td>
		</tr>
		<?php
			}
		?>
	</table>
	<script>
		window.print();
	</script>
	
</body>
</html>