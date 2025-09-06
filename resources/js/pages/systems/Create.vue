<script setup lang="ts">
import BackupTable from '@/components/app/BackupTable.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { backup } from '@/routes/systems';
import { type BreadcrumbItem } from '@/types';
import { Head, router, usePage } from '@inertiajs/vue3';
import { Download } from 'lucide-vue-next';
import { toast } from 'vue-sonner';

interface Props {
    system: Object;
}

const props = defineProps<Props>();

const page = usePage();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'System',
        href: dashboard().url,
    },
];

const onBackup = () => {
    router.get(
        backup({ system: props.system.slug }),
        {},
        {
            preserveScroll: true,
            onSuccess: () => {
                toast('Success!', {
                    description: page.props.flash.success,
                    action: {
                        label: 'Undo',
                        onClick: () => console.log('Undo'),
                    },
                });
            },
            onError: (errors) => {
                toast.error('Backup failed!');
                console.error(errors);
            },
        },
    );
};
</script>

<template>
    <Head title="System" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="hidden h-full flex-1 flex-col space-y-8 p-8 md:flex">
            <div class="flex items-center justify-between space-y-2">
                <div>
                    <h3 class="text-2xl font-bold tracking-tight">{{ system.name }}</h3>
                    <p class="text-muted-foreground">
                        Here's a list of your <b>{{ system.name }}</b> database backups!
                    </p>
                </div>
                <div class="flex items-center space-x-2">
                    <Button class="cursor-pointer" @click="onBackup">
                        <Download />
                        <span>Take Backup</span>
                    </Button>
                </div>
            </div>

            <BackupTable :backups="system.backups" />
        </div>
    </AppLayout>
</template>
