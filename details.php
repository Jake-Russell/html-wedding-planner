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

    // Get Relevant Values From Submitted Form //
    $venueId = $_REQUEST['venueId'];

    if (validateInputs($venueId)) {
        echo "<table border=1>";
        echo "<caption>Venue Details</caption>";

        $db->setFetchMode(MDB2_FETCHMODE_ASSOC);

        // SQL Statement To Retrieve Correct Data //
        $sql = "SELECT * FROM $table_name WHERE venue_id = $venueId";

        $res =& $db->query($sql);

        if (PEAR::isError($res)) {
            die($res->getMessage());
        }

        // Create And Populate Table With Correct Data //
        echo "<th>Venue ID</th><th>Venue Name</th><th>Venue Capacity</th><th>Weekend Price</th><th>Weekday Price</th><th>Licensed</th>";

        while ($row = $res->fetchRow()) {
            echo '<tr>';
            for ($j = 1; $j <= 6; $j++) {
                switch ($j) {
                    case 1:
                        echo "<td>" . $row[strtolower('venue_id')] . "</td>";
                        break;
                    case 2:
                        echo "<td>" . $row[strtolower('name')] . "</td>";
                        break;
                    case 3:
                        echo "<td>" . $row[strtolower('capacity')] . "</td>";
                        break;
                    case 4:
                        echo "<td>" . $row[strtolower('weekend_price')] . "</td>";
                        break;
                    case 5:
                        echo "<td>" . $row[strtolower('weekday_price')] . "</td>";
                        break;
                    case 6:
                        if ($row[strtolower('licensed')] == "0") {
                            echo "<td>No</td>";
                        } else {
                            echo "<td>Yes</td>";
                        }
                        break;
                }
            }
            echo '</tr>';
        }
        echo "</table>";
    }

    // Validate All Inputs //
    function validateInputs($venueId)
    {
        if (!is_numeric($venueId) || $venueId > 10 || $venueId < 1) {
            echo "Error. '" . $venueId . "' is an invalid venue ID. It must be a number between 1 and 10.<br/>";
        } else {
            return true;
        }
    }

    ?>

    <button onclick="goBack()" class="button">Go Back</button>

</div>
</body>
</html>