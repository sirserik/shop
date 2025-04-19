@extends('layouts.admin')

@section('content')
    <div class="wg-box mb-12">
        {{-- Заголовок и кнопка "Add new" --}}
        <div class="flex items-center flex-wrap justify-between gap-4 mb-4">
            <div class="flex items-center gap-4">
                <h3 class="text-xl font-semibold">Brands</h3>
                <a class="tf-button style-1" href="{{ route('admin.brands.create') }}">
                    <i class="icon-plus"></i>Add new
                </a>
            </div>

            {{-- Хлебные крошки --}}
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap-2 text-sm text-gray-500">
                <li>
                    <a href="{{ route('admin.dashboard') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Brands</div>
                </li>
            </ul>
        </div>
    </div>

    <div class="wg-box mb-12">
        {{-- Фильтры и поиск --}}
        <div class="mb-6">
            <form method="GET" action="{{ route('admin.brands.index') }}" class="form-search flex gap-4">
                <input type="text" name="search" placeholder="Search name or slug..." value="{{ request('search') }}" />

                <select name="status">
                    <option value="">All statuses</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>

                <select name="sort">
                    <option value="">Sort by</option>
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
                    <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Oldest</option>
                    <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Newest</option>
                </select>

                <button type="submit" class="tf-button">Filter</button>
            </form>
        </div>
    </div>


    {{-- Таблица брендов --}}
    <div class="wg-box">
        <div class="wg-table table-all-user">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Products</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($brands as $brand)
                        <tr>
                            <td>{{ $loop->iteration + ($brands->currentPage() - 1) * $brands->perPage() }}</td>
                            <td class="pname">
                                <div class="image">
                                    @if($brand->image_url)
                                        <img src="{{ $brand->image_url }}" alt="{{ $brand->name }}" class="image">
                                    @endif
                                </div>
                                <div class="name">
                                    <a href="#" class="body-title-2">{{ $brand->name }}</a>
                                </div>
                            </td>
                            <td>{{ $brand->slug }}</td>
                            <td><a href="#" target="_blank">—</a></td> <!-- Кол-во продуктов -->
                            <td>
                                <div class="list-icon-function">
                                    <a href="{{ route('admin.brands.edit', $brand) }}">
                                        <div class="item edit">
                                            <i class="icon-edit-3"></i>
                                        </div>
                                    </a>
                                    <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="item text-danger delete" type="submit">
                                            <i class="icon-trash-2"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No brands found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="divider"></div>

            {{-- Пагинация --}}
            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                {{ $brands->appends(['search' => request('search'), 'status' => request('status'), 'sort' => request('sort')])->links() }}
            </div>
        </div>
    </div>
@endsection
