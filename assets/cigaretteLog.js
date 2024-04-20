import axios from 'axios';
import {Chart, LinearScale, LineController, CategoryScale, PointElement, LineElement} from 'chart.js';
import ChartDataLabels from 'chartjs-plugin-datalabels';

Chart.register(LineController, LinearScale, CategoryScale, PointElement, LineElement);
Chart.register(ChartDataLabels);

document.addEventListener('DOMContentLoaded', function () {
    axios.get('/cigarette_log/data')
        .then(function (response) {
            var cigarette_counts = response.data;

            // Initialize percentageDifference of the first data point
            if (cigarette_counts.length > 0) {
                cigarette_counts[0].percentageDifference = '0';
            }

            // Calculate percentage difference for the rest
            for (let i = 1; i < cigarette_counts.length; i++) {
                let previousCount = cigarette_counts[i - 1].count;
                let currentCount = cigarette_counts[i].count;
                let difference = ((currentCount - previousCount) / previousCount) * 100;
                cigarette_counts[i].percentageDifference = isFinite(difference) ? difference.toFixed(2) : '0'; // or '' for nothing
            }
            // Create the chart
            var ctx = document.getElementById('cigaretteCountChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: cigarette_counts.map(function (item) {
                        return item.day;
                    }),
                    datasets: [{
                        label: 'Cigarette Count',
                        data: cigarette_counts.map(function (item) {
                            return item.count;
                        }),
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        datalabels: {
                            color: function (context) {
                                let index = context.dataIndex;
                                let value = cigarette_counts[index].percentageDifference;
                                return value < 0 ? 'green' : 'red';
                            },
                            display: function (context) {
                                return context.dataset.data[context.dataIndex] > 0;
                            },
                            formatter: function (value, context) {
                                return cigarette_counts[context.dataIndex].percentageDifference + '%';
                            },
                            font: {
                                weight: 'bold',
                                size: 16
                            }
                        }
                    }
                }
            });
        })
        .catch(function (error) {
            console.log(error);
        });
});