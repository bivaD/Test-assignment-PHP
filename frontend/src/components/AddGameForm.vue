<template>
    <div class="body">
        <h1>Add New Game</h1>
        <form @submit.prevent="addGame">
            <div>
                <label for="blackId">Black Player ID:</label>
                <input type="number" id="blackId" v-model="blackId" required>
            </div>
            <div>
                <label for="whiteId">White Player ID:</label>
                <input type="number" id="whiteId" v-model="whiteId" required>
            </div>
            <div>
                <label for="winnerColor">Winner:</label>
                <select id="winnerColor" v-model="winnerColor" required>
                    <option value="black">Black</option>
                    <option value="white">White</option>
                </select>
            </div>
            <div>
                <label for="moves">Moves:</label>
                <input type="number" id="moves" v-model="moves" required>
            </div>
            <button type="submit">Add Game</button>
            <p v-if="message" :class="{ 'success-message': isSuccess, 'error-message': !isSuccess }">{{ message }}</p>
        </form>
    </div>
</template>
  
<script setup lang="ts">
import { ref } from 'vue';
import axios from 'axios';

const blackId = ref<number>(0);
const whiteId = ref<number>(0);
const winnerColor = ref<string>('black');
const moves = ref<number>(0);
const message = ref<string>('');
const isSuccess = ref<boolean>(false);

const addGame = async () => {
    try {
        await axios.post('games', {
            black: blackId.value,
            white: whiteId.value,
            winner: winnerColor.value,
            moves: moves.value
        });
        isSuccess.value = true;
        message.value = 'New game added successfully.';

        // Reset 
        blackId.value = 0;
        whiteId.value = 0;
        winnerColor.value = 'black';
        moves.value = 0;

        setTimeout(() => {
            message.value = '';
        }, 5000);
    } catch (error) {
        isSuccess.value = false;
        message.value = 'Error adding new game. Please try again.';
        setTimeout(() => {
            message.value = '';
        }, 5000);
        console.error('Error adding new game:', error);
    }
};
</script>
  
<style scoped>
.success-message {
    color: green;
}

.error-message {
    color: red;
}

.body {
    padding-left: 1rem;
}
</style>