@extends('layouts.admin')

@section('admin-content')
    <div class="clinic-admin-header">
        <div>
            <h1>Акции</h1>
            <p class="clinic-admin-subtitle">
                Управление акциями клиники.
            </p>
        </div>

        <div>
            @if (Route::has('admin.promotions.create'))
                <a href="{{ route('admin.promotions.create') }}" class="clinic-btn">
                    + Добавить акцию
                </a>
            @endif
        </div>
    </div>

    @if (session('success'))
        <div class="clinic-alert clinic-alert--success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="clinic-alert clinic-alert--error">
            {{ session('error') }}
        </div>
    @endif

    @if ($promotions->isEmpty())
        <p class="clinic-empty">
            Акции пока не созданы.
            @if (Route::has('admin.promotions.create'))
                <a href="{{ route('admin.promotions.create') }}" class="clinic-link">Создать первую акцию</a>.
            @endif
        </p>
    @else
        <div class="clinic-table-wrapper">
            <table class="clinic-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Заголовок</th>
                        <th>Период действия</th>
                        <th>Статус</th>
                        <th style="text-align:right;">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($promotions as $promotion)
                        <tr>
                            <td>{{ $promotion->id }}</td>
                            <td>
                                <strong>{{ $promotion->title }}</strong>
                                @if($promotion->short_description)
                                    <div class="clinic-text-muted" style="font-size:0.8rem;">
                                        {{ \Illuminate\Support\Str::limit($promotion->short_description, 80) }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($promotion->starts_at || $promotion->ends_at)
                                    {{ $promotion->starts_at?->format('d.m.Y') ?? '—' }}
                                    &mdash;
                                    {{ $promotion->ends_at?->format('d.m.Y') ?? '—' }}
                                @else
                                    Без ограничений
                                @endif
                            </td>
                            <td>
                                @if($promotion->is_active)
                                    <span class="clinic-badge clinic-badge--success">Активна</span>
                                @else
                                    <span class="clinic-badge clinic-badge--muted">Выключена</span>
                                @endif
                            </td>
                            <td style="text-align:right;">
                                <div class="clinic-table-actions">
                                    @if (Route::has('admin.promotions.edit'))
                                        <a href="{{ route('admin.promotions.edit', $promotion) }}"
                                           class="clinic-link">
                                            Редактировать
                                        </a>
                                    @endif

                                    @if (Route::has('admin.promotions.destroy'))
                                        <form action="{{ route('admin.promotions.destroy', $promotion) }}"
                                              method="POST"
                                              class="clinic-inline-form"
                                              onsubmit="return confirm('Удалить эту акцию? Действие необратимо.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="clinic-link clinic-link--danger"
                                                    style="background:none;border:none;padding:0;cursor:pointer;">
                                                Удалить
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection