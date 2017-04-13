<h2>Booking Confirmation</h2>

<table>
    <tr>
        <th>Host</th>
        <td><?= $booking -> host -> name ?></td>
    </tr>
    <tr>
        <th>Host's phone number</th>
        <td><?= $booking -> host -> phone ?></td>
    </tr>
    <tr>
        <th>Start Date</th>
        <td><?= $booking -> begin ?></td>
    </tr>
    <tr>
        <th>End Date</th>
        <td><?= $booking -> end ?></td>
    </tr>
    <tr>
        <th>Opening instructions</th>
        <td><?= $booking -> host -> openinginstructions ?></td>
    </tr>
    <tr>
        <th>Exact GPS Coordinates</th>
        <td>Lat: <?= $booking -> lat ?>, Lng: <?= $booking -> lng ?></td>
    </tr>
</table>

<pre>
<?php
var_dump($booking);
?>