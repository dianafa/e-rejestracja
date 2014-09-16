<h2>Lekarz <?php echo $this->User->fullName($doctor['User']); ?></h2>

<h3>Specjalności</h3>
<ul>
	<?php foreach ($doctor['Speciality'] as $s): ?>
		<li><?php echo $this->Html->link(__($s['name']), array('controller' => 'specialities', 'action' => 'view', $s['id'])); ?></li>
	<?php endforeach; ?>
</ul>