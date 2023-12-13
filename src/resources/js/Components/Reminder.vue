<template>
    <div class="space-y-4 py-4">
        <div class="p-4 text-lg text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 space-y-2">
            <h1>Hi, {{ user.name }}</h1>
            <p>I hope you have a great day</p>
        </div>

        <div class="flex justify-between">
            <div>
                <select v-model="limit"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option v-for="option in options" :value="option.value">
                        {{ option.text }}
                    </option>
                </select>
            </div>
            <div>
                <button @click="createReminder()"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                    Create Reminder
                </button>
            </div>
        </div>

        <div v-if="isShowCreateForm">
            <div class="space-y-4 border rounded-lg p-4">
                <div>
                    <h3 class="font-bold">Create Reminder</h3>
                </div>
                <div>
                    <label>
                        <span>Title:</span>
                    </label>
                    <br>
                    <input
                        type="text"
                        v-model="reminder.title"
                        placeholder="title ..."
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    />
                </div>
                <div>
                    <label>
                        <span>Description:</span>
                    </label>
                    <br>
                    <textarea
                        v-model="reminder.description"
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="description ..."></textarea>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label>
                            <span>Remind At:</span>
                        </label>
                        <br>
                        <input
                            type="datetime-local"
                            v-model="reminder.remind_at"
                            placeholder="remind at ..."
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        />
                    </div>
                    <div>
                        <label>
                            <span>Event At:</span>
                        </label>
                        <br>
                        <input
                            type="datetime-local"
                            v-model="reminder.event_at"
                            placeholder="event at ..."
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        />
                    </div>
                </div>
                <div class="text-right">
                    <button @click="cancelCreateForm"
                            class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        Cancel
                    </button>
                    <button
                        @click="storeReminder"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        Simpan
                    </button>
                </div>
            </div>
        </div>

        <div class="space-y-2">
            <template v-if="reminders.length > 0">
                <template v-for="item in reminders">
                    <div class="p-4 flex justify-between border rounded-lg">
                        <div class="space-y-4">
                            <h1><span>🏃</span> {{ item.title }}</h1>
                            <p><span>🕙</span> Event at: {{ toDatetime(item.event_at) }}</p>
                        </div>
                        <div class="h-full my-auto">
                            <div class="inline-flex rounded-md shadow-sm m-auto w-full" role="group">
                                <button type="submit"
                                        @click="showReminderSection(item)"
                                        class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-s-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white">
                                    View
                                </button>
                                <button type="submit"
                                        @click="editReminder(item)"
                                        class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white">
                                    Edit
                                </button>
                                <button type="submit"
                                        @click="showConfirmDeleteReminder(item.id)"
                                        class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-e-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Show Section -->
                    <div v-if="item.id === showReminderData.id">
                        <div class="space-y-4 border rounded-lg p-4" :class="((new Date()).getTime() / 1000) > showReminderData.event_at ? 'bg-red-50 dark:bg-gray-800' : 'bg-blue-50 dark:bg-gray-800' ">
                            <div v-if="((new Date()).getTime() / 1000) > showReminderData.event_at">
                                <div class="p-4 text-lg text-yellow-800 text-center">
                                    <p>The event time has passed</p>
                                </div>
                            </div>
                            <div>
                                <h3 class="font-bold">Reminder</h3>
                            </div>
                            <div>
                                <label>
                                    <span>Title:</span>
                                </label>
                                <br/>
                                <p>{{ showReminderData.title }}</p>
                            </div>
                            <div>
                                <label>
                                    <span>Description:</span>
                                </label>
                                <br/>
                                <p>{{ showReminderData.description }}</p>
                            </div>
                            <div>
                                <label>
                                    <span>Remind At:</span>
                                </label>
                                <br/>
                                <p>{{ toDatetime(showReminderData.remind_at) }}</p>
                            </div>
                            <div>
                                <label>
                                    <span>Event At:</span>
                                </label>
                                <br/>
                                <p>{{ toDatetime(showReminderData.event_at) }}</p>
                            </div>
                            <div class="text-right">
                                <button
                                    @click="closeShowReminder"
                                    class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Edit Form Section -->
                    <div v-if="isShowEditForm && editReminderForm.id === item.id">
                        <div class="space-y-4 border rounded-lg p-4">
                            <div>
                                <h3 class="font-bold">Edit Reminder</h3>
                            </div>
                            <div>
                                <label>
                                    <span>Title:</span>
                                </label>
                                <br/>
                                <input
                                    type="text"
                                    v-model="editReminderForm.title"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="title..."
                                />
                            </div>
                            <div>
                                <label>
                                    <span>Description:</span>
                                </label>
                                <br/>
                                <textarea
                                    v-model="editReminderForm.description"
                                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="description..."></textarea>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label>
                                        <span>Remind At:</span>
                                    </label>
                                    <br/>
                                    <input type="datetime-local"
                                           v-model="editReminderForm.remind_at"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    />
                                </div>
                                <div>
                                    <label>
                                        <span>Event At:</span>
                                    </label>
                                    <br/>
                                    <input
                                        type="datetime-local"
                                        v-model="editReminderForm.event_at"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    />
                                </div>
                            </div>
                            <div class="text-right">
                                <button
                                    @click="cancelEditForm"
                                    class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                    Cancel
                                </button>
                                <button
                                    @click="updateReminder"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                    Update
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </template>
            <template v-else>
                <div class="py-8">
                    <p>No reminders found. You can create one by clicking the Create Reminder button</p>
                </div>
            </template>
        </div>
        <div>
            <button @click="logout()"
                    class="w-full text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                Logout
            </button>
        </div>
        <hr>
        <!--  Ini untuk delete-->
        <div
            :class="isShowDeleteAlert ? '' : 'hidden'"
            class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div
                        class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                         stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                    <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Delete
                                        Reminder</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">Are you sure you want to delete Reminder? All
                                            of your data will be permanently removed. This action cannot be undone.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button @click="deleteReminder(deleteReminderId)" type="submit"
                                    class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">
                                Delete
                            </button>
                            <button @click="isShowDeleteAlert = !isShowDeleteAlert" type="submit"
                                    class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {useStore} from "vuex";
import {computed, ref, watch} from "vue";
import {ofetch} from 'ofetch';

const store = useStore();
const user = computed(() => localStorage.getItem('user') ? JSON.parse(localStorage.getItem('user')) : null);
const reminders = ref([]);
const limit = ref(10)
const options = ref([
    {text: 'Limit (default: 10)', value: 10, disabled: true, selected: true},
    {text: '5 Data', value: 5},
    {text: '10 Data', value: 10},
    {text: '20 Data', value: 20},
    {text: '50 Data', value: 50},
]);

const isShowCreateForm = ref(false);
const isShowEditForm = ref(false);
const isShowDeleteAlert = ref(false);

const reminder = ref({
    id: '',
    title: '',
    description: '',
    remind_at: '',
    event_at: '',
})
const editReminderForm = ref({
    id: '',
    title: '',
    description: '',
    remind_at: '',
    event_at: ''
});
const showReminderData = ref({
    id: '',
    title: '',
    description: '',
    remind_at: '',
    event_at: '',
});
const deleteReminderId = ref('');

const logout = () => {
    localStorage.removeItem('access_token');
    localStorage.removeItem('refresh_token');
    store.commit('setLoginStatus', false);
};

function toTimestamp(datetime) {
    return (new Date(datetime)).getTime();
}

function toDatetime(datetime) {
    return (new Date(datetime * 1000)).toLocaleString();
}

function formattedDateTime(unixTimestamp) {
    const date = new Date(unixTimestamp * 1000);
    const year = date.getFullYear();
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const day = date.getDate().toString().padStart(2, '0');
    const hours = date.getHours().toString().padStart(2, '0');
    const minutes = date.getMinutes().toString().padStart(2, '0');

    return `${year}-${month}-${day}T${hours}:${minutes}`;
}

const getReminders = async () => {
    try {
        const response = await ofetch('/api/reminders', {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('access_token')}`
            },
            query: {
                limit: limit.value
            }
        });

        if (response.ok) {
            reminders.value = response.data.reminders;
        }
    } catch (error) {
        console.error('Error:', error);
    }
};

function createReminder() {
    isShowCreateForm.value = true;
}

function cancelCreateForm() {
    isShowCreateForm.value = false;
    reminder.value = {
        id: '',
        title: '',
        description: '',
        remind_at: '',
        event_at: '',
    }
}

const storeReminder = async () => {
    try {
        const response = await ofetch('/api/reminders', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('access_token')}`
            },
            body: {
                title: reminder.value.title,
                description: reminder.value.description,
                remind_at: toTimestamp(reminder.value.remind_at),
                event_at: toTimestamp(reminder.value.event_at)
            },
            async onResponse({ request, response, options }) {
                if (response._data.ok === false) {
                    alert(response._data.msg);
                }
            },
        })

        if (response.ok) {
            cancelCreateForm();
            alert('Successfully created reminder');
        } else {
            alert(response.msg);
        }

        await getReminders();
    } catch (error) {
        console.error('Errornya:', error);
    }
}

const editReminder = (item) => {
    isShowEditForm.value = true;
    showReminderSection.value = false;
    showReminderData.value = {
        id: '',
    };
    editReminderForm.value = {
        id: item.id,
        title: item.title,
        description: item.description,
        remind_at: formattedDateTime(item.remind_at),
        event_at: formattedDateTime(item.event_at),
    };
}

const cancelEditForm = () => {
    isShowEditForm.value = false;
    editReminderForm.value = {
        id: '',
        title: '',
        description: '',
        remind_at: '',
        event_at: '',
    };
};

const updateReminder = async () => {
    try {
        const response = await ofetch(`/api/reminders/${editReminderForm.value.id}`, {
            method: 'PUT',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('access_token')}`
            },
            body: {
                title: editReminderForm.value.title,
                description: editReminderForm.value.description,
                remind_at: toTimestamp(editReminderForm.value.remind_at),
                event_at: toTimestamp(editReminderForm.value.event_at),
            },
            async onResponse({ request, response, options }) {
                if (response._data.ok === false) {
                    alert(response._data.msg);
                }
            },
        });

        if (response.ok) {
            cancelEditForm();
            await getReminders();
            alert('Successfully updated reminder');
        }
    } catch (error) {
        console.log('Error:', error);
    }
};

const showConfirmDeleteReminder = async (id) => {
    deleteReminderId.value = id;
    isShowDeleteAlert.value = true;
}

const deleteReminder = async (id) => {
    try {
        const response = await ofetch(`/api/reminders/${id}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('access_token')}`
            },
            async onResponse({ request, response, options }) {
                if (response._data.ok === false) {
                    alert(response._data.msg);
                }
            },
        })

        if (response.ok) {
            console.log(response);
            await getReminders();
            deleteReminderId.value = null;
            alert('Successfully deleted reminder');
        }
    } catch (error) {
        console.error('Error:', error);
    }

    isShowDeleteAlert.value = false;
}
    // requestNewAccessToken();

const showReminderSection  = (item) => {
    showReminderData.value = item;
    isShowEditForm.value = false;
}

const closeShowReminder = () => {
    showReminderSection.value = false;
    showReminderData.value = {
        id: '',
    }
}

// get reminder for first time
getReminders();

// watch limit
watch(limit, () => {
    getReminders();
})
</script>
