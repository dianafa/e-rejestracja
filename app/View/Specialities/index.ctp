<h3>Specjalności naszej kliniki i ich lekarze</h3>
<dl class="specialities">
	<?php foreach ($specialities as $s): ?>
		<dt><?php echo $s['Speciality']['name']; ?></dt>
		<dd>
			<?php
				foreach ($s['Doctor'] as $d) {
					echo $this->User->fullName($d['User']).', ';
				}
				if (empty($s['Doctor']))
					echo '&nbsp;'
			?>
		</dd>
	<?php endforeach; ?>
</dl>