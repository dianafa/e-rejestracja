<?php echo $this->Session->flash('auth'); ?>
<div class="form">
	<?php echo $this->Form->create('User'); ?>
		<fieldset>
			<legend><?php echo __('Wypełnij proszę formularz'); ?></legend>
			<?php
				echo $this->Form->input('PESEL', array('label' => 'PESEL'));
				echo $this->Form->input('PESEL2', array('label' => __('Potwierdź').' PESEL', 'required' => true, 'div' => 'input number required'));
				echo $this->Form->input('name', array('label' => __('Imię')));
				echo $this->Form->input('surname', array('label' => __('Nazwisko')));
				echo $this->Form->input('password', array('label' => __('Hasło')));
				echo $this->Form->input('password2', array('label' => __('Potwierdź hasło'), 'type' => 'password', 'required' => true, 'div' => 'input password required'));
			?>
		</fieldset>
	<?php echo $this->Form->end(__('Zapisz')); ?>
</div>