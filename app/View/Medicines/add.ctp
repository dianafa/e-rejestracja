<?php echo $this->Session->flash('auth'); ?>
<div class="form">
	<?php echo $this->Form->create('Medicine'); ?>
		<fieldset>
			<legend><?php echo __('Wypełnij proszę formularz'); ?></legend>
			<?php
				
				echo $this->Form->input('name', array('label' => 'Nazwa leku'));
                                echo $this->Form->input('substance', array('label' => 'Substancja czynna'));
			?>
		</fieldset>
	<?php echo $this->Form->end(__('Zapisz')); ?>
</div>