<h2>Rejestrowanie do lekarza</h2>

<?php
	$dvalues = array();
	foreach ($doctors as $d):
		$dvalues[$d['Doctor']['id']] = $this->User->fullName($d['User']);
	endforeach;
	$svalues = array();
	foreach ($specialities as $s) {
		$svalues[$s['Speciality']['id']] = $s['Speciality']['name'];
	}
?>
<div class="form">
	<?php echo $this->Form->create('Registration'); ?>
		<fieldset>
			<legend><?php echo __('Wypełnij proszę formularz'); ?></legend>
			<?php
				echo $this->Form->input('speciality_id', array('label' => __('Specjalność'), 'options' => $svalues, 'id' => 'specialityBox'));
				echo $this->Form->input('doctor_id', array('label' => __('Lekarz'), 'options' => $dvalues, 'id' => 'doctorBox'));
			?>
		</fieldset>
		<a href="#" id="resetlink">Resetuj ułatwienia powiązań specjalności</a>
	<?php echo $this->Form->end(__('Zapisz')); ?>
</div>

<script type="text/javascript">
	function changeDoctors(speciality_id) {
		var url;
		if (speciality_id >= 0)
			url = "<?php echo $this->Html->url(array('controller' => 'doctors', 'action' => 'getBySpeciality')); ?>/" + speciality_id;
		else
			url = "<?php echo $this->Html->url(array('controller' => 'doctors', 'action' => 'getAll')); ?>";
		$.ajax({
			url: url,
			success: function(data, status, xhr) {
					if (xhr.status == 200) {
						var dbox = $("#doctorBox");
						var selected = dbox.val();
						dbox.empty();
						data = $.parseJSON(data);
						for (var o in data) {
							option = $("<option></option>");
							option.val(o);
							option.text(data[o]["name"] + " " + data[o]["surname"]);
							if (o == selected)
								option.prop('selected', true);
							dbox.append(option);
						}
					}
				}
		});
	}
	function changeSpecialities(doctor_id) {
		var url;
		if (doctor_id >= 0)
			url = "<?php echo $this->Html->url(array('controller' => 'specialities', 'action' => 'getByDoctor')); ?>/" + doctor_id;
		else
			url = "<?php echo $this->Html->url(array('controller' => 'specialities', 'action' => 'getAll')); ?>";
		$.ajax({
			url: url,
			success: function(data, status, xhr) {
				if (xhr.status == 200) {
					var sbox = $("#specialityBox");
					var selected = sbox.val();
					sbox.empty();
					data = $.parseJSON(data);
					for (var o in data) {
						option = $("<option></option>");
						option.val(o);
						option.text(data[o]["name"]);
						if (o == selected)
							option.prop('selected', true);
						sbox.append(option);
					}
				}
			}
		});
	}
	function reset() {
		changeDoctors(-1);
		changeSpecialities(-1);
	}
	$().ready(function() {
		$("#resetlink").click(function(e) {
			e.preventDefault();
			reset();
		});
		$("#specialityBox").change(function() {
			changeDoctors(this.value);
		});
		$("#doctorBox").change(function() {
			changeSpecialities(this.value);
		});
	});	
</script>