<!DOCTYPE html>
<html>

<head>
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }
    </style>
</head>

<body>


    <?php
    require __DIR__ . "/vendor/autoload.php";
    require __DIR__ . "/NotifyClass.php";

    use Google\Client;
    use Google\Service\Sheets\ValueRange;

    // Google API client
    function getClient()
    {
        $client = new Google\Client();
        $client->setApplicationName('sheet_api');
        $client->setScopes('https://www.googleapis.com/auth/spreadsheets');
        $client->setAuthConfig('./credential.json');
        $client->setAccessType('offline');
        return $client;
    }

    $client_path = __DIR__ . "/client.json";
    if (!file_exists($client_path)) {
        die("You need client.json to continue");
    }

    try {
        $client_data   = file_get_contents($client_path);
        $client_obj    = json_decode($client_data, true);
        $client        = getClient();
        $service       = new Google\Service\Sheets($client);
        $spreadsheetId = $client_obj["sheet_id"];
        $range         = $client_obj["tab_name"];;
        $response      = $service->spreadsheets_values->get($spreadsheetId, $range);
        $values        = $response->getValues();
        $notify        = new Notify($client_obj["smtp"]["host"], $client_obj["smtp"]["user"], $client_obj["smtp"]["pass"]);

        if (empty($values)) {
            print "No data found.\n";
        } else {
            $num = 1;
            echo "<table>";
            foreach ($values as $value) {
                echo "<tr>";
                foreach ($value as $key => $element) {
                    if ($num == 1) {
                        echo "<th>$element</th>";
                    } else {
                        echo "<td>$element</td>";
                    }
                }
                $flag = strtolower($value[4]);
                if ($flag == 'yes') {
                    $email_count = count(explode(",", $value[2]));
                    if ($email_count > 1) {
                        $emails_arr = explode(",", $value[2]);
                        foreach ($emails_arr as $email_one) {
                            if ($notify->send($email_one, "Notification email", "You are notified!")) {
                                echo "email sent to " . $email_one . "<br>";
                            }
                        }
                    } else {
                        $email = $value[2];
                        if ($notify->send($email, "Notification email", "You are notified!")) {
                            echo "email sent to " . $email . "<br>";
                        }
                    }
                }
                $num++;
                echo "</tr>";
            }
            echo "</table>";
        }
    } catch (Exception $e) {
        echo 'Message: ' . $e->getMessage();
    }
    ?>
</body>

</html>