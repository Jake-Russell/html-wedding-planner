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

// Get Relevant Values From Submitted Form //
$startDate = $_REQUEST['startDate'];
$lastDate = $_REQUEST['lastDate'];
$partySize = $_REQUEST['partySize'];
$cateringGrade = $_REQUEST['cateringGrade'];
$sortBy = $_REQUEST['sortBy'];
$licensed = $_REQUEST['filterByLicensed'];

// Validate Inputs And Create Table If Valid //
if (validateInputs($startDate, $lastDate, $partySize, $cateringGrade)) {
    echo "<br/><span id='checkbox'>";
    // If Only Showing Licensed Venues, Check Checkbox //
    if($licensed == 1){
        echo "<input type='checkbox' id='filterByLicensed' name='filterByLicensed' value='Licensed' onclick='checkboxStateChanged()' checked>";
    }
    else{
        echo "<input type='checkbox' id='filterByLicensed' name='filterByLicensed' value='Licensed' onclick='checkboxStateChanged()'>";
    }
    echo "<label for='filterByLicensed'>Show only licensed venues</label><br>";
    echo "</span>";

    echo "<table border=1 id='dataTable'>";

    // Change Date Format From DD/MM/YYYY To YYYY-MM-DD, And Create DateTime Instances //
    $startDateInCorrectFormat = str_replace('/', '-', $startDate);
    $startDateInCorrectFormat = (date('Y-m-d', strtotime($startDateInCorrectFormat)));
    $startDateAsDateTime = new DateTime($startDateInCorrectFormat);

    $lastDateInCorrectFormat = str_replace('/', '-', $lastDate);
    $lastDateInCorrectFormat = (date('Y-m-d', strtotime($lastDateInCorrectFormat)));
    $lastDateAsDateTime = new DateTime($lastDateInCorrectFormat);

    $lastDateAsDateTime = $lastDateAsDateTime->modify('+1 day');

    // Determine Range Of Dates Between Two Given Dates //
    $interval = new DateInterval('P1D');
    $dateRange = new DatePeriod($startDateAsDateTime, $interval, $lastDateAsDateTime);

    $numberOfRows = 0;
    // Loop Through Each Date In Calculated Date Range //
    foreach ($dateRange as $date) {
        $dateAsString = $date->format("Y-m-d");

        $db->setFetchMode(MDB2_FETCHMODE_ASSOC);

        // If Only Showing Licensed Venues, Also Query Database For Licensed Venues //
        if($licensed == 1){
            $sql = "SELECT venue.name, venue.licensed, venue.capacity, venue.weekend_price, venue.weekday_price, catering.cost, catering.grade
            FROM catering LEFT JOIN venue
            ON catering.venue_id = venue.venue_id
            WHERE venue.capacity >= $partySize AND catering.grade = $cateringGrade AND venue.licensed = 1 AND venue.venue_id NOT IN (SELECT venue_booking.venue_id FROM venue_booking WHERE venue_booking.date_booked = '$dateAsString')
            ORDER BY $sortBy";
        } else{
            $sql = "SELECT venue.name, venue.licensed, venue.capacity, venue.weekend_price, venue.weekday_price, catering.cost, catering.grade
            FROM catering LEFT JOIN venue
            ON catering.venue_id = venue.venue_id
            WHERE venue.capacity >= $partySize AND catering.grade = $cateringGrade AND venue.venue_id NOT IN (SELECT venue_booking.venue_id FROM venue_booking WHERE venue_booking.date_booked = '$dateAsString')
            ORDER BY $sortBy";
        }

        $res =& $db->query($sql);

        if (PEAR::isError($res)) {
            die($res->getMessage());
        }

        // If Query Brings Back Results, Create Table Headings Depending On Weekday Or Weekend //
        if($res->numRows() != 0){
            $numberOfRows++;
            if (isWeekend($dateAsString)) {
                echo "<th>Date</th><th>Venue Name</th><th>Catering Grade</th><th>Licensed</th><th>Capacity</th><th>Weekend Venue Cost</th><th>Catering Cost (Per Person)</th><th>Total Cost</th>";
            } else {
                echo "<th>Date</th><th>Venue Name</th><th>Catering Grade</th><th>Licensed</th><th>Capacity</th><th>Weekday Venue Cost</th><th>Catering Cost (Per Person)</th><th>Total Cost</th>";
            }
        }

        // While There Are More Results, Loop Through And Output Results Into Table //
        while ($row = $res->fetchRow()) {
            echo '<tr>';
            for ($j = 1; $j <= 9; $j++) {
                switch ($j) {
                    case 1:
                        // Format Date In Form 'l d F Y', For Example: Monday 11 May 2020 //
                        echo "<td>" . $date->format('l d F Y') . "</td>";
                        break;
                    case 2:
                        echo "<td>" . $row[strtolower('name')] . "</td>";
                        break;
                    case 3:
                        echo "<td>" . $row[strtolower('grade')] . "</td>";
                        break;
                    case 4:
                        if ($row[strtolower('licensed')] == "0") {
                            echo "<td>No</td>";
                        } else {
                            echo "<td>Yes</td>";
                        }
                        break;
                    case 5:
                        echo "<td>" . number_format((int)$row[strtolower('capacity')]) . "</td>";
                        break;
                    case 6:
                        if (isWeekend($dateAsString)) {
                            echo "<td>£" . number_format((int)$row[strtolower('weekend_price')]) . "</td>";
                        } else {
                            echo "<td>£" . number_format((int)$row[strtolower('weekday_price')]) . "</td>";
                        }
                        break;
                    case 7:
                        echo "<td>£" . number_format((int)$row[strtolower('cost')]) . "</td>";
                        break;
                    case 8:
                        if (isWeekend($dateAsString)) {
                            $totalCost = number_format((int)$row[strtolower('weekend_price')] + ((int)$row[strtolower('cost')]*$partySize));
                            echo "<td>£" . $totalCost . "</td>";
                        } else {
                            $totalCost = number_format((int)$row[strtolower('weekday_price')] + ((int)$row[strtolower('cost')]*$partySize));
                            echo "<td>£" . $totalCost . "</td>";
                        }
                        break;
                }
            }
            echo '</tr>';
        }
    }
    // If The Query Brought Back Results, Add The Table Heading, Otherwise, Output An Error //
    if($numberOfRows != 0){
        echo "<caption><br/>Available Wedding Venues</caption>";
        echo "<style>#checkbox{display: inline}</style>"; // Make Checkbox Visible //
    } else{
        echo "<script>errorMessage(4)</script>";
        echo "<style>#checkbox{display: none;}</style>"; // Hide Checkbox //
    }
    echo '</table>';
}

// Validate If Date Is Weekend Or Weekday //
function isWeekend($date)
{
    $weekDay = date('w', strtotime($date));
    return ($weekDay == 0 || $weekDay == 6);
}

// Validate All Inputs And Display Error Message Otherwise //
function validateInputs($startDate, $lastDate, $partySize, $cateringGrade)
{
    $now = date('d/m/Y');

    if ($startDate < $now) {
        echo "<script>errorMessage(0)</script>";
    } else if ($startDate > $lastDate) {
        echo "<script>errorMessage(1)</script>";
    } else if (!is_numeric($partySize) || $partySize < 0) {
        echo "<script>errorMessage(2)</script>";
    } else if (!is_numeric($cateringGrade) || $cateringGrade > 5 || $cateringGrade < 1) {
        echo "<script>errorMessage(3)</script>";
    } else {
        return true;
    }
}
