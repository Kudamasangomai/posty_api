<template>
    <div>
        <h2>HOME</h2>
        <router-link to="/about">About </router-link>
        <br/><br/>
        <router-link to="/posts">Posts</router-link>
    </div>


    <!-- <button @click.prevent="getValue">Trigger Endpoint</button>
    <p v-if="response">{{ response.data }}</p> -->
</template>

<script setup>
import axios from "axios";
import { ref } from "vue";

const response = ref();

const getValue = async () => {
    try {
        const res = await axios.get("http://127.0.0.1:8000/api/v1/posts");
        response.value = res.data; // Ensure only `data` is stored in the response
    } catch (error) {
        // Do something with the error
        console.log(error);
    }
};

</script>
