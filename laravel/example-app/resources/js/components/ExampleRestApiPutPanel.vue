<script setup>
import { reactive, ref } from 'vue'
import { exampleStore } from '../stores/examples'

const loading = ref(false)
const message = ref('')
const errors = ref({})

const form = reactive({
    id: -1,
    name: '',
    note: '',
})

async function updateExample() {
    loading.value = true
    message.value = ''

    try {
        exampleStore.updated = false

        const response = await fetch(
            `http://localhost:8000/api/examples/${form.id}`,
            {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                },
                body: JSON.stringify(form),
            }
        )

        const data = await response.json()

        if (!response.ok) {
          message.value = data.message
          throw new Error(data.message || 'Request failed')
        }

        exampleStore.updated = true
        message.value = data.message || 'Example updated successfully.'

    } catch (err) {
        console.error(err)

    } finally {
        loading.value = false
    }
}
</script>

<template>
  <div class="card">
    <span class="header mb-0.75 truncate leading-tight text-lg font-bold">Edit Example</span>

    <div class="container p-[15px]">
      <div class="mb-3">
        <label>ID:</label>
        <input
          v-model="form.id"
          type="text"
          class="form-control"
        />
      </div>

      <div class="mb-3">
        <label>Name:</label>
        <input
          v-model="form.name"
          type="text"
          class="form-control"
          placeholder="Please supply a name..."
        />
      </div>

      <div class="mb-3">
        <label>Note:</label>
        <input
          v-model="form.note"
          type="text"
          class="form-control"
          placeholder="Please supply a note..."
        />
      </div>

      <button
        class="btn btn-primary"
        @click="updateExample"
        :disabled="loading"
      >
        {{ loading ? 'Updating...' : 'Update Example' }}
      </button>

      <div v-if="message" class="alert alert-success mt-3">
        {{ message }}
      </div>
    </div>
  </div>
</template>

<style scoped>
.header {
    padding-bottom: var(--standard-pad);
    margin-bottom: var(--standard-pad);
}

.container {
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
}

input[type="text"] {
    border: 1px solid var(--border);
    border-radius: 15px;
    padding: 2px;
    margin: 2px;
    margin-left: 15px;
    padding-right: 15px;
    padding-left: 15px;
}

button {
    border: 2px solid black;
    padding: 5px;
    margin: 2px;
    border-bottom: 5px solid gray;
    position: relative;
    left: 15px;
}
button:hover {
    opacity: .2;
    transition: all .9s;
}
</style>