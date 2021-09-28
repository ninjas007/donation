@extends('layouts.app', ['title' => 'Tambah Campaign - Admin'])

@section('content')
<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-300">
    <div class="container mx-auto px-6 py-8">

        <div class="p-6 bg-white rounded-md shadow-md">
            <h2 class="text-lg text-gray-700 font-semibold capitalize">TAMBAH CAMPAIGN</h2>
            <hr class="mt-4">
                <form action="{{ route('admin.campaign.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 gap-6 mt-4">
                        <div>
                            <label for="image" class="text-gray-700">GAMBAR</label>
                            <input type="file" name="image"
                                class="form-input w-full mt-2 rounded-md bg-gray-200 focus:bg-white p-3">
                            @error('image')
                            <div class="w-full bg-red-200 shadow-sm rounded-md overflow-hidden mt-2">
                                <div class="px-4 py-2">
                                    <p class="text-gray-600 text-sm">{{ $message }}</p>
                                </div>
                            </div>
                            @enderror
                        </div>

                        <div>
                            <label for="name" class="text-gray-700">JUDUL CAMPAIGN</label>
                            <input type="text" name="title" value="{{ old('title') }}" placeholder="Judul Campaign"
                                class="form-input w-full mt-2 rounded-md bg-gray-200 focus:bg-white">
                            @error('title')
                            <div class="w-full bg-red-200 shadow-sm rounded-md overflow-hidden mt-2">
                                <div class="px-4 py-2">
                                    <p class="text-gray-600 text-sm">{{ $message }}</p>
                                </div>
                            </div>
                            @enderror
                        </div>

                        <div>
                            <label for="name" class="text-gray-700">KATEGORI</label>
                            <select name="category_id"
                                class="w-full border bg-gray-200 focus:bg-white rounded px-3 py-2 outline-none">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" class="py-1">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <div class="w-full bg-red-200 shadow-sm rounded-md overflow-hidden mt-2">
                                <div class="px-4 py-2">
                                    <p class="text-gray-600 text-sm">{{ $message }}</p>
                                </div>
                            </div>
                            @enderror
                        </div>

                        <div>
                            <label for="name" class="text-gray-700">DESKRIPSI</label>
                            <textarea name="description" rows="5">{{ old('description') }}</textarea>
                            @error('description')
                            <div class="w-full bg-red-200 shadow-sm rounded-md overflow-hidden mt-2">
                                <div class="px-4 py-2">
                                    <p class="text-gray-600 text-sm">{{ $message }}</p>
                                </div>
                            </div>
                            @enderror
                        </div>

                        <div>
                            <label for="name" class="text-gray-700">TARGET DONASI</label>
                            <input type="number" name="target_donation" value="{{ old('target_donation') }}"
                                placeholder="Target Donasi, Ex: 10000"
                                class="form-input w-full mt-2 rounded-md bg-gray-200 focus:bg-white p-3">
                            @error('target_donation')
                            <div class="w-full bg-red-200 shadow-sm rounded-md overflow-hidden mt-2">
                                <div class="px-4 py-2">
                                    <p class="text-gray-600 text-sm">{{ $message }}</p>
                                </div>
                            </div>
                            @enderror
                        </div>

                        <div>
                            <label for="name" class="text-gray-700">TANGGAL BERAKHIR</label>
                            <input type="date" value="{{ old('max_date') }}" name="max_date"
                                class="form-input w-full mt-2-rounded-md bg-gray-200 focus:bg-white p-3">
                            @error('max_date')
                            <div class="w-full bg-red-200 shadow-sm rounded-md overflow-hidden mt-2">
                                <div class="px-4 py-2">
                                    <p class="text-gray-600 text-sm">{{ $message }}</p>
                                </div>
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-start mt-4">
                        <button type="submit" class="px-4 py-2 bg-gray-600 text-gray-200 rounded-md hover:bg-gray-700 focus:outline-none focus:bg-gray-700">SIMPAN</button>
                    </div>
                </form>            
        </div>

    </div>
</main>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.7.0/tinymce.min.js"></script>
<script>
    tinymce.init({selector:'textarea'});
</script>
@endsection
