<template>
    <div class="body">
        <h1>Add New Member</h1>
        <form @submit.prevent="addMember">
            <div>
                <label for="name">Name:</label>
                <input type="text" id="name" v-model="name" required>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" v-model="email" required>
            </div>
            <button type="submit">Add Member</button>
            <p v-if="message" :class="{ 'success-message': isSuccess, 'error-message': !isSuccess }">{{ message }}</p>
        </form>
    </div>
</template>
  
<script setup lang="ts">
import { ref } from 'vue';
import axios from 'axios';

const name = ref('');
const email = ref('');
const message = ref('');
const isSuccess = ref(false);

const addMember = async () => {
    try {
        await axios.post('members', {
            name: name.value,
            email: email.value
        });
        isSuccess.value = true;
        message.value = 'New member added successfully.';

        name.value = '';
        email.value = '';

        setTimeout(() => {
            message.value = '';
        }, 5000);
    } catch (error) {
        isSuccess.value = false;
        message.value = 'Error adding new member. Please try again.';
        setTimeout(() => {
            message.value = '';
        }, 5000);
        console.error('Error adding new member:', error);
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

