<template>
    <div class="top-menu">
        <div class="logo">
            <font-awesome-icon :icon="faChess" />
            <span class="header">Chess Club</span>
        </div>
        <div class="nav-buttons">
            <button @click="redirectToHome">Home</button>
            <button @click="redirectToMembers">Members</button>
            <button @click="redirectToGames">Games</button>
            <button @click="redirectToLeaderboard">Leaderboard</button>
        </div>
    </div>
</template>
  
<script setup lang="ts">
import router from '@/router';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faChess } from '@fortawesome/free-solid-svg-icons';

const redirectToHome = () => {
    router.push(`/`);
};

const redirectToMembers = () => {
    router.push(`/members`);
};

const redirectToLeaderboard = () => {
    router.push(`/leaderboard`);
};

const redirectToGames = () => {
    router.push(`/games`);
};

</script>
  
<style scoped>
.header {
    padding-right: 30px;
}

.top-menu {
    display: flex;
    align-items: center;
    padding: 1rem;
    background-color: #f0f0f0;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.logo {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1.2rem;
}

.logo i {
    font-size: 1.5rem;
}

.nav-buttons button {
    border: none;
    background-color: transparent;
    cursor: pointer;
    padding: 0.5rem 1rem;
    font-size: 1rem;
    color: #333;
    transition: color 0.3s ease;
}

.nav-buttons button:hover {
    color: #666;
}
</style>