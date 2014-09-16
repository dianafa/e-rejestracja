<?php echo $this->Session->flash('auth'); ?>

<h2>Nowy pacjent</h2>

<?php
$sexvalues = array();
foreach ($sexes as $s):
    $sexvalues[$s['Sex']['id']] = $s['Sex']['name'];
endforeach;
?>

<div class="form">
    <?php echo $this->Form->create('Patient'); ?>
    <fieldset>
        <legend><?php echo __('Wypełnij proszę formularz'); ?></legend>
        <?php
        echo $this->Form->input('name', array(
            'label' => __('Imię'),
            'required' => true,
            'onchange' => "correctName()",
            'id' => "name"
        ));
        echo $this->Form->input('surname', array(
            'label' => __('Nazwisko'),
            'required' => true,
            'onchange' => "correctSurname()",
            'id' => "surname"
        ));
        echo $this->Form->input('address', array('label' => __('Adres'), 'required' => true));

        echo $this->Form->input('sex_id', array(
            'options' => array($sexvalues),
            'empty' => '(wybierz płeć)',
            'label' => __('Płeć'),
            'required' => true
        ));
        echo $this->Form->input('PESEL', array(
            'label' => 'PESEL',
            'required' => true,
            'onchange' => "correctPESEL()",
            'id' => "PESEL"
        ));
        echo $this->Form->input('PESEL2', array(
            'label' => __('Potwierdź') . ' PESEL',
            'required' => true,
            'div' => 'input number required',
            'onchange' => "correctPESEL2()",
            'id' => "PESEL2"
        ));
        echo $this->Form->input('birthdate', array(
            'type' => 'date',
            'minYear' => date('Y') - 110,
            'maxYear' => date('Y'),
            'label' => __('Data urodzenia')
        ));
        echo $this->Form->input('birthplace', array('label' => __('Miejsce urodzenia'), 'required' => true, 'type' => 'textbox'));
        echo $this->Form->input('idcard', array('label' => __('Numer i seria dowodu tożsamości'), 'required' => true, 'type' => 'textbox'));
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
        echo $this->Form->input('emergencyPhone', array('label' => __('Awaryjny numer telefonu'), 'type' => 'textbox'));
        echo $this->Form->input('password', array('label' => __('Hasło'), 'required' => true));
        echo $this->Form->input('password2', array('label' => __('Potwierdź hasło'), 'type' => 'password', 'required' => true, 'div' => 'input password required'));
        ?>
    </fieldset>
    <?php echo $this->Form->end(__('Zapisz')); ?>
</div>

<script type="text/javascript">

    function correctName()
    {
        var name = $("#name").val();
        var regex = /^[A-Za-z]+$/;
        if (name.length < 2)
        {
            alert('Za krótkie imię.');
            return;
        }
        if (name.match(regex) !== null)
            return true;
        alert('Imię może składać się wyłącznie z liter');

    }

    function correctSurname()
    {
        var surname = $("#surname").val();
        var regex = /^[A-Za-z]+$/;
        if (surname.length < 2)
        {
            alert('Za krótkie nazwisko.');
            return;
        }

        if (surname.match(regex) === null)
            alert('Nazwisko może składać się wyłącznie z liter');
    }

    function correctPESEL()
    {
        var pesel = $("#PESEL").val();
        if (pesel.length !== 11)
        {
            alert('Niewłaściwa ilość cyfr w numerze PESEL');
            return;
        }
        // Wycinamy daty z numeru
        var rok = parseInt(pesel.substring(0, 2), 10);
        var miesiac = parseInt(pesel.substring(2, 4), 10) - 1;
        var dzien = parseInt(pesel.substring(4, 6), 10);

        if (miesiac > 80) {
            rok = rok + 1800;
            miesiac = miesiac - 80;
        }
        else if (miesiac > 60) {
            rok = rok + 2200;
            miesiac = miesiac - 60;
        }
        else if (miesiac > 40) {
            rok = rok + 2100;
            miesiac = miesiac - 40;
        }
        else if (miesiac > 20) {
            rok = rok + 2000;
            miesiac = miesiac - 20;
        }
        else
        {
            rok += 1900;
        }
        // Daty sa ok. Teraz ustawiamy.
        var urodzony = new Date();
        urodzony.setFullYear(rok, miesiac, dzien);

        // Teraz zweryfikujemy numer pesel
        // Metoda z wagami jest w sumie najszybsza do weryfikacji.
        var wagi = [9, 7, 3, 1, 9, 7, 3, 1, 9, 7];
        var suma = 0;

        for (var i = 0; i < wagi.length; i++) {
            suma += (parseInt(pesel.substring(i, i + 1), 10) * wagi[i]);
        }
        suma = suma % 10;
        var valid = (suma === parseInt(pesel.substring(10, 11), 10));

        //plec
        if (parseInt(pesel.substring(9, 10), 10) % 2 === 1) {
            var plec = 'm';
        } else {
            var plec = 'k';
        }
        //return {valid:valid,sex:plec,date:urodzony};
        if (!valid)
            alert('Niepoprawny PESEL');
        //return date;
    }

    function correctPESEL2()
    {
        var pesel = $("#PESEL").val();
        var pesel2 = $("#PESEL2").val();

        if (pesel === pesel2)
            return;
        alert('Niepoprawnie powtórzony PESEL');
    }

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
