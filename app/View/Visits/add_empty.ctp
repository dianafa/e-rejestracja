<h2>Dodawanie wizyty pacjenta nieznanego</h2>

<div class="form">
	<?php echo $this->Form->create('Visit'); ?>
	<fieldset>
		<?php
			$ds = array();
			foreach ($doctors as $doctor) {
				$ds[$doctor['Doctor']['id']] = $this->User->fullName($doctor['User']);
			}
			$ss = array();
			foreach ($specialities as $speciality) {
				$ss[$speciality['Speciality']['id']] = $speciality['Speciality']['name'];
			}
			echo $this->Form->input('speciality_id', array('label' => __('Specjalność'), 'options' => $ss, 'id' => 'specialityBox'));
			echo $this->Form->input('doctor_id', array('label' => __('Lekarz'), 'options' => $ds, 'id' => 'doctorBox'));
			echo $this->Form->input('time', array('label' => __('Czas wizyty'), 'type' => 'datetime', 'dateFormat' => 'DMY', 'timeFormat' => '24', 'id' => 'timeBox'));
			?><div id="calendar"></div><?php
			echo $this->Form->input('reason', array('label' => __('Przyczyna'), 'type' => 'text'));
			echo $this->Form->input('note', array('label' => __('Notatki')));
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
	
	function showCalendar() {
		$("#calendar").html('<p class="info">Proszę chwilę poczekać, trwa ładowanie kalendarza</p>');
		var doctor_id = $("#doctorBox").val();
		var year = $("#timeBoxYear").val();
		var month = $("#timeBoxMonth").val();
		var day = $("#timeBoxDay").val();
		$.ajax({
			url: "<?php echo $this->Html->url(array('controller' => 'doctors', 'action' => 'viewFreeWeek')); ?>/" + doctor_id,
			data: "data[beginning][year]=" + year + "&data[beginning][month]=" + month + "&data[beginning][day]=" + day,
			method: "post",
			success: function(data, status, xhr) {
				$("#calendar").html(data);
			},
			error: function(data, status, xhr) {
				$("#calendar").html('<p class="error">Nie udało się załadować kalendarza</p>');
			}
		});
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
			showCalendar();
		});
		$("#timeBoxDay").change(showCalendar);
		$("#timeBoxMonth").change(showCalendar);
		$("#timeBoxYear").change(showCalendar);
		
		reset();
		showCalendar();
	});	
</script>