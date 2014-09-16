<h1>Lista użytkowników</h1>
<p><?php echo $this->Html->link("Dodaj nowego użytkownika", array('action' => 'add')); ?></p>
<table>
    <tr>
        <th>Imię i nazwisko</th>
        <th>PESEL</th>
        <th>Typ użytkownika</th>
        <th>Akcje</th>
    </tr>

    <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo $user['User']['name'], ' ', $user['User']['surname']; ?></td>
            <td><?php echo $this->Html->link($user['User']['PESEL'], array('action' => 'view', $user['User']['id'])); ?> </td>
            <td><?php echo $user['Role']['name']; ?> </td>
            <td><?php echo $this->Form->postLink('Usuń użytkownika', array('action' => 'delete', $user['User']['id']), array('confirm' => 'Jesteś pewien, że chcesz usunąć tego użytkownika')); ?></td>
            <td> <?php endforeach; ?>
</table>