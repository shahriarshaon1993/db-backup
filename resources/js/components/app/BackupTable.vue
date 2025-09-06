<script setup lang="ts">
import DestroySelectedItemDialog from '@/components/app/DestroySelectedItemDialog.vue';
import TableDataNotFound from '@/components/app/TableDataNotFound.vue';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { debounce } from 'lodash';
// import { Search } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { computed, ref } from 'vue';

interface Props {
    backups: Array<any>;
}

const search = ref('');
const props = defineProps<Props>();
const selectedRows = ref<number[]>([]);

const debouncedSearch = debounce(() => {
    updateFilters();
}, 500);

const selectAll = computed({
    get: () => selectedRows.value.length === props.backups.length,
    set: (value) => toggleSelectAll(value),
});

const toggleSelectAll = (checked: boolean): void => {
    console.log(checked);
    if (checked) {
        selectedRows.value = props.backups.map((b: any) => b.id);
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
    console.log(search.value);
};

const deleteSelected = () => {};
</script>

<template>
    <div class="space-y-4">
        <div class="flex flex-1 items-center space-x-2">
            <div class="flex flex-1 items-center space-x-2">
                <Input v-model="search" type="text" @input="debouncedSearch" placeholder="Search..." class="w-[150px] lg:w-[250px]" />
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
                        <template v-if="backups.length > 0">
                            <TableRow v-for="backup in backups" :key="backup.file_name">
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
    </div>
</template>
