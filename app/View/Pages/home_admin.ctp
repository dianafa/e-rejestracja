<h2>Kokpit administracyjny</h2>

<h3>Tworzenie kont specjalnych</h3>
<ul>
	<li><?php echo $this->Html->link('Załóż konto pacjenta', array('controller' => 'patients', 'action' => 'add')); ?></li>
	<li><?php echo $this->Html->link('Załóż konto lekarza', array('controller' => 'users', 'action' => 'add', 'doctor')); ?></li>
	<li><?php echo $this->Html->link('Załóż konto osobie z biura przyjęć', array('controller' => 'users', 'action' => 'add', 'receptionist')); ?></li>
	<li><?php echo $this->Html->link('Załóż konto administratorskie', array('controller' => 'users', 'action' => 'add', 'admin')); ?></li>
</ul>

<h3>Lekarze i specjalności</h3>
<ul>
	<li><?php echo $this->Html->link(__('Tabela powiązań'), array('controller' => 'specialities', 'action' => 'connections')); ?></li>
</ul>

<h3>Ustawienia systemu</h3>
<ul>
	<li><?php echo $this->Html->link('Przeglądaj', array('controller' => 'settings', 'action' => 'index')); ?></li>
	<li><?php echo $this->Html->link('Edytuj', array('controller' => 'settings', 'action' => 'edit')); ?></li>
	<li><?php echo $this->Html->link('Uruchom zadania CRON przypomnień o wizytach', array('controller' => 'reminders', 'action' => 'adminRun')); ?></li>
</ul>