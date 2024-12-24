<div>
  <form wire:submit='save' class="mb-10" enctype="multipart/form-data">
    <div class="block gap-4 md:flex">
      <div class="w-full mb-5 lg:w-1/3 md:mb-0">
        <div class="text-white border-transparent bg-glass rounded-xl p-7">
          <div class="flex items-center justify-center w-full h-40 mx-auto rounded-lg bg-lightGray mb-7">
            @if ($image)
            <img class="h-full" src="{{ $image->temporaryUrl() }}" alt="Image">
        @else
            <iconify-icon icon="tdesign:camera" class="text-4xl text-white"></iconify-icon>
        @endif
          </div>
          <div class="flex flex-col items-center justify-center w-full h-20 p-4 mx-auto rounded-lg bg-lightGray">
            <label class="block">
              <span class="sr-only">Choose gallery photo</span>
              <input type="file"
                class="block w-full text-sm text-gray-500
                    file:me-4 file:py-2 file:px-4
                    file:rounded-lg file:border-0
                    file:text-sm file:font-semibold
                    file:bg-[#4D5157] file:text-white
                    hover:file:bg-[#3A3D41]
                    file:disabled:opacity-50 file:disabled:pointer-events-none
                    file:cursor-pointer
                  "
                accept="image/png, image/jpeg" wire:model='image'>
            </label>
          </div>
          @error('image')<small class="text-red-600"> {{ $message }} </small>@enderror
        </div>
      </div>
      <div class="w-full mb-5 lg:w-2/3 md:mb-0">
        <div class="text-white border-transparent bg-glass rounded-xl p-7">
          <div class="division-desc">
            <h2 class="mb-3 text-xl font-semibold text-white">Caption</h2>
            <div>
                <textarea wire:model='caption' class="block w-full px-4 py-3 text-sm bg-transparent border-2 border-gray-600 rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none"                rows="3" placeholder="This is a textarea placeholder"></textarea>
                @error('caption')<small class="text-red-600"> {{ $message }} </small>@enderror
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="items-center justify-end block space-x-4 md:flex">

      <a href="{{ route('app.content.gallery') }}" wire:navigate
        class="flex items-center px-5 py-3 text-sm font-semibold text-[#9E9E9E] bg-transparent rounded-md border border-[#9E9E9E] group hover:bg-white hover:text-black transition-colors"
        type="submit">Batal</a>
      <button type="submit" class="flex items-center px-5 py-3 text-sm font-semibold text-black bg-white rounded-md"
        type="submit">Tambah gallery <iconify-icon icon="mingcute:arrow-right-line"
          class="text-xl ms-2"></iconify-icon></button>
    </div>
  </form>
</div>