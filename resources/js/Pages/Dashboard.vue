<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Line } from 'vue-chartjs';
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend
} from 'chart.js'

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend
)

const props = defineProps({
    labels: Array,
    stockIn: Array,
    stockOut: Array
});

const chartOptions = {
    responsive: true,
    scales: {
        x: {
            grid: {
                color: "#495057"
            }
        },
        y: {
            grid: {
                color: "#495057"
            }
        }
    }
};

const chartData = {
    labels: props.labels,
    datasets: [
        {
            label: 'Stock In',
            backgroundColor: 'transparent',
            data: props.stockIn,
            borderWidth: 3,
            borderColor: '#0C6DFD',
        },
        {
            label: 'Stock Out',
            backgroundColor: 'transparent',
            data: props.stockOut,
            borderWidth: 3,
            borderColor: '#FFC007',
        }
    ]
};
</script>

<template>
    <AppLayout title="Dashboard">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Dashboard</h1>
        </div>

        <div class="row">
            <div class="col">
                <Line id="myChart" :options="chartOptions" :data="chartData" />
            </div>
        </div>
    </AppLayout>
</template>
