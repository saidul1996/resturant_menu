<script setup>
import BreezeAuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/inertia-vue3";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    categories: {
        type: Array,
        default: () => [],
    }
});

const form = useForm({
    name: '',
    parent_id: '',
    discount: 0
});

const submit = () => {
    form.post(route("category.store"));
};

const recursiveCategories = (categories, level = 1) => {
  let result = [];
  for (const category of categories) {
    result.push({
      ...category,
      name: getIndentation(level) + category.name
    });
    if (category.childrens && Array.isArray(category.childrens)) {
      result = result.concat(recursiveCategories(category.childrens, level + 1));
    } else if (category.childrens) {
      result.push(...recursiveCategories([category.childrens], level + 1));
    }
  }
  return result;
};

const getIndentation = (level) => {
  return Array(level).fill('    ').join('');
};
</script>

<template>
    <Head title="Category Create" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Category Create
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submit">
                            <div class="mb-6">
                                <label
                                    for="ParentId"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                                >Select Parent</label>
                                <select 
                                    v-model="form.parent_id" 
                                    id="ParentId" 
                                    name="parent_id" 
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                >
                                    <option value="" disabled>Select a category</option>
                                    <option v-for="category in recursiveCategories(categories)" :key="category.id" :value="category.id">
                                        <span v-if="category.level==2">&nbsp;-</span>
                                        <span v-if="category.level==3">&nbsp;--</span>
                                        <span v-if="category.level==4">&nbsp;---</span>
                                        {{ category.name }}
                                    </option>
                                </select>
                                <div
                                    v-if="form.errors.parent_id"
                                    class="text-sm text-red-600"
                                >
                                    {{ form.errors.parent_id }}
                                </div>
                            </div>
                            <div class="mb-6">
                                <label
                                    for="Name"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                                >Name</label>
                                <input
                                    type="text"
                                    v-model="form.name"
                                    name="name"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    placeholder=""
                                />
                                <div
                                    v-if="form.errors.name"
                                    class="text-sm text-red-600"
                                >
                                    {{ form.errors.name }}
                                </div>
                            </div>
                            <div class="mb-6">
                                <label
                                    for="discount"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                                >Discount</label>
                                <input
                                    type="number"
                                    v-model="form.discount"
                                    name="discount"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    placeholder=""
                                />
                                <div
                                    v-if="form.errors.discount"
                                    class="text-sm text-red-600"
                                >
                                    {{ form.errors.discount }}
                                </div>
                            </div>
                            <button
                                type="submit"
                                class="text-white bg-blue-700  focus:outline-none  font-medium rounded-lg text-sm px-5 py-2.5 "
                                :disabled="form.processing"
                                :class="{ 'opacity-25': form.processing }"
                            >
                                Submit
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
