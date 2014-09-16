<h1>Imię: <?php echo h($user['User']['name']); ?></h1>
<h1>Nazwisko: <?php echo $user['User']['surname']; ?></h1>
<h1>Pesel: <?php echo h($user['User']['PESEL']); ?></h1>
<p><?php echo ' ',$this->Html->link('Powrót do mojego konta', array('action' => 'my_account')); ?> </p>