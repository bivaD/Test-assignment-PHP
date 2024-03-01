<template>
    <div class="body">
        <h1>Leaderboard Statistics</h1>
        <div v-if="loading">Loading...</div>
        <div v-else>
            <div v-if="shortestGame && longestGame">
                <div>
                    <h2>Shortest Game</h2>
                    <p>Moves: {{ shortestGame.moves }}</p>
                    <p>Winner: {{ shortestGame.winnerName }} ({{ shortestGame.winnerEmail }})</p>
                    <p>Loser: {{ shortestGame.loserName }} ({{ shortestGame.loserEmail }})</p>
                    <p>Date: {{ formatDate(shortestGame.date) }}</p>
                </div>
                <div>
                    <h2>Longest Game</h2>
                    <p>Moves: {{ longestGame.moves }}</p>
                    <p>Winner: {{ longestGame.winnerName }} ({{ longestGame.winnerEmail }})</p>
                    <p>Loser: {{ longestGame.loserName }} ({{ longestGame.loserEmail }})</p>
                    <p>Date: {{ formatDate(longestGame.date) }}</p>
                </div>
            </div>
            <div v-else>
                In this chess club, nobody has ever logged a game.
            </div>
        </div>
    </div>
</template>
  
<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';

interface GameStats {
    moves: number;
    winnerName: string;
    winnerEmail: string;
    loserName: string;
    loserEmail: string;
    date: string;
}

const loading = ref(true);
const shortestGame = ref<GameStats | null>(null);
const longestGame = ref<GameStats | null>(null);

const fetchData = async () => {
    try {
        const response = await axios.get<{ shortestGame: GameStats; longestGame: GameStats }>('leaderboard/stats');
        shortestGame.value = response.data.shortestGame;
        longestGame.value = response.data.longestGame;
        loading.value = false;
    } catch (error) {
        console.error('Error fetching leaderboard statistics:', error);
        loading.value = false;
    }
};

onMounted(fetchData);

const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleDateString();
};
</script>
  


<style scoped>
.body {
    padding-left: 1rem;
}
</style>