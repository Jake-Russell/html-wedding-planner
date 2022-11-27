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
    $minCapacity = $_REQUEST['minCapacity'];
    $maxCapacity = $_REQUEST['maxCapacity'];

    if (validateInputs($minCapacity, $maxCapacity)) {
        echo "<table border=1 id='dataTable'>";
        echo "<caption>Licensed Venues Within The Given Capacity Range</caption>";

        $db->setFetchMode(MDB2_FETCHMODE_ASSOC);

        // SQL Statement To Retrieve Correct Data //
        $sql = "SELECT name, weekend_price, weekday_price FROM $table_name WHERE licensed = 1 AND capacity >= $minCapacity AND capacity <= $maxCapacity";

        $res =& $db->query($sql);

        if (PEAR::isError($res)) {
            die($res->getMessage());
        }

        // Create And Populate Table With Correct Data //
        echo "<th>Venue Name</th><th>Weekend Price</th><th>Weekday Price</th>";

        while ($row = $res->fetchRow()) {
            echo '<tr>';
            for ($j = 1; $j <= 3; $j++) {
                switch ($j) {
                    case 1:
                        echo "<td>" . $row[strtolower('name')] . "</td>";
                        break;
                    case 2:
                        echo "<td>" . $row[strtolower('weekend_price')] . "</td>";
                        break;
                    case 3:
                        echo "<td>" . $row[strtolower('weekday_price')] . "</td>";
                        break;
                }
            }
            echo '</tr>';
        }
        echo '</table>';
    }


    // Validate All Inputs //
    function validateInputs($minCapacity, $maxCapacity)
    {
        if (!is_numeric($minCapacity)) {
            echo "Error. '" . $minCapacity . "' is an invalid minimum capacity. It must be a number.<br/>";
        } else if (!is_numeric($maxCapacity)) {
            echo "Error. '" . $maxCapacity . "' is an invalid maximum capacity. It must be a number.<br/>";
        } else if ($maxCapacity < $minCapacity) {
            echo "Error. '" .$maxCapacity . "' is an invalid maximum capacity. It must be greater than or equal to the minimum capacity (" . $minCapacity . ").<br/>";
        } else {
            return true;
        }
    }

    ?>

    <button onclick="goBack()" class="button">Go Back</button>
</div>
</body>
</html>
