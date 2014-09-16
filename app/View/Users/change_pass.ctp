<div class="form">
	<?php echo $this->Form->create(); ?>
		<fieldset>
			<legend><?php echo __('Wypełnij proszę formularz zmiany hasła'); ?></legend>
			<?php
				echo $this->Form->input('current_password', array('label' => __('Wprowadź stare hasło'), 'type' => 'password','required' => true, 'div' => 'old password required'));
				echo $this->Form->input('npassword1', array('label' => __('Wprowadź nowe hasło'), 'type' => 'password', 'required' => true, 'div' => 'new password required'));
				echo $this->Form->input('npassword2', array('label' => __('Potwierdź nowe hasło'), 'type' => 'password', 'required' => true, 'div' => 'new password required'));
			?>
		</fieldset>
	<?php echo $this->Form->end(__('Zapisz')); ?>
</div>