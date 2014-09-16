<?php /* Designed to be ajax-called */ ?>
{
<?php
	$first = true;
	foreach ($doctors as $doctor) {
		if (!$first)
			echo ",";
		else
			$first = false;
		echo '"'.$doctor['Doctor']['id'].'":';
		echo '{';
			echo '"name":"'.$doctor['User']['name'].'",';
			echo '"surname":"'.$doctor['User']['surname'].'"';
		echo '}';
	}
?>
}