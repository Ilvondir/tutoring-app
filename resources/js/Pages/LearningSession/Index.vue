<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import {onMounted, reactive, ref, watch} from "vue";
import tableTranslations from "@/Utils/TableTranslations.js";
import rowsLimit from "@/Utils/RowsLimit.js";
import _ from "lodash";
import JetInput from "@/Components/Input.vue";
import {Link, router, usePage} from "@inertiajs/vue3";
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
const page = usePage();
const toast = useToast();

const table = reactive({
    isLoading: false,
    rows: [],
    totalRecordCount: 0,
    pageSize: localStorage.learning_sessions_limit,
    page: localStorage.learning_sessions_page,
    sortable: {
        order: localStorage.learning_sessions_order ?? 'id',
        sort: localStorage.learning_sessions_sort ?? 'desc',
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
            label: "Uczeń",
            width: '20%',
            field: 'user_id',
            sortable: true,
            display: (row) => {
                return row.user.name;
            }
        },
        {
            label: "Data dodania",
            width: '20%',
            field: 'created_at',
            sortable: true,
        },
        {
            label: 'Czas',
            width: '20%',
            field: 'seconds',
            sortable: true,
            display: (row) => {
                const elapsed = row.seconds;
                const hours = String(Math.floor(elapsed / 3600)).padStart(2, '0')
                const minutes = String(Math.floor((elapsed % 3600) / 60)).padStart(2, '0')
                const seconds = String(elapsed % 60).padStart(2, '0')
                return `${hours}:${minutes}:${seconds}`
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

const updateCheckedRows = (rowsKey) => {
    ids_array.value = rowsKey;
};

const doSearch = (offset, limit, order, sort, type = null) => {
    table.isLoading = true;

    let page = 1;
    if (offset && offset / limit >= 1) {
        page = offset / limit + 1;
    }

    let url =
        route('learning-sessions.index') +
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
        localStorage.learning_sessions_sort = sort;
        localStorage.learning_sessions_type = type;
        localStorage.learning_sessions_order = order;
        localStorage.learning_sessions_offset = offset;
        localStorage.learning_sessions_limit = limit;
        localStorage.learning_sessions_page = page;
    });
};

const deleteRow = (id) => {
    if (confirm('Czy na pewno chcesz usunąć ten element?')) {
        router.delete(route('learning-sessions.destroy', id), {
            preserveScroll: true,
            onSuccess: () => {
                doSearch(localStorage.learning_sessions_offset ?? 0, localStorage.learning_sessions_limit ?? 10, localStorage.learning_sessions_order ?? 'id', localStorage.learning_sessions_sort ?? 'desc', localStorage.learning_sessions_type ?? null);
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

const deleteRowsWithArray = async () => {
    if (ids_array.value.length >= 1 && confirm('Czy na pewno chcesz usunąć zaznaczone elementy?')) {
        router.get(route('learning-sessions.destroyArray', {ids: ids_array.value}), {}, {
            preserveScroll: true,
            onFinish: () => {
                doSearch(localStorage.learning_sessions_offset ?? 0, localStorage.learning_sessions_limit ?? 10, localStorage.learning_sessions_order ?? 'id', localStorage.learning_sessions_sort ?? 'desc', localStorage.learning_sessions_type ?? null);
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

watch(
    searchTerm,
    _.debounce(() => {
        if (searchTerm.value !== localStorage.learning_sessions_searchTerm) {
            doSearch(
                0,
                localStorage.learning_sessions_limit ?? 10,
                localStorage.learning_sessions_order ?? 'id',
                localStorage.learning_sessions_sort ?? 'desc',
                localStorage.learning_sessions_type ?? null
            );
            localStorage.learning_sessions_searchTerm = searchTerm.value;
        }
    }, 500)
);


onMounted(() => {
    if (localStorage.learning_sessions_searchTerm) {
        searchTerm.value = localStorage.learning_sessions_searchTerm;
    }

    url.value = window.location.host;
    protocol.value = window.location.protocol;

    doSearch(
        localStorage.learning_sessions_offset ?? 0,
        localStorage.learning_sessions_limit ?? 10,
        localStorage.learning_sessions_order ?? 'id',
        localStorage.learning_sessions_sort ?? 'desc',
        localStorage.learning_sessions_type ?? null
    );
});
</script>

<template>
    <AppLayout
        title="Sesje nauki"
    >

        <template #header>
            <h2 class="font-semibold text-xl text-black dark:text-gray-400 leading-tight">
                <span>Sesje nauki</span>
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
                                <div class>
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
