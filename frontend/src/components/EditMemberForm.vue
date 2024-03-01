<template>
    <div>
        <h1>Edit Member</h1>
        <form @submit.prevent="editMember">
            <div>
                <label for="name">Name:</label>
                <input type="text" id="name" v-model="name" required>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" v-model="email" required>
            </div>
            <button type="submit">Update Member</button>
            <p v-if="message" :class="{ 'success-message': isSuccess, 'error-message': !isSuccess }">{{ message }}</p>
        </form>
    </div>
</template>
  
<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';

const name = ref('');
const email = ref('');
const message = ref('');
const isSuccess = ref(false);


const memberId = window.location.pathname.split('/').pop();

const fetchMemberDetails = async () => {
    try {
        const response = await axios.get(`members/${memberId}`);
        const { name: memberName, email: memberEmail } = response.data;
        name.value = memberName;
        email.value = memberEmail;
    } catch (error) {
        console.error('Error fetching member details:', error);
    }
};

onMounted(fetchMemberDetails);

const editMember = async () => {
    try {
        await axios.put(`members/${memberId}`, {
            name: name.value,
            email: email.value
        });
        isSuccess.value = true;
        message.value = 'Member details updated successfully.';

        setTimeout(() => {
            message.value = '';
        }, 5000);
    } catch (error) {
        isSuccess.value = false;
        message.value = 'Error updating member details. Please try again.';
        setTimeout(() => {
            message.value = '';
        }, 5000);
        console.error('Error updating member details:', error);
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
</style>
