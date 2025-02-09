<template>
    <div>
        <router-link to="/">Home </router-link>
        <h1>Post Details</h1>
        <div v-if="post">
            <h2>{{ post.post }}</h2>
            <p>{{ post.created }}</p>
            <p><strong>User:</strong> {{ post.user.name }} ({{ post.user.email }})</p>
            <p><strong>Likes:</strong> {{ post.likes_count }}</p>
            <img :src="`http://127.0.0.1:8000/${post.image}`" alt="Post image" v-if="post.image" />
        </div>
        <p v-else>Loading post...</p>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRoute } from 'vue-router';

const post = ref(null);
const route = useRoute();

const fetchPost = async () => {
    try {
        const res = await axios.get(`http://127.0.0.1:8000/api/v1/posts/${route.params.post_id}`);

        post.value = res.data.data;
    } catch (error) {
        console.error('Error fetching post:', error);
    }
};

onMounted(fetchPost);
</script>
