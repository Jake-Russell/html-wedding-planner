<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <title>Wedding</title>
    <meta name="description" content="Wedding">

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Google Fonts Integration: https://fonts.google.com -->
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/styles.css">

    <!-- JQuery Integration -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
            integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

    <!-- Custom JS -->
    <script src="js/main.js"></script>

</head>
<body>
<!-- Inline PHP to calculate the current date, and the date 1 week later -->
<?php
$today = date('Y-m-d');

$futureDate = strtotime($today);
$futureDate = strtotime("+7 day", $futureDate);
$futureDate = date('Y-m-d', $futureDate);
?>

<h3 class="center">COA123 - Server-Side Programming</h3>
<h2 class="center">Individual Coursework - Wedding Planner</h2>

<h1 class="center">Task 5 - Wedding (wedding.php)</h1>
<form action="weddingData.php" method="get" id="capacity">
    <table border="1">
        <tr>
            <th scope="col">Key</th>
            <th scope="col">Value</th>
        </tr>
        <tr>
            <!-- Using 'date' input type to allow the user to pick a date, which has a default value of the current day -->
            <td><label for="startDate">First Available Date</label></td>
            <td><input type=date name="startDate" type="text" class="larger" id="startDate" value="<?php echo $today; ?>" size="12" /></td>
        </tr>
        <tr>
            <!-- Using 'date' input type to allow the user to pick a date, which has a default value of 1 week into the future -->
            <td><label for="lastDate">Last Available Date</label></td>
            <td><input type=date name="lastDate" type="text" class="larger" id = "lastDate" value="<?php echo $futureDate; ?>" size="12" /></td>
        </tr>
        <tr>
            <td><label for="partySize">Party Size</label></td>
            <td><input name="partySize" type="text" class="larger" id="partySize" value="220" size="12" /></td>
        </tr>
        <tr>
            <td><label for="cateringGrade">Catering Grade</label></td>
            <td><input type="number" name="cateringGrade" type="text" class="larger" id="cateringGrade" value="4" size="12" min="1" max="5" /></td>
        </tr>
        <tr>
            <!-- Drop down menu to allow the user to choose what they would like to filter the table by.
            Note - If the user has selected a range of dates, the whole table will be filtered by dates in ascending order.
            It is only the 'sub-tables' for each individual date which will be filtered by the selected criteria -->
            <td><label for="sortBy">Sort By</label></td>
            <td>
                <select name="sortBy" id = "sortBy" class = "larger">
                    <option value = "name">Venue Name</option>
                    <option value = "capacity">Capacity</option>
                    <option value = "weekend_price">Venue Cost</option>
                    <option value = "cost">Catering Cost</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>List the details of matching venues available during the given time frame</td>
            <td><input type="submit" name="submit" id="submit" value="Submit" class = "button" class="larger" /></td>
        </tr>
    </table>
</form>

<!-- This empty div is populated with the results from the database query -->
<div id = "results"></div>

</body>
</html>
