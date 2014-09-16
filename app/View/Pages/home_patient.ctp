<h2>Panel pacjenta</h2>

<h3>Wizyty</h3>
<ul>
	<li><?php echo $this->Html->link(__('Zarejestruj się na wizytę'), array('controller' => 'registrations', 'action' => 'add')); ?></li>
	<li><?php echo $this->Html->link(__('Oczekujące rejestracje'), array('controller' => 'registrations', 'action' => 'my')); ?></li>
	<li><?php echo $this->Html->link(__('Zobacz historię wizyt'), array('controller' => 'visits', 'action' => 'index', 'happenned')); ?></li>
	<li><?php echo $this->Html->link(__('Zobacz przyszłe wizyty'), array('controller' => 'visits', 'action' => 'index', 'planned')); ?></li>
</ul>

<h3>Moje konto</h3>
<ul>
	<li><?php echo $this->Html->link(__('Zobacz profil'), array('controller' => 'patients', 'action' => 'profile')); ?></li>
	<li><?php echo $this->Html->link(__('Edytuj dane'), array('controller' => 'patients', 'action' => 'editProfile')); ?></li>
	<!--<li>zmien haslo</li>-->
</ul>
