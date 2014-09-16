<h2>Panel lekarza</h2>

<h3>Wizyty</h3>
<ul>
	<li><?php echo $this->Html->link(__('Zobacz najbliższe'), array('controller' => 'visits', 'action' => 'index', 'planned')); ?></li>
	<li><?php echo $this->Html->link(__('Zobacz historię'), array('controller' => 'visits', 'action' => 'index', 'happenned')); ?></li>
	<li><?php echo $this->Html->link(__('Zapisz pacjenta nieznanego'), array('controller' => 'visits', 'action' => 'add')); ?></li>
</ul>