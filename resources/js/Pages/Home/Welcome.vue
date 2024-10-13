<template>
    <AppLayout title="Home">
        <template #header>
            <Hero :features="features">
                <template #heroTitle>
                    "Beheer, plan, klaar..."
                                </template>
                <template #heroTitle2>
                    Welkom bij Evenatora – Jouw hub voor evenementen!
                </template>
                <template #heroDescription>
                    Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis suscipit
                    eaque, iste dolor cupiditate blanditiis ratione.
                </template>
                <template #heroImage>
                    <img src="assests/hero.jpg"
                        alt="Product screenshot"
                        class="w-[48rem] max-w-none rounded-xl shadow-xl ring-1 ring-gray-400/10 sm:w-[57rem] md:-ml-4 lg:-ml-0"
                        width="2432" height="1442" />
                </template>
            </Hero>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <Welcome />
                </div>
            </div>
        </div>
    </AppLayout>
</template>



<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import Hero from '@/Components/Hero.vue';
import { CloudArrowUpIcon, TicketIcon, CurrencyEuroIcon } from '@heroicons/vue/20/solid'

const features = [
    {
        name: 'Eenvoudig Evenementbeheer',
        description:
            'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis suscipit eaque, iste dolor cupiditate blanditiis ratione.',
        icon: CloudArrowUpIcon,
    },
    {
        name: 'Snelle Registratie en Digitale Tickets',
        description: 'Anim aute id magna aliqua ad ad non deserunt sunt. Qui irure qui lorem cupidatat commodo.',
        icon: TicketIcon,
    },
    {
        name: 'Realtime Statistieken en Betalingsbeheer',
        description: 'Ac tincidunt sapien vehicula erat auctor pellentesque rhoncus. Et magna sit morbi lobortis.',
        icon: CurrencyEuroIcon,
    },
]
</script>
