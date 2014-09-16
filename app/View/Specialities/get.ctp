<?php /* Designed to be ajax-called */ ?>
{
<?php
	$first = true;
	foreach ($specialities as $speciality) {
		if (!$first)
			echo ",";
		else
			$first = false;
		echo '"'.$speciality['Speciality']['id'].'":{"name":"'.$speciality['Speciality']['name'].'"}';
	}
?>
}