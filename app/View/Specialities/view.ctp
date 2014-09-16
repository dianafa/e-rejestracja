<h2>Specjalność <?php echo $speciality['Speciality']['name']; ?></h2>

<h3>Lekarze</h3>
<ul>
	<?php foreach ($speciality['Doctor'] as $d): ?>
		<li><?php echo $this->Html->link($this->User->fullName($d['User']), array('controller' => 'doctors', 'action' => 'view', $d['id']), array('escape' => false)); ?></li>
	<?php endforeach; ?>
</ul>