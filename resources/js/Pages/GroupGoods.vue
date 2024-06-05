<template>
    <div>
      <h1>Filtered Group Goods</h1> 
      <ul>
        <li v-for="good in filteredGoods" :key="good.id">
          {{ good.name }} 
          <!-- Add other good properties as needed -->
        </li>
      </ul>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      filteredGoods: Array,
    },
  };
  </script>