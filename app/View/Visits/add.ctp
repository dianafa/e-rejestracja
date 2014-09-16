<h2>Dodawanie wizyty pacjenta <?php echo $this->User->fullName($registration['Patient']['User']); ?></h2>

<div class="form">
    <?php echo $this->Form->create('Visit'); ?>
    <fieldset>
        <?php
        $ds = array();
        foreach ($doctors as $doctor) {
            $ds[$doctor['Doctor']['id']] = $this->User->fullName($doctor['User']);
        }
        echo $this->Form->input('doctor_id', array('label' => __('Lekarz specjalności').$registration['Speciality']['name'], 'options' => $ds, 'selected' => $registration['Doctor']['id'], 'id' => 'doctorBox'));
        echo $this->Form->input('time', array('label' => __('Czas wizyty'), 'type' => 'datetime', 'dateFormat' => 'DMY', 'timeFormat' => '24', 'id' => 'timeBox'));
        ?><div id="calendar"></div><?php
        echo $this->Form->input('reason', array('label' => __('Przyczyna'), 'type' => 'text'));
        echo $this->Form->input('note', array('label' => __('Notatki'))); ?>
    </fieldset>
    <?php echo $this->Form->end(__('Zapisz')); ?>
</div>

<script type="text/javascript">
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

	$.ready(function() {
		$("#doctorBox").change(showCalendar);
		$("#timeBoxDay").change(showCalendar);
		$("#timeBoxMonth").change(showCalendar);
		$("#timeBoxYear").change(showCalendar);
	});
</script>

