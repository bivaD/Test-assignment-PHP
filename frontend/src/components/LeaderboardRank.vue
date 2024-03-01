<template>
    <div class="body">
        <div v-if="loading"><br />Loading...</div>
        <div v-else>
            <h1>Leaderboard Ranks</h1>
            <div v-if="ranks.length !== 0">
                <table>
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Won Games</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(rank, index) in ranks" :key="rank.email">
                            <td>{{ index + 1 }}</td>
                            <td>{{ rank.name }}</td>
                            <td>{{ rank.email }}</td>
                            <td>{{ rank.wonGames }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-else>
                <p>No ranks</p>
            </div>
        </div>
    </div>
</template>
  
<script setup lang="ts">
import { ref } from 'vue';
import axios from 'axios';

interface Rank {
    name: string;
    email: string;
    wonGames: number;
}

const ranks = ref<Rank[]>([]);
const loading = ref(true);

const fetchData = async () => {
    try {
        const response = await axios.get<Rank[]>('leaderboard/rank');
        ranks.value = response.data;
        loading.value = false;
    } catch (error) {
        console.error('Error fetching ranks:', error);
        loading.value = false;
    }
};

fetchData();
</script>
  
<style scoped>
.body {
    padding-left: 1rem;
    padding-right: 1rem;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th,
td {
    border: 1px solid #ccc;
    padding: 8px;
    text-align: left;
}

th {
    background-color: #f2f2f2;
}
</style>
  