<div>
    <div id="circle-cursor" class="hidden"></div>

    <script>
        document.addEventListener('livewire:initialized', function () {
            Livewire.on('test-event', () => {
                console.log('test-event');
            });
        });
    </script>
</div>
