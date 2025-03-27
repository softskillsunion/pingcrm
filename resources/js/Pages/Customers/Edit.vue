<template>
  <div>
    <Head :title="`${form.first_name}`" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/customers">客戶</Link>
      <span class="text-indigo-400 font-medium">/</span>
      {{ form.first_name }}
    </h1>
    <trashed-message v-if="customer.deleted_at" class="mb-6" @restore="restore"> 此客戶已刪除 </trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.first_name" :error="form.errors.first_name" class="pb-8 pr-6 w-full lg:w-1/2" label="姓名" />
          <select-input v-model="form.user_id" :error="form.errors.user_id" class="pb-8 pr-6 w-full lg:w-1/2" label="服務人員">
            <option :value="null" />
            <option v-for="user in users" :key="user.id" :value="user.id">{{ user.first_name }}</option>
          </select-input>
          <text-input v-model="form.phone" :error="form.errors.phone" class="pb-8 pr-6 w-full lg:w-1/2" label="市內電話" />
          <text-input v-model="form.mobile" :error="form.errors.mobile" class="pb-8 pr-6 w-full lg:w-1/2" label="行動電話" />
          <text-input v-model="form.email" :error="form.errors.email" class="pb-8 pr-6 w-full lg:w-1/2" label="電子郵件" />          
          <text-input v-model="form.mailing_address" :error="form.errors.mailing_address" class="pb-8 pr-6 w-full lg:w-1/2" label="通訊地址" />
          <text-input v-model="form.registered_address" :error="form.errors.registered_address" class="pb-8 pr-6 w-full lg:w-1/2" label="戶籍地址" />          
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button v-if="!customer.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">刪除客戶</button>
          <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">更新</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import TextInput from '@/Shared/TextInput.vue'
import SelectInput from '@/Shared/SelectInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'
import TrashedMessage from '@/Shared/TrashedMessage.vue'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
  },
  layout: Layout,
  props: {
    customer: Object,
    users: Array,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        first_name: this.customer.first_name,
        user_id: this.customer.user_id,
        email: this.customer.email,
        phone: this.customer.phone,
        mobile: this.customer.mobile,
        mailing_address: this.customer.mailing_address,
        registered_address: this.customer.registered_address,
      }),
    }
  },
  methods: {
    update() {
      this.form.put(`/customers/${this.customer.id}`)
    },
    destroy() {
      if (confirm('確定刪除客戶資料?')) {
        this.$inertia.delete(`/customers/${this.customer.id}`)
      }
    },
    restore() {
      if (confirm('確定還原客戶資料?')) {
        this.$inertia.put(`/customers/${this.customer.id}/restore`)
      }
    },
  },
}
</script>
