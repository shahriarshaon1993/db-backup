<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { backup } from '@/routes/systems';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { Download } from 'lucide-vue-next';

interface Props {
    system: Object;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'System',
        href: dashboard().url,
    },
];

const onBackup = () => {
    router.get(backup({ system: props.system.slug }));
};
</script>

<template>
    <Head title="System" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex justify-between gap-4 rounded-xl p-4">
            <h3 class="mb-0.5 text-base font-medium">{{ system.name }}</h3>
            <div>
                <Button class="cursor-pointer" @click="onBackup">
                    <Download />
                    <span>Create Backup</span>
                </Button>
            </div>
        </div>
    </AppLayout>
</template>
