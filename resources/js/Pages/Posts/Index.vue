<template>
    <AppLayout>
        <Container>
            <div>
                <PageHeading v-text="selectedTopic ? selectedTopic.name : 'All Posts'" />
                <p v-if="selectedTopic" class="mt-1 text-sm text-gray-600">
                    {{ selectedTopic.description }}
                </p>

                <menu class="flex space-x-1 mt-3 overflow-x-auto pb-2 pt-1">
                    <li>
                        <!-- to seve search by topic -->
                        <Pill :href="route('posts.index', { query: searchForm.query })" :filled="!selectedTopic">All
                            Posts</Pill>

                    </li>
                    <li v-for="topic in topics" :key="topic.id">
                        <!-- to seve search by topic -->
                        <Pill :href="route('posts.index', { topic: topic.slug, query: searchForm.query })"
                            :filled="topic.id === selectedTopic?.id">
                            {{ topic.name }}
                        </Pill>
                    </li>
                </menu>

                <form @submit.prevent="search" class="mt-4">
                    <div>
                        <InputLabel for="query">Search</InputLabel>
                        <div class="flex space-x-2 mt-1">
                            <TextInput v-model="searchForm.query" class="w-full" id="query" />
                            <SecondaryButton type="submit">Search</SecondaryButton>
                            <DangerButton v-if="searchForm.query" @click="clearSearch">Clear</DangerButton>

                        </div>
                    </div>
                </form>

            </div>


            <PostsCardsGrid :posts="posts.data" :formattedDate="formattedDate" />

            <Pagination :meta="posts.meta" :only="['posts']" class="mt-2" />
        </Container>
    </AppLayout>
</template>
<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Container from "@/Components/Container.vue";
import Pagination from "@/Components/Pagination.vue";
import { Link, useForm, usePage } from "@inertiajs/vue3";
import { formatDistance, parseISO } from "date-fns";
import { relativeDate } from "@/Utilities/date.js";
import PageHeading from "@/Components/PageHeading.vue";
import Pill from "@/Components/Pill.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import DangerButton from "@/Components/DangerButton.vue";
import PostsCardsGrid from "@/Components/PostsCardsGrid.vue";

//from controller, to detect what is in query
const props = defineProps(["posts", "topics", "selectedTopic", "query"]);

const formattedDate = (post) => relativeDate(post.created_at);

//to search within all posts or within topic
const searchForm = useForm({
    query: props.query,
    page: 1, //reset pagination when new search to search from page 1
});

const page = usePage(); //to get curent url index or topic
const search = () => searchForm.get(page.url);
const clearSearch = () => {
    searchForm.query = '';
    search();
};


</script>