<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
import { exampleStore } from '../stores/examples'

const examples = ref([])
const loading = ref(true)
const error = ref(null)

watch(
    () => exampleStore.updated,
    (updated) => {
        if (updated) {
          loadExamples()
        }
    }
)

const loadExamples = async () => {
    loading.value = true
    error.value = null

    try {
        const response = await fetch('http://localhost:8000/api/examples?page=1')

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`)
        }

        examples.value = (await response.json()).data

    } catch (err) {
        error.value = err.message

    } finally {
        loading.value = false

    }
}

onMounted(() => {
    loadExamples()
})

</script>

<template>
  <div class="ml-1 grid flex-1 text-left text-sm">
    <div class="container p-[5px]">
      <span class="header mb-0.75 truncate leading-tight text-lg font-bold">Example REST API Response Panel</span>

      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Note</th>
            <th>Created</th>
            <th>Updateed</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="example in examples" :key="example.id">
            <td>{{ example.id }}</td>
            <td>{{ example.name }}</td>
            <td>{{ example.note }}</td>
            <td>{{ example.created_at }}</td>
            <td>{{ example.created_at }}</td>
          </tr>
        </tbody>
      </table>
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

.table {
    width: 100%;
    border-collapse: separate;
}

.table th,
.table td {
    border: .25px dashed var(--border);
    padding: var(--standard-pad);
}

.error {
    color: red;
}
</style>