<template>

    <div>
        <router-link to="/">Home </router-link>
        <h1>Posts</h1>
    </div>


    <div v-if="posts">
        <div v-for="post in posts" :key="post.id">

            <router-link :to="`/posts/${post.post_id}`">  <h2>{{post.post_id }} {{ post.post }}</h2> </router-link>
            <p><strong>Created:</strong> {{ post.created }}</p>
            <p><strong>User:</strong> {{ post.user.name }} ({{ post.user.email }})</p>
            <p><strong>Likes:</strong> {{ post.likes_count }}</p>
        </div>
    </div>

    <p v-else>No posts available</p>

</template>
<script setup>
import axios from "axios";
import { ref, onMounted } from "vue";

const posts = ref([]);


const fetchPosts = async () => {
    try {
        const res = await axios.get("http://127.0.0.1:8000/api/v1/posts");
        console.log("Fetched posts data:", res.data);
        posts.value = res.data.data; // Store response in posts
    } catch (error) {

        console.log(error);
    }
};

onMounted(fetchPosts);
</script>
