<template>
    <div class="p-4 grid justify-center">
        <div class="rounded-box shadow-md border border-gray-100 p-3">
            <div class="mb-4 flex gap-2 justify-end items-center">
                <div class="flex-1 text-3xl font-black p-1 text-info-content">
                    <h1 class=""> <i class="fa-solid fa-table"></i> Data Table</h1>
                </div>
                <label class="select w-60">
                    <span class="label"> Area </span>
                    <select v-model="area" class="select select-text">
                        <option selected disabled>Filter Area</option>
                        <div v-for="item in areas">
                            <option>{{ item.name }}</option>
                        </div>
                    </select>
                </label>
                <label class="input w-60">
                    <span class="label">Start Date</span>
                    <input
                        type="date"
                        v-model="startDate"
                        class="input input-bordered -ml-3"
                    />
                </label>
                <label class="input w-60">
                    <span class="label">End Date</span>
                    <input
                        type="date"
                        v-model="endDate"
                        class="input input-bordered -ml-3"
                    />
                </label>
                <button @click="filter" class="btn btn-neutral shadow-none">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
                <button @click="clearFilter" class="btn">
                    <i class="fa-solid fa-filter-circle-xmark"></i> Clear
                </button>
            </div>
            <div
                class="overflow-x-auto rounded-box border border-base-content/10 bg-base-100 w-340 h-127"
            >
                <table class="table">
                    <thead>
                        <tr>
                            <th>Station ID</th>
                            <th>Date</th>
                            <th>Temp Max</th>
                            <th>Temp Min</th>
                            <th>Temp Average</th>
                            <th>Wind Speed Average</th>
                            <th>Weather Type</th>
                            <th>Elevation</th>
                            <th>Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, i) in data">
                            <td>{{ item.station_id }}</td>
                            <td>{{ item.date }}</td>
                            <td>{{ item.tempMax }}</td>
                            <td>{{ item.tempMin }}</td>
                            <td>{{ item.tempAvg }}</td>
                            <td>{{ item.averageWindSpeed }}</td>
                            <td>{{ item.weatherType }}</td>
                            <td>{{ item.elevation }}</td>
                            <td>{{ item.name }}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="flex justify-center">
                    <span
                        class="loading loading-spinner loading-xl my-2"
                        v-if="loading"
                    ></span>
                </div>
            </div>
            <div class="flex gap-5 justify-center items-center mt-2">
                <button class="btn btn-circle btn-sm" @click="prevPage">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
                <div class="text-sm opacity-50">
                    Page {{ params.page }} of {{ totalPages }}
                </div>
                <button class="btn btn-circle btn-sm" @click="nextPage">
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>
        </div>
    </div>
    <YearChart :info="params" />
</template>

<script setup>
import axios from "axios";
import { reactive, ref } from "vue";
import YearChart from "./chart-section/YearChart.vue";

const startDate = ref();
const endDate = ref();
const area = ref();
const areas = ref([]);
const loading = ref(false);
let totalPages = 0;

const data = ref([]);
const params = reactive({
    page: 1,
    startDate: '',
    endDate: '',
    area:''
});

fetchData();
fetchArea();

function nextPage() {
    if (params.page < totalPages) {
        params.page += 1;
        data.value = [];
        fetchData();
    }
}

function prevPage() {
    if (params.page > 1) {
        params.page -= 1;
        data.value = [];
        fetchData();
    }
}

function filter() {
    if (startDate.value) {
        params.startDate = startDate.value;
    }

    if (endDate.value) {
        params.endDate = endDate.value;
    }

    if (area.value) {
        params.area = area.value;
    }

    if (startDate.value && endDate.value) {
        if (new Date(startDate.value) > new Date(endDate.value)) {
            alert("Start date must be less than End date!");
            return
        }
    }

    params.page = 1;

    data.value = [];
    fetchData();
}

function fetchArea() {
    axios
        .get("http://localhost:8000/api/area")
        .then((res) => {
            areas.value = res.data;
        })
        .catch((err) => {
            console.log("err get area: ", err);
        });
}

function clearFilter() {
    startDate.value = "";
    endDate.value = "";
    area.value = null;
    data.value = [];
    params.page = 1;
    params.startDate = null;
    params.endDate = null;
    params.area = null;

    console.log("area.value");

    fetchData();
    fetchArea();
}

function fetchData() {
    loading.value = true;
    axios
        .get("http://localhost:8000/api/data", { params: params })
        .then((res) => {
            data.value = res.data.rows;
            totalPages = res.data.totalPages;
            // console.log("data",data.value);
            loading.value = false;
        })
        .catch((err) => {
            console.log("error:", err);
        });
}
</script>
