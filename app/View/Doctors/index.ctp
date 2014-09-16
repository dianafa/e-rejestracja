<h3>Doktorzy naszej kliniki i ich specjalności</h3>
<dl class="doctors">
	<?php foreach ($doctors as $d): ?>
		<dt><?php echo $this->User->fullName($d['User']); ?></dt>
		<dd>
			<?php
				foreach ($d['Speciality'] as $s) {
					echo $s['name'].', ';
				}
				if (empty($d['Speciality']))
					echo '&nbsp;';
			?>
		</dd>
	<?php endforeach; ?>
</dl>