<template>
  <div>
    <Head title="新建成員" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/users">成員</Link>
      <span class="text-indigo-400 font-medium"> / </span>新建
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.first_name" :error="form.errors.first_name" class="pb-8 pr-6 w-full lg:w-1/2" label="姓名" />
          <!-- <text-input v-model="form.last_name" :error="form.errors.last_name" class="pb-8 pr-6 w-full lg:w-1/2" label="Last name" /> -->
          <text-input v-model="form.commission_rate" :error="form.errors.commission_rate" class="pb-8 pr-6 w-full lg:w-1/2" label="佣金成數(%)" />
          <text-input v-model="form.phone" :error="form.errors.phone" class="pb-8 pr-6 w-full lg:w-1/2" label="市內電話" />
          <text-input v-model="form.mobile" :error="form.errors.mobile" class="pb-8 pr-6 w-full lg:w-1/2" label="行動電話" />
          <text-input v-model="form.email" :error="form.errors.email" class="pb-8 pr-6 w-full lg:w-1/2" label="電子郵件" />
          <text-input v-model="form.password" :error="form.errors.password" class="pb-8 pr-6 w-full lg:w-1/2" type="password" autocomplete="new-password" label="登入密碼" />
          <text-input v-model="form.mailing_address" :error="form.errors.mailing_address" class="pb-8 pr-6 w-full lg:w-1/2" label="通訊地址" />
          <text-input v-model="form.registered_address" :error="form.errors.registered_address" class="pb-8 pr-6 w-full lg:w-1/2" label="戶籍地址" />
          <select-input v-model="form.owner" :error="form.errors.owner" class="pb-8 pr-6 w-full lg:w-1/2" label="管理者">
            <option :value="true">是</option>
            <option :value="false">否</option>
          </select-input>
          <select-input v-model="form.is_active" :error="form.errors.is_active" class="pb-8 pr-6 w-full lg:w-1/2" label="啟用">
            <option :value="true">是</option>
            <option :value="false">否</option>
          </select-input>
          <file-input v-model="form.photo" :error="form.errors.photo" class="pb-8 pr-6 w-full lg:w-1/2" type="file" accept="image/*" label="照片" />
        </div>
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <loading-button :loading="form.processing" class="btn-indigo" type="submit">建立</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import FileInput from '@/Shared/FileInput.vue'
import TextInput from '@/Shared/TextInput.vue'
import SelectInput from '@/Shared/SelectInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'

export default {
  components: {
    FileInput,
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
  },
  layout: Layout,
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        first_name: '',
        // last_name: '',
        commission_rate: 4,
        phone: '',
        mobile: '',
        email: '',
        password: '',
        mailing_address: '',
        registered_address: '',
        owner: false,
        is_active: false,
        photo: null,
      }),
    }
  },
  methods: {
    store() {
      this.form.post('/users')
    },
  },
}
</script>
