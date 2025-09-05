<script setup lang="ts">
import { SidebarGroup, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { store } from '@/routes/systems';

import InputError from '@/components/InputError.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { urlIsActive } from '@/lib/utils';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { MonitorCog, Plus } from 'lucide-vue-next';
import { toast } from 'vue-sonner';

import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps<{
    items: NavItem[];
}>();

const page = usePage();

const open = ref(false);
const form = useForm({
    name: '',
    db_host: '127.0.0.1',
    db_port: 3306,
    db_username: '',
    db_password: '',
    db_name: '',
});

const onSubmit = () => {
    form.submit(store(), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            (form.clearErrors(), (open.value = false));

            toast('Success!', {
                description: page.props.flash.success,
                action: {
                    label: 'Undo',
                    onClick: () => console.log('Undo'),
                },
            });
        },
    });
};
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel>Platform</SidebarGroupLabel>
        <SidebarMenu>
            <SidebarMenuItem v-for="item in items" :key="item.title">
                <SidebarMenuButton as-child :is-active="urlIsActive(item.href, page.url)" :tooltip="item.title">
                    <Link :href="item.href">
                        <component :is="item.icon" />
                        <span>{{ item.title }}</span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
        <SidebarGroupLabel>Projects</SidebarGroupLabel>
        <SidebarMenu>
            <SidebarMenuItem>
                <SidebarMenuButton>
                    <Dialog v-model:open="open">
                        <DialogTrigger as-child>
                            <button type="button" class="flex w-full cursor-pointer items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <MonitorCog class="size-5" />
                                    Add System
                                </div>
                                <Plus class="size-5" />
                            </button>
                        </DialogTrigger>
                        <DialogContent>
                            <form @submit.prevent="onSubmit" class="space-y-3">
                                <DialogHeader>
                                    <DialogTitle>Add a system</DialogTitle>
                                    <DialogDescription>Add a new system with there secret informations.</DialogDescription>
                                </DialogHeader>

                                <div class="mt-6 grid gap-6">
                                    <div class="grid gap-2">
                                        <Label for="name">System name</Label>
                                        <Input
                                            id="name"
                                            type="text"
                                            required
                                            autofocus
                                            :tabindex="1"
                                            autocomplete="name"
                                            v-model="form.name"
                                            placeholder="System name"
                                        />
                                        <InputError :message="form.errors.name" />
                                    </div>

                                    <div class="grid gap-2">
                                        <Label for="db_name">DB name</Label>
                                        <Input id="db_name" type="text" v-model="form.db_name" placeholder="DB name" />
                                        <InputError :message="form.errors.db_name" />
                                    </div>

                                    <div class="grid gap-2">
                                        <Label for="db_host">DB Host</Label>
                                        <Input id="db_host" type="text" v-model="form.db_host" placeholder="DB host" />
                                        <InputError :message="form.errors.db_host" />
                                    </div>

                                    <div class="grid gap-2">
                                        <Label for="db_port">DB port</Label>
                                        <Input id="db_port" type="text" v-model="form.db_port" placeholder="DB port" />
                                        <InputError :message="form.errors.db_port" />
                                    </div>

                                    <div class="grid gap-2">
                                        <Label for="db_username">DB username</Label>
                                        <Input id="db_username" type="text" v-model="form.db_username" placeholder="DB username" />
                                        <InputError :message="form.errors.db_username" />
                                    </div>

                                    <div class="grid gap-2">
                                        <Label for="db_password">DB password</Label>
                                        <Input id="db_password" type="text" v-model="form.db_password" placeholder="DB password" />
                                        <InputError :message="form.errors.db_password" />
                                    </div>
                                </div>

                                <DialogFooter class="gap-2">
                                    <DialogClose as-child>
                                        <Button
                                            variant="secondary"
                                            @click="
                                                {
                                                    form.reset();
                                                    form.clearErrors();

                                                    open = false;
                                                }
                                            "
                                        >
                                            Cancel
                                        </Button>
                                    </DialogClose>

                                    <Button type="submit" :disabled="form.processing"> Save </Button>
                                </DialogFooter>
                            </form>
                        </DialogContent>
                    </Dialog>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
