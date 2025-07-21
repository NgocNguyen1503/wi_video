<template>
    <ContentComponent />
    <ChatComponent />

</template>

<script>
import ContentComponent from './components/ContentComponent.vue';
import ChatComponent from './components/ChatComponent.vue';

export default {
    components: {
        ContentComponent,
        ChatComponent
    }
}
</script>