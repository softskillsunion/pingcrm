<template>
  <div>
    <Head title="成員" />
    <h1 class="mb-8 text-3xl font-bold">成員</h1>
    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block text-gray-700">角色:</label>
        <select v-model="form.role" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="user">成員</option>
          <option value="owner">管理者</option>
        </select>
        <label class="block mt-4 text-gray-700">已刪除:</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="with">包含刪除</option>
          <option value="only">只有刪除</option>
        </select>
      </search-filter>
      <Link class="btn-indigo" href="/users/create">
        <span class="hidden md:inline">新建成員</span>
      </Link>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">姓名</th>
          <th class="pb-4 pt-6 px-6">行動電話</th>
          <th class="pb-4 pt-6 px-6">電子郵件</th>
          <th class="pb-4 pt-6 px-6">佣金成數(%)</th>
          <th class="pb-4 pt-6 px-6">啟用</th>
          <th class="pb-4 pt-6 px-6" colspan="2">角色</th>
        </tr>
        <tr v-for="user in users" :key="user.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
            <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/users/${user.id}/edit`">
              <img v-if="user.photo" class="block -my-2 mr-2 w-5 h-5 rounded-full" :src="user.photo" />
              {{ user.name }}
              <icon v-if="user.deleted_at" name="trash" class="shrink-0 ml-2 w-3 h-3 fill-gray-400" />
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/users/${user.id}/edit`" tabindex="-1">
              {{ user.mobile }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/users/${user.id}/edit`" tabindex="-1">
              {{ user.email }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/users/${user.id}/edit`" tabindex="-1">
              {{ user.commission_rate/1 }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/users/${user.id}/edit`" tabindex="-1">
              {{ user.is_active ? '是' : '否' }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/users/${user.id}/edit`" tabindex="-1">
              {{ user.owner ? '管理者' : '成員' }}
            </Link>
          </td>
          <td class="w-px border-t">
            <Link class="flex items-center px-4" :href="`/users/${user.id}/edit`" tabindex="-1">
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </Link>
          </td>
        </tr>
        <tr v-if="users.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">無成員資料</td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'
import pickBy from 'lodash/pickBy'
import Layout from '@/Shared/Layout.vue'
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'
import SearchFilter from '@/Shared/SearchFilter.vue'

export default {
  components: {
    Head,
    Icon,
    Link,
    SearchFilter,
  },
  layout: Layout,
  props: {
    filters: Object,
    users: Array,
  },
  data() {
    return {
      form: {
        search: this.filters.search,
        role: this.filters.role,
        trashed: this.filters.trashed,
      },
    }
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get('/users', pickBy(this.form), { preserveState: true })
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
