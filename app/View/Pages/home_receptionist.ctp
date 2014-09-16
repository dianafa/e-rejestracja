<h2>Obsługa biura przyjęć</h2>

<?php
	/* Właściwie to tylko pacjenci mają podpięte rozbudowane tabele z profilami (reszta ma tylko id i user_id w tejże tabeli), więc na razie wykomentowałem */
	/*
	<h3>Moje konto</h3>
	<ul>
		<li>zobacz profil</li>
		<li>zmien dane kontaktowe // no płci chyba nie nie? xd ani nic innego</li>
		<li>zmien haslo</li>
	</ul>
	*/
?>

<h3>Rejestracje i wizyty</h3>
<ul>
	<li><?php echo $this->Html->link(__('Zobacz oczekujące rejestracje'), array('controller' => 'registrations', 'action' => 'index')); ?></li>
	<li><?php echo $this->Html->link(__('Zapisz na wizytę Nieznanego Pacjenta (NN)'), array('controller' => 'visits', 'action' => 'add')); ?></li>
	<!--<li>zobacz historie wizyt tu trzeba pomyslec jak uporzadkowac, najpierw chyba mozna wyswietlic wszystkie sortujac po dacie, potem dac mozliwosc wybrania z dropdownlist konkrtenego pacjenta + inne param</li>-->
	<!--<li>zobacz przyszle wizyty j.w.</li>-->
</ul>

<?php
	/* Na dobrą sprawę to tym niech się zajmują pacjenci, moim zdaniem */
	/*
<h3>Pacjenci</h3>
<ul>
	<li>stworz nowego pacjenta</li>
	<li>zmien dane pacjenta</li>
	<li>zarejestruj na wizytę</li>
	<li>anuluj rejestrację</li>
</ul>
	*/
?>