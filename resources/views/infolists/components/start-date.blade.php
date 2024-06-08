<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div>
        {{ jalali($getState())->format("Y/m/d ") }}
    </div>
</x-dynamic-component>
