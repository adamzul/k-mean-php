<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<form method="post" enctype="multipart/form-data">
  <input type="file" name="fileToUpload" id="fileToUpload">
  <h3>centeroid:</h3>
  <input type="text" name="jumCenteroid" >
  
  <br><br>
  <input type="submit" name="submit" value="submit">
</form>
<?php
if(isset($_POST['submit']))
{
	$file = basename($_FILES["fileToUpload"]["name"]);
	$data = getDataFromFile($file);
	$centeroids = getCenteroidAwal($_POST['jumCenteroid'],$data);
	$jumCenteroid = count($centeroids);
	$number =1;
	$jumFitur = count($centeroids[0])-1;
	// $centeroids=array(array(3,3,3,3,'cluster'=>0),array(2,2,2,2,'cluster'=>1),array(1,1,1,1,'cluster'=>2));
	echo '<h3>inisialisasi centeroid:</h3>';
	echo '<table border="1">';
		
		myHeader($jumFitur);
		for ($i=0; $i < $jumCenteroid; $i++) { 
			# code...
			echo '<tr>' ;
			echo '<td>'.$i.'</td>';
			for ($j=0; $j < $jumFitur; $j++) { 
				# code...
				echo '<td> <b>'.$centeroids[$i][$j].'</b></td>';
			}
			echo '<td><b>0</b></td>';
			// var_dump($centeroids[$i]);
			// echo '<br>';
			echo '</tr>';
		}
		echo '</table>';
	do{
		$param =true;
		$temps = $centeroids;
		$jarakPerData = getJarak($centeroids,$data);
		$data = getCluster($jarakPerData,$data);
		// var_dump($data[7]);
		$centeroids = getCenteroid($centeroids,$data);
		for ($i=0; $i < count($centeroids); $i++) { 
			# code...
			for ($j=0; $j < count($centeroids[0])-1; $j++) { 
				# code...
				if(abs($temps[$i][$j]-$centeroids[$i][$j])>0)
				{
					$param = false;
				}
				// var_dump(abs($temps[$i][$j]-$centeroids[$i][$j]));
			}
			// echo '<br>';
		}
//////////////////////////tampilkan data centeroid////////////////////

		echo '<h3>centeroid baru (loop ke '.$number.'):</h3>';
		echo '<table border="1">';
		
		myHeader($jumFitur);
		for ($i=0; $i < count($centeroids); $i++) { 
			# code...
			echo '<tr>' ;
			echo '<td>'.$i.'</td>';
			for ($j=0; $j < $jumFitur; $j++) { 
				# code...
				echo '<td> <b>'.$centeroids[$i][$j].'</b></td>';
			}
			echo '<td><b>'.getJumlahAnggota($centeroids[$i],$data).'</b></td>';
			// var_dump($centeroids[$i]);
			// echo '<br>';
			echo '</tr>';
		}
		echo '</table>';
		echo '<br><br>';
//////////////////////////tampilkan data centeroid////////////////////

		$number++;
	}while($param == false);
//////////////////////////tampilkan jarak per data percenteroid////////////////////

	echo '<table border="1">';
	myHeaderData($jumCenteroid);
	for ($i=0; $i < count($jarakPerData); $i++) { 
		# code...
		echo '<tr>';
		echo '<td>'.$i.'</td>';
		for ($j=0; $j < $jumCenteroid; $j++) { 
			# code...
			echo '<td>'.$jarakPerData[$i][$j].'</td>';
		}
		echo '<td>'.$data[$i]['cluster'].'</td>';
		// var_dump($jarakPerData[$i]);
		// var_dump($data[$i]['cluster']);
		// echo '<br>';
		echo '</tr>';

	}
	echo '</table>';
//////////////////////////tampilkan jarak per data percenteroid////////////////////

	// var_dump($jarakPerData[0]);
	// 	var_dump($data[0]['cluster']);
	// 	echo '<br><br>';

}


?>

<?php
function getDataFromFile($file)
{
	$stringData = file_get_contents($file);

	$pecahPerdata = preg_split("/\r\n|\n|\r/", $stringData);
	$temps = array();$i=0;
	foreach ($pecahPerdata as $data) {
		# code...
		$pecahPerkolom = explode(',', $data);
		$pecahPerkolom['index'] = $i;

		array_push($temps, $pecahPerkolom);
		$i++;
	}
    return $temps;
}

function getCenteroidAwal($jumCenteroid,$data)
{
	$jumData = count($data);
	$jumFitur = count($data[0])-1;
	$minMaxPerFitur = array();
	
	for ($i=0; $i < $jumFitur ; $i++) { 
		# code...
		$min =0;
		$max = 0;
		for ($j=0; $j < $jumData; $j++) { 
			# code...
			if($j==0)
			{
				$min = $data[$j][$i];
			}
			if($min > $data[$j][$i])
			{
				$min = $data[$j][$i];
			}
			if($max < $data[$j][$i])
			{
				$max = $data[$j][$i];
			}
		}
		array_push($minMaxPerFitur, array($min,$max));
	}
	$centroids = array();
	for ($i=0; $i < $jumCenteroid ; $i++) { 
		# code...
		for ($j=0; $j < $jumFitur ; $j++) { 
			# code...
			$centroid[$j] = rand($minMaxPerFitur[$j][0]*100,$minMaxPerFitur[$j][1]*100)/100;
		}
		$centroid['cluster'] = $i;
		array_push($centroids, $centroid);

	}
	// var_dump($centroids[2]);
	return $centroids;

}

function getJarak($centeroids,$data)
{
	$jarakPerData = array();
	$jumCenteroid = count($centeroids);
	$jumData = count($data);
	$jumFitur = count($centeroids[0])-1;
	$jarakKeseluruhan = array();
	
	for ($i=0; $i < $jumData ; $i++) { 
		# code...
		$jaraks = array();
		for ($j=0; $j < $jumCenteroid; $j++) { 
			# code...
			$jarak = 0;
			for ($h=0 ;$h < $jumFitur; $h++) { 
				# code...
				$jarak = sqrt(pow($jarak,2)+pow(($data[$i][$h]-$centeroids[$j][$h]),2));

			}
			// $jarak = sqrt(pow(($data[$i][0]-$centeroids[$j][0]),2)+
			// 	pow(($data[$i][1]-$centeroids[$j][1]),2)+
			// 	pow(($data[$i][2]-$centeroids[$j][2]),2)+
			// 	pow(($data[$i][3]-$centeroids[$j][3]),2));
			$jaraks[$j] = $jarak;
			// echo $jarak.' - ';

		}
		// var_dump($jaraks);
		// echo '<br>';
		$jaraks['index'] =$i;
		array_push($jarakKeseluruhan, $jaraks); 
	}
	// for ($i=0; $i < $jumData; $i++) { 
	// 	# code...
	// 	var_dump($jarakKeseluruhan[$i]);
	// 	echo '<br>';
	// }
	// var_dump($jarakKeseluruhan[0]);
	// echo '<br><br>';
	return $jarakKeseluruhan;

}

function getCluster($jarakPerData,$data)
{
	$jumData = count($data);
	$jumCluster = count($jarakPerData[0])-1;
	for ($i=0; $i < $jumData; $i++) { 
		# code...
		$cluster = 0;
		for ($j=0; $j < $jumCluster-1; $j++) { 
			# code...
			if($jarakPerData[$i][$cluster]>$jarakPerData[$i][$j+1]){
				$cluster = $j+1;
			}
		}
		$data[$i]['cluster'] = $cluster;
	}
	// for ($i=0; $i < count($jarakPerData); $i++) { 
	// # code...
	// 	var_dump($jarakPerData[$i]);
	// 	echo $data[$i]['cluster'];
	// 	echo '<br>';
	// }	
	return $data;
}

function getCenteroid($centeroids,$data)
{

	$jumCenteroid = count($centeroids);
	// var_dump($centeroids[0]);
	// echo '<br>';
	// var_dump($data[0]);
	$jumData = count($data);
	$jumFitur = count($centeroids[0])-1;
	for ($h=0; $h < $jumCenteroid; $h++) { 
		# code...
		$jumDataPerCenteroid = array();
		for ($i=0; $i < $jumFitur; $i++) { 
			# code...
			$jumDataPerCenteroid[$i] = 0;
		}
		$jum =0;
		for ($i=0; $i < $jumData; $i++) { 
			# code...
			if($data[$i]['cluster'] == $centeroids[$h]['cluster'])
			{
				$jum++;
				for ($j=0; $j < $jumFitur; $j++) { 
					# code...
					$jumDataPerCenteroid[$j] = $jumDataPerCenteroid[$j]+$data[$i][$j]; 
					// var_dump($jumDataPerCenteroid[$j]);
				}

			}

			// var_dump($data[$i]['index']);	
		}

		// echo '<br>';
		// var_dump($jum);
		for ($j=0; $j < $jumFitur; $j++) { 
			# code...
			if($jum>0){
				$centeroids[$h][$j] = $jumDataPerCenteroid[$j] / $jum; 
			}
		}
	}
	return $centeroids;
}

function getJumlahAnggota($centeroid,$data)
{
	$jumData = count($data);
	$jum = 0;
	for ($i=0; $i < $jumData; $i++) { 
		# code...
		if($data[$i]['cluster'] == $centeroid['cluster'])
		{
			$jum++;
		}
	}
	return $jum;
}
function myHeader($jumFitur)
{
////////////////////header table///////////////////////////
		echo '<tr>' ;
		echo '<td>centeroid</td>';
		for ($j=0; $j < $jumFitur; $j++) { 
			# code...
			echo '<td>fitur ke <b>'.$j.'</b></td>';
		}
		echo '<td>jumlah anggota</td>';
		
		echo '</tr>';
	////////////////////header table///////////////////////////
}
function myHeaderData($jumCenteroid)
{
////////////////////header table///////////////////////////
		echo '<tr>' ;
		echo '<td>index data</td>';
		for ($j=0; $j < $jumCenteroid; $j++) { 
			# code...
			echo '<td>jarak dari centeroid ke <b>'.$j.'</b></td>';
		}
		echo '<td>cluster</td>';
		
		echo '</tr>';
	////////////////////header table///////////////////////////
}
?>
</body>
</html>