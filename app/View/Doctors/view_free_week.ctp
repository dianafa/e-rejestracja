<table>
	<tr>
		<th>Godzina</th>
		<?php foreach ($days as $d): ?>
			<th><?php echo implode('.', $d); ?></th>
		<?php endforeach; ?>
	</tr>
	<?php foreach ($hours as $h): ?>
		<tr>
			<th><?php echo implode(':', $h['time']); ?></th>
			<?php
				foreach ($h['days'] as $c => $d) {
					if ($d) {
						echo '<td class="timeSlotFree">';
						?>
							<a href="javascript:choose('<?php echo implode('.', $days[$c]).'.'.implode('.', $h['time']); ?>')">Wybierz</a>
						<?php
						echo '</td>';
					} else
						echo '<td class="timeSlotBusy">&nbsp;</td>';
				}
			?>
		</tr>
	<?php endforeach; ?>
</table>

<script type="text/javascript">
	function choose(dtString) {
		dt = dtString.split('.');
		console.log(dt);
		$("#timeBoxYear").val(dt[0]);
		$("#timeBoxMonth").val(dt[1]);
		$("#timeBoxDay").val(dt[2]);
		$("#timeBoxHour").val(dt[3]);
		$("#timeBoxMinute").val(dt[4]);
	}
</script>