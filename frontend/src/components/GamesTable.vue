<template>
    <div class="body">
        <div v-if="loading"><br />Loading...</div>
        <div v-else>
            <h1>Game Log</h1>
            <button @click="redirectToAdd">Add game</button><br /><br />
            <table>
                <thead>
                    <tr>
                        <th>Black Player</th>
                        <th>White Player</th>
                        <th>Winner</th>
                        <th>Moves</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="game in games" :key="game.date">
                        <td>{{ game.blackName }}</td>
                        <td>{{ game.whiteName }}</td>
                        <td>{{ game.winner }}</td>
                        <td>{{ game.moves }}</td>
                        <td>{{ formatDate(game.date) }}</td>
                        <td><button @click="deleteGame(game.id)">Delete</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
  
<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

interface Game {
    id: number;
    blackName: string;
    whiteName: string;
    winner: string;
    date: string;
    moves: number;
}

const games = ref<Game[]>([]);
const loading = ref(true);

const fetchData = async () => {
    try {
        const response = await axios.get<Game[]>('games');
        games.value = response.data;
        loading.value = false;

    } catch (error) {
        loading.value = false;
        console.error('Error fetching game records:', error);
    }
};

const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleDateString();
};

const router = useRouter();
const redirectToAdd = () => {
    router.push(`/games/add`);
};


const deleteGame = async (id: number) => {
    try {
        games.value = games.value.filter(games => games.id !== id);
        await axios.delete(`games/${id}`);
    } catch (error) {
        console.error('Error deleting member:', error);
    }
};

onMounted(fetchData);
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