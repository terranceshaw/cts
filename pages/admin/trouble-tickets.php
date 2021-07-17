<?php
    $commandID = COMMAND_ID;
    $query = "SELECT tickets.*, users.username
              FROM cts_trouble_tickets tickets
              INNER JOIN user_accounts users
                ON users.id = tickets.user_id
              WHERE tickets.command_id=$commandID";
    if ($result = mysqli_query($connection, $query)) {
        while ($row = mysqli_fetch_assoc($result)) {
            $row['date_opened'] = date('m/d/y', strtotime($row['date_opened']));
            $rows[] = $row;
        }
    } else {
        error("There was an error fetching trouble tickets: " . mysqli_error($connection));
    }
?><div class="col padded">
    <h1 class="page-title">Trouble Tickets</h1>
    <span class="subtitle">View, respond to, and close trouble tickets.</span>
    <table>
        <thead>
            <tr>
                <th>User</th>
                <th>Subject</th>
                <th>Date Submitted</th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach ($rows as $row) {
                    echo "<tr>\n";
                    $username = $row['username'];
                    $subject = $row['subject'];
                    $dateOpened = $row['date_opened'];
                    echo "<td style=\"width: 200px\">$username</td>\n";
                    echo "<td>$subject</td>\n";
                    echo "<td style=\"width: 150px\">$dateOpened</td>\n";
                    echo "</tr>\n";
                }
            ?>
        </tbody>
    </table>
</div>