<template>
  <div>
    <!-- <Head :title="`${form.first_name} ${form.last_name}`" /> -->
    <Head :title="`${form.first_name}`" />
    <div class="flex justify-start mb-8 max-w-3xl">
      <h1 class="text-3xl font-bold">
        <Link class="text-indigo-400 hover:text-indigo-600" href="/users">成員</Link>
        <span class="text-indigo-400 font-medium">/</span>
        <!-- {{ form.first_name }} {{ form.last_name }} -->
        {{ form.first_name }}
      </h1>
      <img v-if="user.photo" class="block ml-4 w-8 h-8 rounded-full" :src="user.photo" />
    </div>
    <trashed-message v-if="user.deleted_at" class="mb-6" @restore="restore"> 此成員已刪除 </trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.first_name" :error="form.errors.first_name" class="pb-8 pr-6 w-full lg:w-1/2" label="姓名" />
          <!-- <text-input v-model="form.last_name" :error="form.errors.last_name" class="pb-8 pr-6 w-full lg:w-1/2" label="Last name" /> -->
          <text-input :disabled="!auth.user.owner" v-model="form.commission_rate" :error="form.errors.commission_rate" class="pb-8 pr-6 w-full lg:w-1/2" label="分潤成數(%)" />
          <text-input v-model="form.phone" :error="form.errors.phone" class="pb-8 pr-6 w-full lg:w-1/2" label="市內電話" />
          <text-input v-model="form.mobile" :error="form.errors.mobile" class="pb-8 pr-6 w-full lg:w-1/2" label="行動電話" />
          <text-input v-model="form.email" :error="form.errors.email" class="pb-8 pr-6 w-full lg:w-1/2" label="電子郵件" />
          <text-input v-model="form.password" :error="form.errors.password" class="pb-8 pr-6 w-full lg:w-1/2" type="password" autocomplete="new-password" label="登入密碼" />
          <text-input v-model="form.mailing_address" :error="form.errors.mailing_address" class="pb-8 pr-6 w-full lg:w-1/2" label="通訊地址" />
          <text-input v-model="form.registered_address" :error="form.errors.registered_address" class="pb-8 pr-6 w-full lg:w-1/2" label="戶籍地址" />
          <select-input v-if="auth.user.owner" v-model="form.owner" :error="form.errors.owner" class="pb-8 pr-6 w-full lg:w-1/2" label="管理者">
            <option :value="true">是</option>
            <option :value="false">否</option>
          </select-input>
          <select-input v-if="auth.user.owner" v-model="form.is_active" :error="form.errors.is_active" class="pb-8 pr-6 w-full lg:w-1/2" label="啟用">
            <option :value="true">是</option>
            <option :value="false">否</option>
          </select-input>
          <file-input v-model="form.photo" :error="form.errors.photo" class="pb-8 pr-6 w-full lg:w-1/2" type="file" accept="image/*" label="照片" />
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button v-if="!user.deleted_at && auth.user.owner" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">刪除成員</button>
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
import FileInput from '@/Shared/FileInput.vue'
import SelectInput from '@/Shared/SelectInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'
import TrashedMessage from '@/Shared/TrashedMessage.vue'

export default {
  components: {
    FileInput,
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
  },
  layout: Layout,
  props: {
    auth: Object, // 從父層傳入auth
    user: Object,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        _method: 'put',
        first_name: this.user.first_name,
        // last_name: this.user.last_name,
        commission_rate: this.user.commission_rate/1, //去除小數點後的0
        phone: this.user.phone,
        mobile: this.user.mobile,
        email: this.user.email,
        password: '',
        mailing_address: this.user.mailing_address,
        registered_address: this.user.registered_address,
        owner: Boolean(this.user.owner),
        is_active: Boolean(this.user.is_active),
        photo: null,
      }),
    }
  },
  methods: {
    update() {
      this.form.post(`/users/${this.user.id}`, {
        onSuccess: () => this.form.reset('password', 'photo'),
      })
    },
    destroy() {
      if (confirm('確定刪除成員資料?')) {
        this.$inertia.delete(`/users/${this.user.id}`)
      }
    },
    restore() {
      if (confirm('確定還原成員資料?')) {
        this.$inertia.put(`/users/${this.user.id}/restore`)
      }
    },
  },
}
</script>
