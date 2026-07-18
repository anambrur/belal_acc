"use strict";
$(document).ready(function () {
    function generateData(baseval, count, yrange) {
        var i = 0;
        var series = [];
        while (i < count) {
            var x = Math.floor(Math.random() * (750 - 1 + 1)) + 1;
            var y =
                Math.floor(Math.random() * (yrange.max - yrange.min + 1)) +
                yrange.min;
            var z = Math.floor(Math.random() * (75 - 15 + 1)) + 15;
            series.push([x, y, z]);
            baseval += 86400000;
            i++;
        }
        return series;
    }

    // Sales Chart
    if ($("#sales_chart").length > 0) {
        var columnCtx = document.getElementById("sales_chart");
        var columnConfig = {
            colors: ["#28C76F", "#FF9F43"],
            series: [
                { name: "Sells", type: "column", data: [] }, // Empty initially
                { name: "Purchase", type: "column", data: [] }, // Empty initially
            ],
            chart: {
                type: "bar",
                fontFamily: "Poppins, sans-serif",
                height: 350,
                toolbar: { show: false },
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: "60%",
                    endingShape: "rounded",
                },
            },
            dataLabels: { enabled: false },
            stroke: { show: true, width: 2, colors: ["transparent"] },
            xaxis: { categories: [] }, // Empty initially
            yaxis: { title: { text: "$ (thousands)" } },
            fill: { opacity: 1 },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return "$ " + val + " thousands";
                    },
                },
            },
        };

        var columnChart = new ApexCharts(columnCtx, columnConfig);
        columnChart.render();

        // Fetch data dynamically for sales chart
        $.ajax({
            url: "/get-sales-data",
            method: "GET",
            success: function (response) {
                // Update chart series and x-axis categories
                columnChart.updateSeries([
                    { name: "Sells", data: response.sales },
                    { name: "Purchase", data: response.purchase },
                ]);
                columnChart.updateOptions({
                    xaxis: { categories: response.categories },
                });
            },
            error: function (error) {
                console.error("Error fetching sales chart data", error);
            },
        });
    }

    // Sales Charts (stacked bar)
    if ($("#sales_charts").length > 0) {
        var stackedOptions = {
            series: [
                { name: "Sales", data: [] }, // Empty initially
                { name: "Purchase", data: [] }, // Empty initially
            ],
            colors: ["#28C76F", "#EA5455"],
            chart: {
                type: "bar",
                height: 300,
                stacked: true,
                zoom: { enabled: true },
            },
            responsive: [
                {
                    breakpoint: 280,
                    options: { legend: { position: "bottom", offsetY: 0 } },
                },
            ],
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: "20%",
                    endingShape: "rounded",
                },
            },
            xaxis: { categories: [] }, // Empty initially
            legend: { position: "right", offsetY: 40 },
            fill: { opacity: 1 },
        };

        var stackedChart = new ApexCharts(
            document.querySelector("#sales_charts"),
            stackedOptions
        );
        stackedChart.render();

        // Fetch data dynamically for stacked chart
        $.ajax({
            url: "/get-sales-data",
            method: "GET",
            success: function (response) {
                // Update series and x-axis categories for the stacked chart
                stackedChart.updateSeries([
                    { name: "Sales", data: response.sales },
                    { name: "Purchase", data: response.purchase },
                ]);
                stackedChart.updateOptions({
                    xaxis: { categories: response.categories },
                });
            },
            error: function (error) {
                console.error("Error fetching stacked chart data", error);
            },
        });
    }
});
