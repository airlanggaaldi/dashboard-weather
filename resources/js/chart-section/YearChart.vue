<template>
    <div class="p-4 flex justify-center gap-3">
        <div class="grid gap-3">
            <div
                class="border border-gray-100 rounded-box shadow-md p-3 grid gap-4"
            >
                <div>
                    <div
                        class="w-full text-2xl font-black p-1 text-info-content"
                    >
                        <h1>
                            <i class="fa-solid fa-chart-simple"></i> Average Max
                            and Min Temperature
                        </h1>
                    </div>
                </div>
                <!-- <div>
                        <div class="card w-96 bg-base-100 card-xs shadow-sm p-2">
                            <div class="card-body">
                                <h2 class="card-title">{{ maxTempTotal.value.max_temp }}</h2>
                                <p>
                                    The year with the highest temperature was {{ maxTempTotal.value.year }}
                                </p>
                            </div>
                        </div>
                    </div> -->
                <div
                    class="border border-base-content/10 rounded-box w-220 p-1 h-100"
                >
                    <Line :data="data" :options="options" />
                </div>
            </div>
            <div
                class="border border-gray-100 rounded-box shadow-md p-3 grid gap-4"
            >
                <div>
                    <div
                        class="w-full text-2xl font-black p-1 text-info-content"
                    >
                        <h1>
                            <i class="fa-solid fa-chart-simple"></i> Total Days
                            with Extreme Weather
                        </h1>
                    </div>
                </div>
                <div
                    class="border border-base-content/10 rounded-box w-220 p-1 h-100"
                >
                    <Line :data="dataExtrem" :options="options" />
                </div>
            </div>
        </div>
        <div class="border border-gray-100 rounded-box shadow-md p-3">
            <div class="w-full text-2xl font-black p-1 text-info-content mb-4">
                <h1>
                    <i class="fa-solid fa-chart-simple"></i> Annual Data Summary
                </h1>
            </div>
            <div
                class="flex gap-1 border border-base-content/10 bg-amber-50 rounded-box w-110 p-1 card card-sm mb-2"
            >
                <div class="card-body">
                    <h2 class="card-title text-xl">
                        {{ summary.max.year }}
                        <div class="badge badge-neutral badge-dash">
                            {{ summary.max.value.toFixed(2) }}°
                        </div>
                    </h2>
                    <p>Year with the highest average temperature</p>
                </div>
            </div>
            <div
                class="flex gap-1 border border-base-content/10 bg-emerald-50 rounded-box w-110 p-1 card card-sm mb-2"
            >
                <div class="card-body">
                    <h2 class="card-title text-xl">
                        {{ summary.min.year }}
                        <div class="badge badge-neutral badge-dash">
                            {{ summary.min.value.toFixed(2) }}°
                        </div>
                    </h2>
                    <p>Year with the lowest average temperature.</p>
                </div>
            </div>
            <div
                class="flex gap-1 border border-base-content/10 bg-gray-50 rounded-box w-110 p-1 card card-sm mb-2"
            >
                <div class="card-body">
                    <h2 class="card-title text-xl">
                        {{ summary.rain.year }}
                        <div class="badge badge-neutral badge-dash">
                            {{ summary.rain.value }} days
                        </div>
                    </h2>
                    <p>The year with the most rainy days.</p>
                </div>
            </div>
            <div
                class="flex gap-1 border border-base-content/10 bg-blue-50 rounded-box w-110 p-1 card card-sm mb-2"
            >
                <div class="card-body">
                    <h2 class="card-title text-xl">
                        {{ summary.snow.year }}
                        <div class="badge badge-neutral badge-dash">
                            {{ summary.snow.value }} days
                        </div>
                    </h2>
                    <p>The year with the most snowy days.</p>
                </div>
            </div>
            <div
                class="flex gap-1 border border-base-content/10 bg-cyan-50 rounded-box w-110 p-1 card card-sm mb-2"
            >
                <div class="card-body">
                    <h2 class="card-title text-xl">
                        {{ summary.wind.year }}
                        <div class="badge badge-neutral badge-dash">
                            {{ summary.wind.value }} days
                        </div>
                    </h2>
                    <p>The year with the windiest days.</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend,
} from "chart.js";
import { computed, reactive, ref, watch } from "vue";
import { Line } from "vue-chartjs";

ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend
);

export default {
    name: "YearChart",
    components: {
        Line,
    },
    props: {
        info: {
            type: Object,
            required: true,
        },
    },
    setup(props) {
        const params = ref({ ...props.info });

        const dataChart = ref([]);
        const dataChartExtrem = ref([]);
        const summary = reactive({
            max: { year: 0, value: 0 },
            min: { year: 0, value: 0 },
            rain: { year: 0, value: 0 },
            snow: { year: 0, value: 0 },
            wind: { year: 0, value: 0 },
        });

        fetchData();

        watch(
            () => props.info,
            (newVal) => {
                params.value = { ...newVal };
                fetchData();
            },
            { deep: true } // deep:true karena info object
        );

        function fetchData() {
            axios
                .get("http://localhost:8000/api/year-average", {
                    params: props.info,
                })
                .then((res) => {
                    console.log("chart fetched");
                    dataChart.value = res.data;
                })
                .catch((err) => {
                    console.log("error:", err);
                });

            axios
                .get("http://localhost:8000/api/year-extrem", {
                    params: props.info,
                })
                .then((res) => {
                    console.log("chart extrem fetched");
                    dataChartExtrem.value = res.data;
                })
                .catch((err) => {
                    console.log("error:", err);
                });

            axios
                .get("http://localhost:8000/api/summary", {
                    params: props.info,
                })
                .then((res) => {
                    console.log("chart summary fetched");
                    summary.max = res.data.max[0];
                    summary.min = res.data.min[0];
                    summary.rain = res.data.rain[0];
                    summary.snow = res.data.snow[0];
                    summary.wind = res.data.wind[0];
                })
                .catch((err) => {
                    console.log("error:", err);
                });
        }

        const data = computed(() => ({
            labels: dataChart.value.map((item) => item.year),
            datasets: [
                {
                    label: "Max Temp",
                    backgroundColor: "#ff637d",
                    data: dataChart.value.map((item) => item.max_temp),
                },
                {
                    label: "Min Temp",
                    backgroundColor: "#00d390",
                    data: dataChart.value.map((item) => item.min_temp),
                },
            ],
        }));

        const dataExtrem = computed(() => ({
            labels: dataChartExtrem.value.map((item) => item.year),
            datasets: [
                {
                    label: "Rainy Days",
                    backgroundColor: "#ff637d",
                    data: dataChartExtrem.value.map((item) => item.rain_day),
                },
                {
                    label: "Snowy Days",
                    backgroundColor: "#00d390",
                    data: dataChartExtrem.value.map((item) => item.snow_day),
                },
                {
                    label: "Windy Days",
                    backgroundColor: "#fcb700",
                    data: dataChartExtrem.value.map((item) => item.wind_day),
                },
            ],
        }));

        const options = {
            responsive: true,
            maintainAspectRatio: false,
        };

        return { data, dataExtrem, summary, options };
    },
};

// const maxTempTotal = ref({
//     year: 0,
//     max_temp: 0
// });

// maxTempTotal.value = dataChart.value.reduce((a, b) => a.max_temp > b.max_temp ? a : b);
// console.log(maxTempTotal.value)
</script>
