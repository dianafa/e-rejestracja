<h2>Anulowanie wizyty</h2>

<strong>Ta operacja jest nieodwracalna, a ponowna rejestracja ustawi Cię na końcu kolejki w oczekiwaniu na umówienie wizyty!</strong>
Czy chcesz kontynuować?

<?php
	echo $this->Form->create('Registration');
	echo $this->Form->submit(__('Nie'), array('div' => false, 'name' => 'no')); 
	echo $this->Form->submit(__('Tak'), array('div' => false, 'name' => 'yes'));
	$this->Form->end();
?>