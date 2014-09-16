<?php echo $this->Session->flash('auth'); ?>
<div class="form">
	<?php echo $this->Form->create('User'); ?>
		<fieldset>
			<legend><?php echo __('Podaj proszę swój PESEL i hasło'); ?></legend>
			<?php
				echo $this->Form->input('PESEL', array('label' => 'PESEL', 'type' => 'textbox'));
				echo $this->Form->input('password', array('label' => __('Hasło')));
                                echo $this->Form->input('remember_me', array('label' => __('Zapamiętaj mnie'), 'type' => 'checkbox'));
			?>
		</fieldset>
	<?php echo $this->Form->end(__('Login')); ?>
</div>