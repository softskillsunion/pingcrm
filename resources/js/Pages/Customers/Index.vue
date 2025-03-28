<template>
  <div>
    <Head title="客戶" />
    <h1 class="mb-8 text-3xl font-bold">客戶</h1>
    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block text-gray-700">已刪除:</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="with">包含刪除</option>
          <option value="only">只有刪除</option>
        </select>
      </search-filter>
      <Link class="btn-indigo" href="/customers/create">
        <span class="hidden md:inline">新建客戶</span>
      </Link>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">姓名</th>
          <th class="pb-4 pt-6 px-6">行動電話</th>
          <th class="pb-4 pt-6 px-6">電子郵件</th>
          <th class="pb-4 pt-6 px-6">通訊地址</th>
          <th class="pb-4 pt-6 px-6" colspan="2">服務人員</th>
        </tr>
        <tr v-for="customer in customers.data" :key="customer.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
            <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/customers/${customer.id}/edit`">
              {{ customer.name }}
              <icon v-if="customer.deleted_at" name="trash" class="shrink-0 ml-2 w-3 h-3 fill-gray-400" />
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/customers/${customer.id}/edit`" tabindex="-1">
              {{ customer.mobile }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/customers/${customer.id}/edit`" tabindex="-1">
              {{ customer.email }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/customers/${customer.id}/edit`" tabindex="-1">
              {{ customer.mailing_address }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/customers/${customer.id}/edit`" tabindex="-1">
              <div v-if="customer.user">
                {{ customer.user.first_name }}
              </div>
            </Link>
          </td>
          <td class="w-px border-t">
            <Link class="flex items-center px-4" :href="`/customers/${customer.id}/edit`" tabindex="-1">
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </Link>
          </td>
        </tr>
        <tr v-if="customers.data.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">無客戶資料</td>
        </tr>
      </table>
    </div>
    <pagination class="mt-6" :links="customers.links" />
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'
import pickBy from 'lodash/pickBy'
import Layout from '@/Shared/Layout.vue'
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'
import Pagination from '@/Shared/Pagination.vue'
import SearchFilter from '@/Shared/SearchFilter.vue'

export default {
  components: {
    Head,
    Icon,
    Link,
    Pagination,
    SearchFilter,
  },
  layout: Layout,
  props: {
    filters: Object,
    customers: Object,
  },
  data() {
    return {
      form: {
        search: this.filters.search,
        trashed: this.filters.trashed,
      },
    }
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get('/customers', pickBy(this.form), { preserveState: true })
      }, 150),
    },
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null)
    },
  },
}
</script>
