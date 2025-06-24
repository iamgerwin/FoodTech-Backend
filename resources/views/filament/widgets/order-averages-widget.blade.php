<x-filament::widget>
    <div class="p-6">
        <h2 class="text-xl font-bold mb-4">Order Averages</h2>
        <div class="flex flex-col gap-2">
            <div>
                <span class="font-semibold">Average Delivery Distance:</span>
                <span>{{ $this->averageDistance }} km</span>
            </div>
            <div>
                <span class="font-semibold">Average Completion Time:</span>
                <span>{{ $this->averageTime }} minutes</span>
            </div>
        </div>
    </div>
</x-filament::widget>
