<h2>Booking Confirmation</h2>

<table>
    <tr>
        <th>Host</th>
        <td><?= $booking -> host -> nickname ?><br /><?= $booking -> host -> name ?><br /><?= $booking -> host -> firstname ?> <?= $booking -> host -> lastname ?></td>
    </tr>
    <tr>
        <th>Host's email address</th>
        <td><?= $booking -> host -> email ?></td>
    </tr>
    <tr>
        <th>Host includes</th>
        <td><?= $booking -> host -> details ?></td>
    </tr>
    <tr>
        <th>Host's opening hours</th>
        <td>
            Mon: <?= $booking -> host -> open_monday_from ->i18nFormat('HH:mm') ?> - <?= $booking -> host -> open_monday_till ?><br />
            Tue: <?= $booking -> host -> open_tuesday_from ?> - <?= $booking -> host -> open_tuesday_till ?><br />
            Wed: <?= $booking -> host -> open_wednesday_from ?> - <?= $booking -> host -> open_wednesday_till ?><br />
            Thu: <?= $booking -> host -> open_thursday_from ?> - <?= $booking -> host -> open_thursday_till ?><br />
            Fri: <?= $booking -> host -> open_friday_from ?> - <?= $booking -> host -> open_friday_till ?><br />
            Sat: <?= $booking -> host -> open_saturday_from ?> - <?= $booking -> host -> open_saturday_till ?><br />
            Sun: <?= $booking -> host -> open_sunday_from ?> - <?= $booking -> host -> open_sunday_till ?>
        </td>
    </tr>
    <tr>
        <th>Host's 24/7 member access</th>
        <td><?= $booking -> host -> open_sunday_from ? "yes" : "no" ?></td>
    </tr>
    <tr>
        <th>Host's cancellation scheme</th>
        <td><?= $booking -> host -> cancellationscheme ?></td>
    </tr>
    <tr>
        <th>Host excludes</th>
        <td><?= $booking -> host -> extras ?></td>
    </tr>
    <tr>
        <th>Host's phone number</th>
        <td><?= $booking -> host -> phone ?></td>
    </tr>
    <tr>
        <th>Booking Start Date (including)</th>
        <td><?= $booking -> begin ?></td>
    </tr>
    <tr>
        <th>Booking End Date (including)</th>
        <td><?= $booking -> end ?></td>
    </tr>
    <tr>
        <th>Opening instructions</th>
        <td><?= $booking -> host -> openinginstructions ?></td>
    </tr>
    <tr>
        <th>Exact GPS Coordinates</th>
        <td>Lat: <?= $booking -> host -> lat ?>, Lng: <?= $booking -> host -> lng ?></td>
    </tr>
</table>

<pre>
<?php
var_dump($booking);
?>