<h2><?php echo __d('cake_dev', 'Witamy na stronie e-rejestracji!'); ?></h2>

<p>E-rejestracja to aplikacja webowa umożliwiająca zarządzanie wizytami w przychodni za pomocą strony internetowej.</p>
<p>Zapraszamy do zapoznania się z jej możliwościami. </p>    

<p>By móc cokolwiek zrobić, <?php echo $this->Html->link(__('zaloguj się'), array('controller' => 'users', 'action' => 'login')); ?>. Jeśli jesteś tu jako pacjent, a jeszcze u nas nie byłeś, <?php echo $this->Html->link(__('ZAREJESTRUJ SIĘ'), array('controller' => 'patients', 'action' => 'add')); ?>.</p>
<p>Ewentualnie, możesz przejrzeć <?php echo $this->Html->link('czym się zajmujemy', array('controller' => 'specialities', 'action' => 'index')); ?> albo w czym specjalizują się <?php echo $this->Html->link(__('nasi lekarze'), array('controller' => 'doctors', 'action' => 'index')); ?></p>