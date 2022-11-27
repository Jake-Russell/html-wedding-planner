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
    // Get Relevant Values From Submitted Form //
    $minPartySize = $_REQUEST['min'];
    $maxPartySize = $_REQUEST['max'];
    $c1 = $_REQUEST['c1'];
    $c2 = $_REQUEST['c2'];
    $c3 = $_REQUEST['c3'];
    $c4 = $_REQUEST['c4'];
    $c5 = $_REQUEST['c5'];

    if (validateInputs($minPartySize, $maxPartySize, $c1, $c2, $c3, $c4, $c5)) {
        echo "<table border=1>";
        echo "<caption>Catering Costs</caption>";

        $NumRows = (($maxPartySize - $minPartySize) / 5) + 1;

        // Create And Populate Table With Correct Data //
        echo "<th>Cost Per Person &rarr; <br/>&darr; Party Size</th><th>$c1</th><th>$c2</th><th>$c3</th><th>$c4</th><th>$c5</th>";

        for ($i = 1; $i <= $NumRows; $i = $i + 1) {
            echo '<tr>';
            for ($j = 1; $j <= 6; $j++) {
                switch ($j) {
                    case 1:
                        echo "<td>" . $minPartySize . "</td>";
                        break;
                    case 2:
                        echo "<td>" . $minPartySize * $c1 . "</td>";
                        break;
                    case 3:
                        echo "<td>" . $minPartySize * $c2 . "</td>";
                        break;
                    case 4:
                        echo "<td>" . $minPartySize * $c3 . "</td>";
                        break;
                    case 5:
                        echo "<td>" . $minPartySize * $c4 . "</td>";
                        break;
                    case 6:
                        echo "<td>" . $minPartySize * $c5 . "</td>";
                        break;
                }
            }
            $minPartySize = $minPartySize + 5;
            echo '</tr>';
        }
        echo "</table>";
    }


    // Validate All Inputs //
    function validateInputs($minPartySize, $maxPartySize, $c1, $c2, $c3, $c4, $c5)
    {
        if (!is_numeric($minPartySize)) {
            echo "Error. '" . $minPartySize . "' is an invalid minimum party size. It must be a number.<br/>";
        } else if (!is_numeric($maxPartySize)) {
            echo "Error. '" . $maxPartySize . "' is an invalid minimum party size. It must be a number.<br/>";
        } else if ($maxPartySize < $minPartySize) {
            echo "Error. '" . $maxPartySize . "' is an invalid maximum party size. It must be greater than or equal to the minimum capacity (" . $minPartySize . ").<br/>";
        } else if (!is_numeric($c1)) {
            echo "Error. '" . $c1 . "' is an invalid grade 1 cost. It must be a number.<br/>";
        } else if (!is_numeric($c2)) {
            echo "Error. '" . $c2 . "' is an invalid grade 2 cost. It must be a number.<br/>";
        } else if (!is_numeric($c3)) {
            echo "Error. '" . $c3 . "' is an invalid grade 3 cost. It must be a number.<br/>";
        } else if (!is_numeric($c4)) {
            echo "Error. '" . $c4 . "' is an invalid grade 4 cost. It must be a number.<br/>";
        } else if (!is_numeric($c5)) {
            echo "Error. '" . $c5 . "' is an invalid grade 5 cost. It must be a number.<br/>";
        } else {
            return true;
        }
    }

    ?>

    <button onclick="goBack()" class="button">Go Back</button>
</div>
</body>
</html>
