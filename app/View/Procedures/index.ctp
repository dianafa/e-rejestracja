<h1>Lista procedur medycznych</h1>
<table>
    <tr>
        <th>Kod </th>
        <th>Nazwa badania</th>
    </tr>

    <?php foreach ($procedures as $procedure): ?>
        <tr>
            <td><?php echo $procedure['Procedure']['code']; ?></td>
            <td><?php echo $procedure['Procedure']['name']; ?></td>
            <td> <?php endforeach; ?>
</table>