<!DOCTYPE HTML>
<html>
<head>  
<meta charset="UTF-8">
<script src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script>
<script src="../../canvasjs.min.js"></script>
<script>
window.onload = function () {
	var lightData = [];
	var powerData = [];

	var chart = new CanvasJS.Chart("chartContainer", {
		title: {
			text: "Light and Power Data"
		},
		axisX: {
			title: "Time",
			valueFormatString: "YY-MM-DD HH:mm:ss",
			labelAngle: -30
		},
		axisY: {
			title: "Light",
			lineColor: "DarkSlateBlue",
			tickColor: "DarkSlateBlue",
			labelFontColor: "#4F81BC",
			titleFontColor: "#4F81BC",
		},
		axisY2: {
			title: "Power",
			lineColor: "Crimson",
			tickColor: "Crimson",
			labelFontColor: "#C0504E",
			titleFontColor: "#C0504E",
		},
		toolTip: {
			shared: true
		},
		legend: {
			cursor: "pointer",
			verticalAlign: "top",
			horizontalAlign: "center",
			dockInsidePlotArea: true,
			itemclick: toggleDataSeries
		},
		data: [
			{
				type: "line",
				name: "Light",
				showInLegend: true,
				markerSize: 0,
				axisYType: "primary",
				dataPoints: lightData
			},
			{
				type: "line",
				name: "Power",
				axisYType: "secondary",
				markerSize: 0,
				showInLegend: true,
				dataPoints: powerData
			}
		]
	});

	chart.render();

	function toggleDataSeries(e) {
		if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
			e.dataSeries.visible = false;
		} else {
			e.dataSeries.visible = true;
		}
		chart.render();
	}

	function addData(data) {
		lightData.length = 0;
		powerData.length = 0;

		for (var i = 0; i < data.record.length; i++) {
			var current = data.record[i];
			var dateObj = new Date(current.devicetimestamp.replace(' ', 'T'));

			lightData.push({ x: dateObj, y: parseFloat(current.light) });
			powerData.push({ x: dateObj, y: parseFloat(current.power) });
		}

		chart.render();
		setTimeout(updateData, 5000);
	}

	function updateData() {
		$.getJSON("http://iotserver.com/reportJSON.php", addData);
	}

	updateData();
};
</script>
</head>
<body>
	<div id="chartContainer" style="height: 400px; width: 100%; max-width: 1200px; margin: auto;"></div>
</body>
</html>
