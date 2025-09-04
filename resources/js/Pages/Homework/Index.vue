<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import {onMounted, reactive, ref, watch} from "vue";
import tableTranslations from "@/Utils/TableTranslations.js";
import rowsLimit from "@/Utils/RowsLimit.js";
import _ from "lodash";
import JetInput from "@/Components/Input.vue";
import {Link, router} from "@inertiajs/vue3";
import 'boxicons';
import TableLite from "vue3-table-lite";
import JetButton from "@/Components/Button.vue";
import SearchIcon from "@/Components/SVGIcons/SearchIcon.vue";
import DeleteIcon from "@/Components/SVGIcons/DeleteIcon.vue";
import InfoIcon from "@/Components/SVGIcons/InfoIcon.vue";
import EditIcon from "@/Components/SVGIcons/EditIcon.vue";
import {useToast} from "vue-toastification";
import Toast from "@/Components/Toast.vue";

const searchTerm = ref('');
const ids_array = ref([]);
const url = ref('');
const protocol = ref('');
const toast = useToast()

const table = reactive({
    isLoading: false,
    rows: [],
    totalRecordCount: 0,
    pageSize: localStorage.homeworks_limit,
    page: localStorage.homeworks_page,
    sortable: {
        order: localStorage.homeworks_order ?? 'id',
        sort: localStorage.homeworks_sort ?? 'desc',
    },
    messages: tableTranslations,
    pageOptions: rowsLimit,
    columns: [
        {
            label: 'ID',
            field: 'id',
            width: '5%',
            sortable: true,
            isKey: true,
        },
        {
            label: "Tytuł",
            field: 'title',
            width: '3r0%',
            sortable: true,
        },
        {
            label: "Liczba zadań",
            width: '10%',
            sortable: false,
            display: row => {
                return row.exercises.length;
            }
        },
        {
            label: "Data dodania",
            width: '20%',
            sortable: true,
            display: row => {
                return row.created_at;
            }
        },
        {
            label: "Ukończone",
            field: 'is_completed',
            width: '15%',
            sortable: true,
            display: row => {
                if (row.is_completed) return '<span class="text-[#14b069]">Tak</span>';
                else return '<span style="color: red">Nie</span>';
            }
        },
        {
            label: 'Operacje',
            field: 'actions',
            width: '10%',
            sortable: false,
        },
    ]
});

const doSearch = (offset, limit, order, sort, type = null) => {
    table.isLoading = true;

    let page = 1;
    if (offset && offset / limit >= 1) {
        page = offset / limit + 1;
    }

    let url =
        route('homeworks.index') +
        '?offset=' + offset +
        '&limit=' + limit +
        '&order=' + order +
        '&sort=' + sort +
        '&page=' + page +
        '&type=' + type +
        '&filter=' + searchTerm.value;

    axios.get(url).then((response) => {
        table.rows = response.data.data;
        table.totalRecordCount = response.data.meta.total;
        table.sortable.order = order;
        table.sortable.sort = sort;
        table.page = response.data.meta.current_page;
        localStorage.homeworks_sort = sort;
        localStorage.homeworks_type = type;
        localStorage.homeworks_order = order;
        localStorage.homeworks_offset = offset;
        localStorage.homeworks_limit = limit;
        localStorage.homeworks_page = page;
    });
};


watch(
    searchTerm,
    _.debounce(() => {
        if (searchTerm.value !== localStorage.homeworks_searchTerm) {
            doSearch(
                0,
                localStorage.homeworks_limit ?? 10,
                localStorage.homeworks_order ?? 'id',
                localStorage.homeworks_sort ?? 'desc',
                localStorage.homeworks_type ?? null
            );
            localStorage.homeworks_searchTerm = searchTerm.value;
        }
    }, 500)
);

const deleteRow = (id) => {
    if (confirm('Czy na pewno chcesz usunąć ten element?')) {
        router.delete(route('homeworks.destroy', id), {
            preserveScroll: true,
            onSuccess: () => {
                doSearch(localStorage.homeworks_offset ?? 0, localStorage.homeworks_limit ?? 10, localStorage.homeworks_order ?? 'id', localStorage.homeworks_sort ?? 'desc', localStorage.homeworks_type ?? null);
                toast.success({
                    component: Toast,
                    props: {
                        operation: 'Operacja zakończona powodzeniem!'
                    }
                });
            },
            onError: () => {
                toast.error({
                    component: Toast,
                    props: {
                        operation: 'Podczas wykonywania operacji wystąpił błąd.'
                    }
                });
            },
        });
    }
};

const updateCheckedRows = (rowsKey) => {
    ids_array.value = rowsKey;
};

const deleteRowsWithArray = async () => {
    if (ids_array.value.length >= 1 && confirm('Czy na pewno chcesz usunąć zaznaczone elementy?')) {
        router.get(route('homeworks.destroyArray', {ids: ids_array.value}), {}, {
            preserveScroll: true,
            onFinish: () => {
                doSearch(localStorage.homeworks_offset ?? 0, localStorage.homeworks_limit ?? 10, localStorage.homeworks_order ?? 'id', localStorage.homeworks_sort ?? 'desc', localStorage.homeworks_type ?? null);
                toast.success({
                    component: Toast,
                    props: {
                        operation: 'Operacja zakończona powodzeniem!'
                    }
                });
            },
            onError: () => {
                toast.error({
                    component: Toast,
                    props: {
                        operation: 'Podczas wykonywania operacji wystąpił błąd.'
                    }
                });
            },
        });
    } else {
        alert('Operacja zakończona niepowodzeniem');
    }

};

onMounted(() => {
    if (localStorage.homeworks_searchTerm) {
        searchTerm.value = localStorage.homeworks_searchTerm;
    }

    url.value = window.location.host;
    protocol.value = window.location.protocol;

    doSearch(
        localStorage.homeworks_offset ?? 0,
        localStorage.homeworks_limit ?? 10,
        localStorage.homeworks_order ?? 'id',
        localStorage.homeworks_sort ?? 'desc',
        localStorage.homeworks_type ?? null
    );
});
</script>

<template>
    <AppLayout
        title="Prace domowe"
    >

        <template #header>
            <h2 class="font-semibold text-xl text-black dark:text-gray-400 leading-tight">
                <span>Prace domowe</span>
            </h2>
        </template>


        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-t-lg py-4 px-4">
                    <div class="mt-4">
                        <div class="mb-3 sm:flex sm:justify-between">
                            <div class="flex">
                                <div class="input-group flex">
                                    <button disabled
                                            class="bg-[#4F46E5] text-white font-bold py-2 px-4 border-b-4 border-[#2F26C5] rounded-l h-10 cursor-default">
                                        <SearchIcon/>
                                    </button>
                                    <JetInput
                                        id="searchTerm"
                                        v-model="searchTerm"
                                        type="text"
                                        autocomplete="off"
                                        class="input-group w-full input input-bordered input-primary mb-2 h-10 rounded-r"
                                    />
                                </div>
                            </div>
                            <div class="flex flex-row">
                                <Link :href="route('homeworks.create')" disabled>
                                    <div
                                        class="flex items-center bg-[#4F46E5] hover:bg-[#2F26C5] border-[#2F26C5] hover:border-[#4F46E5] text-white font-medium py-2 px-4 border-b-4 rounded cursor-pointer h-10">
                                        <p class="mr-2">Dodaj</p>
                                        <box-icon name="plus-circle" color="white"></box-icon>
                                    </div>
                                </Link>

                                <JetButton
                                    @click="deleteRowsWithArray"
                                    type="submit"
                                    class="flex items-center bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 border-b-4 border-red-900 hover:border-red-600 rounded cursor-pointer mx-2 h-10">
                                    <p class="mr-2">Usuń zaznaczone</p>
                                    <box-icon name='trash' color="white"></box-icon>
                                </JetButton>
                            </div>
                        </div>
                    </div>

                    <hr class="my-3 border-gray-200 dark:border-gray-600">

                    <div class="flex justify-between w-full">
                        <div class="w-full hidden">
                            filtry
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-white dark:bg-gray-800 sm:rounded-b-lg">

                    <table-lite
                        :is-slot-mode="true"
                        :is-loading="table.isLoading"
                        :columns="table.columns"
                        :rows="table.rows"
                        :total="table.totalRecordCount"
                        :sortable="table.sortable"
                        :page="parseInt(table.page)"
                        :pageSize="parseInt(table.pageSize)"
                        :messages="table.messages"
                        :page-options="table.pageOptions"
                        :has-checkbox="true"
                        @do-search="doSearch"
                        @return-checked-rows="updateCheckedRows"
                        @is-finished="table.isLoading = false"
                    >

                        <template v-slot:actions="data">
                            <div class="justify-center flex">

                                <div class="ml-2">
                                    <Link
                                        class="flex items-center focus:text-indigo-500 link justify-center"
                                        :href="route('homeworks.edit', data.value.id)"
                                    >
                                        <EditIcon/>
                                    </Link>
                                </div>

                                <div class="ml-2">
                                    <button
                                        @click="deleteRow(data.value.id)"
                                        type="submit"
                                        class="cursor-pointer"
                                    >
                                        <DeleteIcon/>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </table-lite>

                </div>
            </div>
        </div>


    </AppLayout>
</template>
