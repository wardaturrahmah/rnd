<!DOCTYPE html>
<html>
<head>
	<title>PRINT NILAI PANELIS TASTE SPECIALIST</title>
</head>
<body>
 
	<center>
		<h2>FORMULA <?PHP echo $formula[0]->kode;?></h2>
	</center>
 
	<br/>
	<p>
		Nama Produk : <?PHP echo $formula[0]->nama_item;?>
	</p>
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
	<table border="1" width="100%">
		<thead>
		<th>Panelis</th>
		<th>Var</th>
		<th>Subvar</th>
		<th>Nilai</th>
		<th>Keterangan</th>
		</thead>
		<?php 
			$panelis1='';
			$panelis2='';
			$var1='';
			$var2='';
			$mer=array();
			foreach($formula as $form)
			{
				$panelis2=$form->panelis;
				if($panelis1!=$panelis2)
				{
					$pan=0;
				}
				$pan++;
				$mer[$form->panelis]=$pan;
				$panelis1=$panelis2;
				
				$var2=$form->varr;
				if($var1!=$var2)
				{
					$va=0;
				}
				$va++;
				$mer[$form->varr]=$va;
				$var1=$var2;
				
			}
			$panelis1='';
			$panelis2='';
			$var1='';
			$var2='';
			foreach($formula as $form)
			{
		?>
		<tr>
		<?php
			$panelis2=$form->panelis;
			if($panelis1!=$panelis2)
			{
		?>
			<td rowspan="<?php echo $mer[$form->panelis];?>"><?php echo $form->panelis; ?></td>

		<?php
			}
			$panelis1=$panelis2;
			$var2=$form->varr;
			if($var1!=$var2)
			{
		?>
				<td rowspan="<?php echo $mer[$form->varr]?>"><?php echo $form->varr; ?></td>
		<?php
			}
			$var1=$var2;
		?>
		
		<td><?php echo $form->subvar; ?></td>
		<td><?php echo round($form->nilai,2); ?></td>
		<td><?php echo $form->keterangan; ?></td>
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