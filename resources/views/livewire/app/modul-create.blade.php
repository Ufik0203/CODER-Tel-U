<div>
  {{-- Header Start --}}
  <header class="flex my-7">
    <h2 class="text-2xl font-bold text-white md:text-3xl">Modul Baru</h2>
  </header>
  {{-- Header End --}}

  <form wire:submit.prevent='store' enctype="multipart/form-data">
    @csrf
    {{-- Informasi Modul Start --}}
    <div class="w-full p-6 mb-6 bg-glass rounded-xl">
      <header class="flex items-center justify-between mb-8 text-white">
        <div class="flex items-center">
          <a href="{{ route('app.e-learning.modul') }}" wire:navigate
            class="flex items-center justify-center w-8 h-8 rounded-full hover:border me-2 bg-lightGray">
            <iconify-icon icon="carbon:arrow-left"></iconify-icon>
          </a>
          <h4 class="text-xl font-semibold">Informasi Modul</h4>
        </div>
      </header>
      <div class="justify-between block mb-6 md:flex gap-9">
        <div class="w-full mb-6 md:mb-0">
          <label for="input-label" class="block mb-2 font-light text-white">Nama Modul</label>
          <input type="text" id="input-label" class="block w-full p-3 text-white rounded-lg bg-lightGray"
            placeholder="Nama Modul" wire:model='name'>
          @error('name')
            <small class="text-red-600"> {{ $message }} </small>
          @enderror
        </div>
        <div class="w-full">
          <label for="section" class="block mb-2 font-light text-white">Masukkan Pertemuan</label>
          <input type="number" id="section" class="block w-full p-3 text-white rounded-lg bg-lightGray"
            placeholder="Pertemuan ke-1" wire:model='section'>

          @error('section')
            <small class="text-red-600"> {{ $message }} </small>
          @enderror
        </div>
      </div>
      <div class="w-full">
        <label for="textarea-label" class="block mb-2 font-light text-white">Deskripsi</label>
        <textarea id="textarea-label" class="block w-full p-3 text-white rounded-lg bg-lightGray" rows="5"
          placeholder="Tulis deskripsi disini..." wire:model='description'></textarea>
        @error('description')
          <small class="text-red-600"> {{ $message }} </small>
        @enderror
      </div>
    </div>
    {{-- Informasi Modul End --}}

    {{-- Upload File Start --}}
    <div class="w-full p-6 mb-6 bg-glass rounded-xl">
      <div class="justify-between block mb-6 md:flex gap-9">
        <div class="w-full mb-6 md:mb-0">
          <label for="input-label" class="block mb-2 font-light dark:text-white">Jenis File
            Pendukung</label>
          <select class="block w-full p-3 text-white border-gray-200 rounded-lg pe-9 bg-lightGray"
            wire:model.live='type'>
            <option value="vscode-icons:file-type-powerpoint">Power Point</option>
            <option value="bi:github">Github</option>
            <option value="vscode-icons:file-type-pdf2">PDF</option>
            <option value="vscode-icons:file-type-word">Ms.Word</option>
            <option value="vscode-icons:file-type-excel">Ms.Excel</option>
            <option value="logos:blogger">Blog</option>
            <option value="fxemoji:notepad">Notepad (.txt)</option>
          </select>
          @error('type')
            <small class="text-red-600"> {{ $message }} </small>
          @enderror
        </div>
        @if (
            $type == 'vscode-icons:file-type-powerpoint' ||
                $type == 'vscode-icons:file-type-pdf2' ||
                $type == 'vscode-icons:file-type-word' ||
                $type == 'vscode-icons:file-type-excel' ||
                $type == 'fxemoji:notepad')
          <div class="w-full">
            <label for="input-label" class="block mb-2 font-light dark:text-white">Upload File <span
                class="text-[#9E9E9E] text-xs">(Maksimal 5 Mb)</span></label>
            <input type="file" id="file-input" class="hidden" wire:model.live='file'>
            <label for="file-input"
              class="flex items-center gap-2 p-3 text-white rounded-md cursor-pointer bg-lightGray">
              <span class="text-xs bg-[#43474C] py-1 px-1.5">Pilih File</span>
              <span class="text-xs" id="file-name">Belum ada file yang dipilih.</span>
            </label>
            @error('file')
              <small class="text-red-600"> {{ $message }} </small>
            @enderror
          </div>
        @endif
        @if ($type == 'bi:github' || $type == 'logos:blogger')
          <div class="w-full">
            <label for="url" class="block mb-2 font-light dark:text-white">Link / URL <span
                class="text-[#9E9E9E] text-xs">(Maksimal 5 Mb)</span></label>
            <input type="url" id="url" class="block w-full p-3 text-white rounded-lg bg-lightGray"
              placeholder="https://" wire:model='link'>
            @error('file')
              <small class="text-red-600"> {{ $message }} </small>
            @enderror
          </div>
        @endif
      </div>
    </div>
    {{-- Upload File End --}}

    <div class="flex justify-end mb-6">
      <a href="{{ route('app.e-learning.modul') }}" wire:navigate
        class="inline-block px-5 py-3 text-sm font-semibold text-gray-400 border border-gray-400 rounded-md hover:border-red-700 hover:text-white hover:bg-red-700">Batal</a>
      <button type="submit" wire:loading.remove
        class="flex items-center px-5 py-3 text-sm font-semibold text-black bg-white rounded-md ms-3">Simpan
        Modul
        <iconify-icon icon="material-symbols:save-outline" class="text-2xl ms-2"></iconify-icon>
      </button>

      {{-- Loaading event start --}}
      <div wire:loading wire:target="store"
        class="flex items-center px-20 py-3 text-sm font-semibold text-black bg-white rounded-md ms-3">
        <div
          class="animate-spin inline-block size-4 border-[3px] border-current border-t-transparent text-black rounded-full"
          role="status" aria-label="loading">
          <span class="sr-only">Loading...</span>
        </div>
      </div>
      {{-- Loaading event end --}}
    </div>
  </form>
</div>

<script>
  const fileInput = document.getElementById('file-input');
  const fileName = document.getElementById('file-name');

  fileInput.addEventListener('change', (event) => {
    const files = event.target.files;
    if (files.length > 0) {
      fileName.textContent = files[0].name;
    } else {
      fileName.textContent = 'Belum ada file yang dipilih.';
    }
  });
</script>