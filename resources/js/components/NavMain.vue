<script setup lang="ts">
import { SidebarGroup, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { urlIsActive } from '@/lib/utils';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { MonitorCog, Plus } from 'lucide-vue-next';
import { Form } from '@inertiajs/vue3';

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
import {Button} from "@/components/ui/button";
import RegisteredUserController from "@/actions/App/Http/Controllers/Auth/RegisteredUserController";

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
                            <button type="button" class="w-full cursor-pointer flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <MonitorCog class="size-5"/>
                                    Add System
                                </div>
                                <Plus class="size-5"/>
                            </button>
                        </DialogTrigger>
                        <DialogContent>
                            <Form
                                v-bind="SystemController.store.form()"
                                v-slot="{ errors, processing }"
                                class="flex flex-col gap-6"
                            >

                            </Form>
                        </DialogContent>
                    </Dialog>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
