<template>
  <div class="flex gap-8 flex-wrap">
    <exam-date class="flex-1"/>
    <study-hours class="flex-1"/>
  </div>
  <div class="flex gap-8 flex-wrap mt-8">
    <on-demand-courses class="flex-1"/>
    <books-and-zoom-classes class="flex-1"/>
  </div>
  <test-activities class="mt-8"/>
</template>

<script lang="ts" setup>
import ExamDate from "@/components/ExamDate.vue";
import StudyHours from "@/components/StudyHours.vue";
import TestActivities from "@/components/TestActivities.vue";
import BooksAndZoomClasses from "@/components/BooksAndZoomClasses.vue";
import OnDemandCourses from "@/components/OnDemandCourses.vue";
</script>
<style scoped>
</style>
