<h2>Dodawanie przypomnienia do wizyty pacjenta <?php echo $this->User->fullName($visit['Patient']['User']); ?> z lekarzem <?php echo $this->User->fullName($visit['Doctor']['User']); ?> dn. <?php echo $visit['Visit']['time']; ?></h2>

<div class="form">
	<?php echo $this->Form->create('Reminder'); ?>
		<fieldset>
			<?php
				echo $this->Form->input('time', array('label' => __('Czas'), 'type' => 'datetime', 'dateFormat' => 'DMY', 'timeFormat' => '24'));
			?>
		</fieldset>
	<?php echo $this->Form->end(__('Zapisz')); ?>
</div>