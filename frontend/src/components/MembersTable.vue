<template>
    <div class="body">
        <div v-if="loading"><br />Loading...</div>
        <div v-else>
            <h1>Chess club members</h1>
            <button @click="redirectToAdd">Add member</button><br /><br />
            <div v-if="members.length !== 0">
                <table>
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Join Date</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="member in members" :key="member.id">
                            <td>{{ member.id }}</td>
                            <td>{{ member.name }}</td>
                            <td>{{ member.email }}</td>
                            <td>{{ formatDate(member.joinDate) }}</td>
                            <td><button @click="redirectToDetails(member.id)">Details</button></td>
                            <td><button @click="redirectToEdit(member.id)">Edit</button></td>
                            <td><button @click="deleteMember(member.id)">Delete</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-else>
                <p>No members</p>
            </div>
        </div>
    </div>
</template>
  
<script setup lang="ts">
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

interface Member {
    id: number;
    name: string;
    email: string;
    joinDate: string;
}

const members = ref<Member[]>([]);
const loading = ref(true);

const fetchData = async () => {
    try {
        const response = await axios.get<Member[]>('members');
        members.value = response.data;
        loading.value = false;

    } catch (error) {
        console.error('Error fetching members:', error);
        loading.value = false;
    }
};

const router = useRouter();
const redirectToDetails = (id: number) => {
    router.push(`/members/${id}`);
};
const redirectToAdd = () => {
    router.push(`/members/add`);
};
const redirectToEdit = (id: number) => {
    router.push(`/members/edit/${id}`);
};


const deleteMember = async (id: number) => {
    try {
        members.value = members.value.filter(member => member.id !== id);
        await axios.delete(`members/${id}`);
    } catch (error) {
        console.error('Error deleting member:', error);
    }
};

const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleDateString();
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