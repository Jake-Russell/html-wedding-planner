<html>
<head>
    <style>
        table {
            border: 1px solid black;
            border-collapse: collapse;
            width: 50%;
            margin-left: auto;
            margin-right: auto;
        }

        th, td {
            padding: 5px;
            text-align: center;
        }

        .wrapper {
            text-align: center;
        }

        .button {
            cursor: pointer;
            font-size: larger;
        }
    </style>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>

</head>

<body>
<div class="wrapper">
    <?php
    require_once 'MDB2.php';
    include "databaseCredentials.php";

    $host = 'localhost';
    $dbName = 'coa123wdb';

    // Make Database Connection //
    $dsn = "mysql://$username:$password@$host/$dbName";
    $db =& MDB2::connect($dsn);

    if (PEAR::isError($db)) {
        die($db->getMessage());
    }

    $table_name = "venue";
    $table_name2 = "venue_booking";

    // Get Relevant Values From Submitted Form //
    $date = $_REQUEST['date'];
    $partySize = $_REQUEST['partySize'];

    if (validateInputs($date, $partySize)) {
        echo "<table border=1>";
        echo "<caption>Costs of Available Venues</caption>";

        // Change Date Format From DD/MM/YYYY To YYYY-MM-DD //
        $newDate = str_replace('/', '-', $date);
        $newDate = date('Y-m-d', strtotime($newDate));

        $db->setFetchMode(MDB2_FETCHMODE_ASSOC);

        // SQL Statement To Retrieve Correct Data //
        $sql = "SELECT name, weekend_price, weekday_price
            FROM $table_name
            WHERE capacity > $partySize AND venue_id NOT IN (SELECT venue_id FROM $table_name2 WHERE date_booked = '$newDate')";

        $res =& $db->query($sql);

        if (PEAR::isError($res)) {
            die($res->getMessage());
        }

        // Create Table Headings Depending On Weekday Or Weekend //
        if (isWeekend($newDate)) {
            echo "<th>Venue Name</th><th>Weekend Price</th>";
        } else {
            echo "<th>Venue Name</th><th>Weekday Price</th>";
        }

        // Create And Populate Table With Correct Data //
        while ($row = $res->fetchRow()) {
            echo '<tr>';
            for ($j = 1; $j <= 2; $j++) {
                switch ($j) {
                    case 1:
                        echo "<td>" . $row[strtolower('name')] . "</td>";
                        break;
                    case 2:
                        if (isWeekend($newDate)) {
                            echo "<td>" . $row[strtolower('weekend_price')] . "</td>";
                        } else {
                            echo "<td>" . $row[strtolower('weekday_price')] . "</td>";
                        }
                        break;
                }
            }
            echo '</tr>';
        }
        echo "</table>";
    }


    // Validate If Date Is Weekend Or Weekday //
    function isWeekend($date)
    {
        $weekDay = date('w', strtotime($date));
        return ($weekDay == 0 || $weekDay == 6);
    }


    // Validate All Inputs //
    function validateInputs($date, $partySize)
    {
        if (DateTime::createFromFormat('d/m/Y', $date) == false) {
            echo "Error. '" . $date . "' is an invalid date.<br/>";
        } else if (!is_numeric($partySize)) {
            echo "Error. '" . $partySize . "' is an invalid party size. It must be a number.<br/>";
        } else {
            return true;
        }
    }

    ?>

    <button onclick="goBack()" class="button">Go Back</button>

</div>
</body>
</html>