<h2>Podgląd wizyty pacjenta <?php echo $this->User->fullName($visit['Patient']['User']); ?> z lekarzem <?php echo $this->user->fullName($visit['Doctor']['User']); ?> dn. <?php echo $visit['Visit']['time']; ?></h2>

<h3>Dane podstawowe</h3>
<dl>
	<dt>Przyczyna</dt>
	<dd><?php echo $visit['Visit']['reason']; ?></dd>
	<dt>Notatki</dt>
	<dd><?php echo $visit['Visit']['note']; ?></dd>
</dl>

<h3>Diagnoza</h3>
<p><?php echo $visit['Visit']['diagnosis']; ?></p>

<h3>Przeprowadzone procedury medyczne</h3>
<ul>
</ul>

<h3>Przepisane leki</h3>
<ul>
	<?php foreach ($medicines_v as $m): ?>
        <div><?php 
        echo $m['MedicinesVisit']['medicine_id']; 
        //echo $m['Medicine']['name']; 
        
        ?></div>
        <?php endforeach; ?>
</ul>

<p><?php echo $this->Html->link('Powrót to listy wizyt', array('action' => 'index')); ?></p>