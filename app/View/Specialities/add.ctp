<div class="form">
	<?php echo $this->Form->create('Speciality'); ?>
		<fieldset>
			<legend><?php echo __('Wypełnij proszę formularz'); ?></legend>
			<?php
				echo $this->Form->input('name', array('label' => 'Nazwa specjalności'));
			?>
		</fieldset>
	<?php echo $this->Form->end(__('Zapisz')); ?>
</div>