<?php
$mvalues = array();
foreach ($medicines as $m):
    $mvalues[$m['Medicine']['id']] = $m['Medicine']['name'] . " (" . $m['Medicine']['substance'] . ")";
endforeach;

$pvalues = array();
foreach ($procedures as $p):
    $pvalues[$p['Procedure']['id']] = " (" . $p['Procedure']['code'] . ")".$p['Procedure']['name'];
endforeach;

$dvalues = array();
foreach ($diagnoses as $d):
    $dvalues[$d['Diagnose']['id']] = " (" . $d['Diagnose']['code'] . ")".$d['Diagnose']['name'];
endforeach;
?>

<h2>Sprawozdanie z wizyty pacjenta <?php echo $this->User->fullName($patient['User']); ?></h2>

<div class="form">
    
    <?php echo $this->Form->create('Visit'); ?>
    <fieldset>
        <?php
        echo "Lekarz: ".$this->User->fullName($doctor['User']); 
        echo $this->Form->input('time', array('label' => __('Czas wizyty'), 'type' => 'datetime', 'dateFormat' => 'DMY', 'timeFormat' => '24'));
        echo $this->Form->input('reason', array('label' => __('Przyczyna'), 'type' => 'text'));
        
        //echo $this->Ajax->autoComplete('Visit.medicine_id', '/visits/autoComplete');
        
        echo $this->Form->input('medicine_id', array(
            'multiple' => 'true',
            'options' => array($mvalues),
            'empty' => '*****wybierz przepisany lek*****',
            'label' => __('Przepisane leki')
            
        ));
        
        echo $this->Form->input('procedure_id', array(
            'multiple' => 'true',
            'options' => array($pvalues),
            'empty' => '*****wybierz przepisaną procedurę*****',
            'label' => __('Przepisane procedury')
        ));
        echo $this->Form->input('procedures', array('label' => __('Dodatkowe procedury'))); 
        echo $this->Form->input('diagnose_id', array(
            'multiple' => 'true',
            'options' => array($dvalues),
            'empty' => '*****wybierz diagnozę*****',
            'label' => __('Diagnozy')
        ));
        
        echo $this->Form->input('diagnosis', array('label' => __('Diagnoza'))); 
        
        echo $this->Form->input('note', array('label' => __('Notatki dodatkowe'))); 
        ?>

    </fieldset>
    <?php echo $this->Form->end(__('Zapisz zmiany'));   
    
    ?>
</div>


