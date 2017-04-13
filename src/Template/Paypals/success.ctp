<h2>Booking Confirmation</h2>

<table>
    <tr>
        <th>Host</th>
        <td><?= $booking -> host -> name ?></td>
    </tr>
    <tr>
        <th>Start Date</th>
        <td><?= $booking -> begin ?></td>
    </tr>
    <tr>
        <th>End Date</th>
        <td><?= $booking -> end ?></td>
    </tr>
</table>

<pre>
<?php
var_dump($booking);
?>