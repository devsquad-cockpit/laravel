@props(['line' => null, 'code' => null])

<div class="flex">
    <div class="bg-[#393D3F] w-14 flex items-center justify-center p-2">
        {{ $line }}
    </div>
    <div class="w-full bg-[#27292B] p-2">
        <code>
            Route::post('/task', function (Request $request) {
        </code>
    </div>
</div>
