<!-- Table Component -->
<div class="overflow-x-auto">
    <table class="w-full">
        <thead>
            <tr class="border-b border-slate-700">
                {{ $header }}
            </tr>
        </thead>
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>
