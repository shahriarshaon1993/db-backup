<script setup lang="ts">
import DestroySelectedItemDialog from '@/components/app/DestroySelectedItemDialog.vue';
import TableDataNotFound from '@/components/app/TableDataNotFound.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Pagination, PaginationContent, PaginationEllipsis, PaginationItem, PaginationNext, PaginationPrevious } from '@/components/ui/pagination';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { show } from '@/routes/systems';
import { router } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import { computed, reactive, ref, watch } from 'vue';

interface Props {
    backups: Object;
    filters: Object;
    systemSlug: String;
}

const props = defineProps<Props>();
const selectedRows = ref<number[]>([]);

const appFilters = reactive({
    search: props.filters?.search ?? '',
    per_page: props.filters?.per_page ?? '',
});

const refresh = debounce(() => {
    updateFilters();
}, 500);

watch(() => [appFilters.search], refresh);

const selectAll = computed({
    get: () => selectedRows.value.length === props.backups.data.length,
    set: (value) => toggleSelectAll(value),
});

const toggleSelectAll = (checked: boolean): void => {
    if (checked) {
        selectedRows.value = props.backups.data.map((b: any) => b.id);
    } else {
        selectedRows.value = [];
    }
};

const toggleRow = (id: number) => {
    const index = selectedRows.value.indexOf(id);

    if (index === -1) {
        selectedRows.value.push(id);
    } else {
        selectedRows.value.splice(index, 1);
    }
};

const updateFilters = () => {
    const params = {};

    if (appFilters.per_page) {
        params.per_page = appFilters.per_page;
    }

    if (appFilters.search) {
        params.search = appFilters.search;
    }

    router.get(show({ system: props.systemSlug }), params, {
        preserveState: true,
        preserveScroll: true,
    });
};

const updatePerPage = (value: string): void => {
    appFilters.per_page = parseInt(value);
    updateFilters();
};

const changePage = (page: number) => {
    router.get(
        show({ system: props.systemSlug }),
        { page: page },
        {
            preserveState: true,
            preserveScroll: true,
        },
    );
};

const deleteSelected = () => {};
</script>

<template>
    <div class="space-y-4">
        <div class="flex flex-1 items-center space-x-2">
            <div class="flex flex-1 items-center space-x-2">
                <Input v-model="appFilters.search" type="text" placeholder="Search..." class="w-[150px] lg:w-[250px]" />
                <Button variant="outline">Status</Button>
                <Button variant="outline">Priority</Button>
            </div>

            <DestroySelectedItemDialog :length="selectedRows.length" @on-destroy="deleteSelected" />
        </div>

        <div class="rounded-md border">
            <div class="relative w-full overflow-auto">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-6">
                                <Checkbox :model-value="selectAll" @update:model-value="toggleSelectAll((selectAll = !selectAll))" />
                            </TableHead>
                            <TableHead> File name </TableHead>
                            <TableHead> Storage type </TableHead>
                            <TableHead> File size </TableHead>
                            <TableHead> Created at </TableHead>
                            <TableHead> Actions </TableHead>
                        </TableRow>
                    </TableHeader>

                    <TableBody>
                        <template v-if="backups.data.length > 0">
                            <TableRow v-for="backup in backups.data" :key="backup.id">
                                <TableCell>
                                    <Checkbox :model-value="selectedRows.includes(backup.id)" @update:model-value="toggleRow(backup.id)" />
                                </TableCell>
                                <TableCell>{{ backup.file_name }}</TableCell>
                                <TableCell>{{ backup.storage_type }}</TableCell>
                                <TableCell>{{ backup.file_size }}</TableCell>
                                <TableCell>{{ backup.created_at }}</TableCell>
                                <TableCell> Download </TableCell>
                            </TableRow>
                        </template>

                        <TableDataNotFound v-else :length="6" />
                    </TableBody>
                </Table>
            </div>
        </div>

        <div class="flex items-center justify-between px-2">
            <div class="flex-1 text-sm text-muted-foreground">{{ backups.meta.to }} of {{ backups.meta.total }} row(s) selected.</div>
            <div class="flex items-center space-x-6 lg:space-x-8">
                <div class="flex items-center space-x-2">
                    <p class="text-sm font-medium">Rows per page</p>
                    <Select v-model="appFilters.per_page" @update:modelValue="updatePerPage">
                        <SelectTrigger class="w-[80px]">
                            <SelectValue :placeholder="appFilters.per_page" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="15">15</SelectItem>
                            <SelectItem value="25">25</SelectItem>
                            <SelectItem value="50">50</SelectItem>
                            <SelectItem value="100">100</SelectItem>
                        </SelectContent>
                    </Select>
                </div>
                <div class="flex w-[100px] items-center justify-center text-sm font-medium">
                    Page {{ backups.meta.current_page }} of {{ backups.meta.last_page }}
                </div>
                <div class="flex items-center space-x-2">
                    <Pagination
                        v-slot="{ page }"
                        :items-per-page="backups.meta.per_page"
                        :total="backups.meta.total"
                        :default-page="backups.meta.current_page"
                    >
                        <PaginationContent v-slot="{ items }">
                            <PaginationPrevious :disabled="!backups.links?.prev" @click="changePage(backups.meta.current_page - 1)" />

                            <template v-for="(item, index) in items" :key="`page-${index}`">
                                <PaginationItem
                                    v-if="item.type === 'page'"
                                    :value="item.value"
                                    :is-active="item.value === page"
                                    @click="changePage(item.value)"
                                >
                                    {{ item.value }}
                                </PaginationItem>

                                <PaginationEllipsis v-else />
                            </template>

                            <PaginationNext :disabled="!backups.links?.next" @click="changePage(backups.meta.current_page + 1)" />
                        </PaginationContent>
                    </Pagination>
                </div>
            </div>
        </div>
    </div>
</template>
