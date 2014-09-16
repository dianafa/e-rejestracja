<h1>Edytuj lek </h1>
<?php
echo $this->Form->create('Medicine');

echo $this->Form->input('name', array('label' => 'Nazwa leku'));
echo $this->Form->input('substance', array('label' => 'Substancja czynna'));
echo $this->Form->end('Zapisz zmiany');
?>