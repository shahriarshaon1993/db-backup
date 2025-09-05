<script setup lang="ts">
import SystemController from '@/actions/App/Http/Controllers/SystemController';
import { SidebarGroup, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';

import InputError from '@/components/InputError.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { urlIsActive } from '@/lib/utils';
import { type NavItem } from '@/types';
import { Form, Link, usePage } from '@inertiajs/vue3';
import { MonitorCog, Plus } from 'lucide-vue-next';

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

defineProps<{
    items: NavItem[];
}>();

const page = usePage();
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
                    <Dialog>
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
                            <Form
                                v-bind="SystemController.store.form()"
                                :options="{
                                    preserveScroll: true,
                                }"
                                class="space-y-6"
                                v-slot="{ errors, processing, reset, clearErrors }"
                            >
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
                                            name="name"
                                            placeholder="System name"
                                        />
                                        <InputError :message="errors.name" />
                                    </div>

                                    <div class="grid gap-2">
                                        <Label for="db_name">DB name</Label>
                                        <Input id="db_name" type="text" name="db_name" placeholder="DB name" />
                                        <InputError :message="errors.db_name" />
                                    </div>

                                    <div class="grid gap-2">
                                        <Label for="db_host">DB Host</Label>
                                        <Input
                                            id="db_host"
                                            type="text"
                                            name="db_host"
                                            placeholder="DB host"
                                            default-value="127.0.0.1"
                                        />
                                        <InputError :message="errors.db_host" />
                                    </div>

                                    <div class="grid gap-2">
                                        <Label for="db_port">DB port</Label>
                                        <Input
                                            id="db_port"
                                            type="text"
                                            name="db_port"
                                            placeholder="DB port"
                                            default-value="3306"
                                        />
                                        <InputError :message="errors.db_port" />
                                    </div>

                                    <div class="grid gap-2">
                                        <Label for="db_username">DB username</Label>
                                        <Input id="db_username" type="text" name="db_username" placeholder="DB username" />
                                        <InputError :message="errors.db_username" />
                                    </div>

                                    <div class="grid gap-2">
                                        <Label for="db_password">DB password</Label>
                                        <Input id="db_password" type="text" name="db_password" placeholder="DB password" />
                                        <InputError :message="errors.db_password" />
                                    </div>
                                </div>

                                <DialogFooter class="gap-2">
                                    <DialogClose as-child>
                                        <Button
                                            variant="secondary"
                                            @click="
                                                () => {
                                                    clearErrors();
                                                    reset();
                                                }
                                            "
                                        >
                                            Cancel
                                        </Button>
                                    </DialogClose>

                                    <Button type="submit" :disabled="processing"> Save </Button>
                                </DialogFooter>
                            </Form>
                        </DialogContent>
                    </Dialog>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
