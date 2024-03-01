<template>
    <div class="body">
        <h1>Member Details</h1>
        <div v-if="member">
            <p><strong>Name:</strong> {{ member.name }}</p>
            <p><strong>Email:</strong> {{ member.email }}</p>
            <p><strong>Wins:</strong> {{ member.wins }}</p>
            <p><strong>Losses:</strong> {{ member.losses }}</p>
            <p><strong>Win Percentage with White:</strong> {{ member.win_percentage_with_white.toFixed(1) }}%</p>
            <p><strong>Win Percentage with Black:</strong> {{ member.win_percentage_with_black.toFixed(1) }}%</p>
            <p><strong>Shortest Win:</strong> {{ member.shortest_game }} moves</p>
        </div>
        <div v-else>
            <p>Loading...</p>
        </div>
    </div>
</template>
  
<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';

interface Member {
    name: string;
    email: string;
    wins: number;
    losses: number;
    win_percentage_with_white: number;
    win_percentage_with_black: number;
    shortest_game: number;
}

const member = ref<Member | null>(null);

onMounted(async () => {
    try {
        const id = window.location.pathname.split('/').pop();
        const response = await axios.get<Member>(`members/${id}`);
        member.value = response.data;
    } catch (error) {
        console.error('Error fetching member details:', error);
    }
});
</script>

<style scoped>
.body {
    padding-left: 1rem;
}
</style>