<script setup>
import TicketCard from './TicketCard.vue'

const props = defineProps({
  tickets: { type: Array, required: true },
  categories: { type: Array, required: true }
})

defineEmits(['advance'])

function categoryName(id) {
  return props.categories.find(item => item.id === id)?.name ?? 'Tidak dikenal'
}
</script>

<template>
  <p v-if="tickets.length === 0" role="status">Tidak ada tiket sesuai filter.</p>
  <ul v-else class="tickets">
    <li v-for="ticket in tickets" :key="ticket.id">
      <TicketCard 
        :ticket="ticket" 
        :category-name="categoryName(ticket.category_id)"
        @advance="$emit('advance', $event)" 
      />
    </li>
  </ul>
</template>

<style scoped>
.tickets { list-style: none; padding: 0; }
</style>