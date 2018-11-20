<?php
include_once("vendor/autoload.php");
include_once("util.php");
include_once("graphing_utils.php");
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Panoptes - Graphs</title>

    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/css/dashboard.css" rel="stylesheet">

</head>

<body>
<nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Panoptes</a>
    <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
            <a class="nav-link" href="#">Sign out</a>
        </li>
    </ul>

</nav>

<div class="container-fluid">
    <div class="row">

        <?php include "sidemenu.php"; ?>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <canvas class="my-4 w-100 col-md-6" id="myChart"></canvas>
        </main>
    </div>
</div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="/js/popper.min.js"></script>
<script src="/js/bootstrap.min.js"></script>

<!-- Icons -->
<script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
<script>
    feather.replace()
</script>

<!-- Graphs -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>

<?php
$traffic_types = num_packets_types($conn);
$types = json_encode(array_column($traffic_types, "packet_type"), JSON_PRETTY_PRINT);
$counts = json_encode(array_column($traffic_types, "cnt"), JSON_PRETTY_PRINT);
$colors = array();
for ($i = 0; $i < count($traffic_types); $i++) {
    $r = rand(0,200);
    $g = rand(0,200);
    $b = rand(0,200);
    array_push($colors, "rgba(".$r.", ".$g.", ".$b.", 1)");
}
$colors = json_encode($colors, JSON_PRETTY_PRINT);
?>

<script>
    var ctx = document.getElementById("myChart");
    // And for a doughnut chart
    var data = {
        datasets: [{
            data: <?php echo $counts; ?>,
            backgroundColor: <?php echo $colors; ?>
        }],

        // These labels appear in the legend and in the tooltips when hovering different arcs
        labels: <?php echo $types; ?>
    };
    var myDoughnutChart = new Chart(ctx, {
        type: 'doughnut',
        data: data,
        options: []
    });
</script>
</body>
</html>
