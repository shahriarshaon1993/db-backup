<script lang="ts" setup>
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
} from '@/components/ui/alert-dialog';
import { Button } from '@/components/ui/button';
import { Trash2 } from 'lucide-vue-next';
import { ref } from 'vue';

interface Props {
    length?: number;
}

withDefaults(defineProps<Props>(), { length: 0 });

const emit = defineEmits(['onDestroy']);

const isOpen = ref(false);

const handleDestroy = () => {
    emit('onDestroy');
    isOpen.value = false;
};
</script>

<template>
    <AlertDialog v-model:open="isOpen">
        <!-- Trigger: A button to open the dialog -->
        <AlertDialogTrigger as-child>
            <Button :disabled="length === 0" variant="destructive">
                <Trash2 class="h-4 w-4" />
                ({{ length }})
            </Button>
        </AlertDialogTrigger>

        <!-- Dialog Content -->
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>Are you sure you want to delete this {{ length }} items?</AlertDialogTitle>
                <AlertDialogDescription> This action cannot be undone. You are about to delete</AlertDialogDescription>
            </AlertDialogHeader>

            <AlertDialogFooter>
                <AlertDialogCancel>Cancel</AlertDialogCancel>
                <AlertDialogAction @click="handleDestroy">Delete</AlertDialogAction>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
