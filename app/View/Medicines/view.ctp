
<p>Nazwa leku: <?php echo $medicine['Medicine']['name']; ?></p>
<h1>Substancja czynna:  <?php echo h($medicine['Medicine']['substance']); ?></h1>
<p><?php echo ' ',$this->Html->link('Powrót to listy leków', array('action' => 'index')); ?> </p>