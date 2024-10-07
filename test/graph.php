<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Radar Chart Example</title>
    <!-- Include jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function exportHTML() {
            var header = "<html xmlns:o='urn:schemas-microsoft-com:office:office' " +
                "xmlns:w='urn:schemas-microsoft-com:office:word' " +
                "xmlns='http://www.w3.org/TR/REC-html40'>" +
                "<head><meta charset='utf-8'><title>Export HTML to Word Document with JavaScript</title></head><body>";
            var footer = "</body></html>";
            var sourceHTML = header + document.getElementById("source-html").innerHTML + footer;

            $.ajax({
                url: 'export.php',
                type: 'POST',
                data: {html: sourceHTML},
                success: function (response) {
                    window.location.href = response;
                }
            });
        }
    </script>
</head>
<body>
<div id="source-html">
    <h1>
        <center>Artificial Intelligence</center>
    </h1>
    <h2>Overview</h2>
    <p>
        Artificial Intelligence(AI) is an emerging technology
        demonstrating machine intelligence. The sub studies like <u><i>Neural
                Networks</i>, <i>Robotics</i> or <i>Machine Learning</i></u> are
        the parts of AI. This technology is expected to be a prime part
        of the real world in all levels.
    </p>
    <!-- Canvas element to render the radar chart -->
    <canvas id="myRadarChart" width="400" height="400"></canvas>
</div>

<div class="content-footer">
    <button id="btn-export" onclick="exportHTML();">Export to
        Word doc
    </button>
</div>

<script>
    // Get the canvas element
    var ctx = document.getElementById('myRadarChart').getContext('2d');

    // Define data for the radar chart
    var data = {
        labels: ['Speed', 'Stamina', 'Strength', 'Agility', 'Endurance'],
        datasets: [{
            label: 'Player Stats',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 2,
            data: [80, 70, 85, 90, 75]
        }]
    };

    // Define options for the radar chart
    var options = {
        scale: {
            angleLines: {
                display: false
            },
            ticks: {
                beginAtZero: true,
                max: 100
            }
        }
    };

    // Create the radar chart
    var myRadarChart = new Chart(ctx, {
        type: 'radar',
        data: data,
        options: options
    });
</script>
</body>
</html>
