<h2>Edycja danych pacjenta</h2>

<table>
	<tr>
		<th colspan="2">Dane nieedytowalne</th>
	</tr>
	<tr>
		<td>Imię i nazwisko</td>
		<td><?php echo $this->User->fullName(AuthComponent::user()); ?></td>
	</tr>
	<tr>
		<td>PESEL</td>
		<td><?php echo $patient['User']['PESEL']; ?></td>
	</tr>
	<tr>
		<td>Płeć</td>
		<td><?php echo __($patient['Sex']['name']); ?></td>
	</tr>
        <tr>
		<td>Data urodzenia</td>
		<td><?php echo __($patient['Patient']['birthdate']); ?></td>
	</tr>
        
        <tr>
		<td>Miejsce urodzenia</td>
		<td><?php echo __($patient['Patient']['birthplace']); ?></td>
	</tr>
</table>

<div class="form">
    <?php echo $this->Form->create('Patient'); ?>
    <fieldset>
        <?php
        echo $this->Form->input('address', array('label' => __('Adres'), 'required' => true));
        echo $this->Form->input('idcard', array('label' => __('Numer i seria dowodu tożsamości'), 'required' => true));
        echo $this->Form->input('NIP', array(
            'label' => __('NIP (opcjonalnie)'),
            'onchange' => "correctNIP()",
            'id' => "NIP"
        ));
        echo $this->Form->input('phone', array('label' => __('Numer telefonu')));
        echo $this->Form->input('email', array(
            'label' => __('Adres e-mail'),
            'onchange' => "correctEmail()",
            'id' => "email"
        ));
        echo $this->Form->input('emergencyPhone', array('label' => __('Awaryjny numer telefonu')));
        echo $this->Form->input('password', array('label' => __('Hasło'), 'required' => true));
        echo $this->Form->input('password2', array('label' => __('Potwierdź hasło'), 'type' => 'password', 'required' => true, 'div' => 'input password required'));
        ?>
    </fieldset>
    <?php echo $this->Form->end(__('Zapisz')); ?>
</div>

<script type="text/javascript">

    function correctEmail() {
        var mail = $("#email").val();
        var atpos = mail.indexOf("@");
        var dotpos = mail.lastIndexOf(".");
        if (atpos < 1 || dotpos < atpos + 2 || dotpos + 2 >= mail.length) {
            alert("Niepoprawny adres e-mail");
            return false;
        }
    }

    function correctNIP() {
        var nip = $("#NIP").val();
        nip = nip.replace(/-/g, ""); //we want only numbers
        var reg = /^[0-9]{10}$/;
        if (reg.test(nip) === false)
        {
            alert('Niepoprawny numer NIP');
            return false;
        }
        else
        {
            var dig = ("" + nip).split("");
            var kontrola = (6 * parseInt(dig[0]) + 5 * parseInt(dig[1]) + 7 * parseInt(dig[2]) + 2 * parseInt(dig[3]) + 3 * parseInt(dig[4]) + 4 * parseInt(dig[5]) + 5 * parseInt(dig[6]) + 6 * parseInt(dig[7]) + 7 * parseInt(dig[8])) % 11;
            if (parseInt(dig[9]) === kontrola)
                return true;
            else
            {
                alert('Niepoprawny numer NIP');
                return false;
            }
        }
    }


</script>