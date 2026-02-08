<!DOCTYPE html>
<html lang="ar">
<head>
    
    <meta charset="UTF-8">
    <title>Admin Test Panel</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-4">

    <h1 class="mb-4 text-center">لوحة تجريبية للإدارة</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- ================= USERS ================= -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">👤 المستخدمون</div>
        <div class="card-body table-responsive">

            <table class="table table-bordered align-middle text-center">
                <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>تعديل</th>
                    <th>حذف</th>
                </tr>
                </thead>
                <tbody>
                @foreach(\App\Models\User::all() as $user)
                    <tr>
                        <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                            @csrf
                            @method('PUT')

                            <td>{{ $user->id }}</td>
                            <td><input class="form-control" name="username" value="{{ $user->username }}"></td>

                            <td>
                                <select class="form-select" name="role_id">
                                    @foreach(\App\Models\Role::whereIn('name',['student','driver','admin'])->get() as $role)
                                        <option value="{{ $role->id }}" @selected($user->role_id==$role->id)>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>

                            <td>
                                <select class="form-select" name="status">
                                    <option value="pending" @selected($user->status=='pending')>pending</option>
                                    <option value="approved" @selected($user->status=='approved')>approved</option>
                                    <option value="rejected" @selected($user->status=='rejected')>rejected</option>
                                </select>
                            </td>

                            <td><button class="btn btn-sm btn-warning">تعديل</button></td>
                        </form>

                        <td>
                            <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"
                                        onclick="return confirm('متأكد من الحذف؟')">حذف</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <hr>

            <h5>➕ إضافة مستخدم</h5>
            <form class="row g-2" method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="col-md-3">
                    <input class="form-control" name="username" placeholder="Username" required>
                </div>
                <div class="col-md-3">
                    <input class="form-control" type="password" name="password" placeholder="Password" required>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="role_id">
                        @foreach(\App\Models\Role::whereIn('name',['student','driver','admin'])->get() as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="status">
                        <option value="approved">approved</option>
                        <option value="pending">pending</option>
                        <option value="rejected">rejected</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-success w-100">إضافة</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= ROUTES ================= -->
   <!-- ================= ROUTES ================= -->
<div class="card mb-4">
    <div class="card-header bg-success text-white">🛣️ الخطوط</div>
    <div class="card-body">

        <!-- إضافة خط -->
        <form class="d-flex gap-2 mb-3" method="POST" action="{{ route('admin.routes.store') }}">
            @csrf
            <input class="form-control" name="route_name" placeholder="اسم الخط" required>
            <button class="btn btn-success">إضافة</button>
        </form>

        <!-- جدول الخطوط -->
        <table class="table table-bordered text-center align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>اسم الخط</th>
                    <th>تعديل</th>
                    <th>حذف</th>
                </tr>
            </thead>
            <tbody>
            @foreach(\App\Models\Route::all() as $route)
                <tr>
                    <!-- فورم التعديل -->
                    <form method="POST" action="{{ route('admin.routes.update', $route->id) }}">
                        @csrf
                        @method('PUT')

                        <td>{{ $route->id }}</td>
                        <td>
                            <input
                                class="form-control"
                                name="route_name"
                                value="{{ $route->route_name }}"
                            >
                        </td>

                        <!-- زر التعديل -->
                        <td>
                            <button type="submit" class="btn btn-warning btn-sm w-100">
                                تعديل
                            </button>
                        </td>
                    </form>

                    <!-- زر الحذف -->
                    <td>
                        <form method="POST" action="{{ route('admin.routes.destroy', $route->id) }}">
                            @csrf
                            @method('DELETE')
                            <button
                                type="submit"
                                class="btn btn-danger btn-sm w-100"
                                onclick="return confirm('متأكد من الحذف؟')"
                            >
                                حذف
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
</div>

    <!-- ================= STATIONS ================= -->
    <div class="card mb-4">
    <div class="card-header bg-info text-white">📍 المحطات</div>
    <div class="card-body">

        <!-- إضافة محطة -->
        <form class="row g-2 mb-3" method="POST" action="{{ route('admin.stations.store') }}">
            @csrf
            <div class="col">
                <input class="form-control" name="station_name" placeholder="اسم المحطة" required>
            </div>
            <div class="col">
                <input class="form-control" name="location_x" placeholder="X" required>
            </div>
            <div class="col">
                <input class="form-control" name="location_y" placeholder="Y" required>
            </div>
            <div class="col">
                <input class="form-control" name="description" placeholder="الوصف">
            </div>
            <div class="col">
                <button class="btn btn-success w-100">إضافة</button>
            </div>
        </form>

        <!-- جدول المحطات -->
        <table class="table table-bordered text-center align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>اسم المحطة</th>
                    <th>X</th>
                    <th>Y</th>
                    <th>الوصف</th>
                    <th>تعديل</th>
                    <th>حذف</th>
                </tr>
            </thead>
            <tbody>
            @foreach(\App\Models\Station::all() as $station)
                <tr>
                    <!-- فورم التعديل -->
                    <form method="POST" action="{{ route('admin.stations.update', $station->id) }}">
                        @csrf
                        @method('PUT')

                        <td>{{ $station->id }}</td>
                        <td>
                            <input class="form-control" name="station_name" value="{{ $station->station_name }}">
                        </td>
                        <td>
                            <input class="form-control" name="location_x" value="{{ $station->location_x }}">
                        </td>
                        <td>
                            <input class="form-control" name="location_y" value="{{ $station->location_y }}">
                        </td>
                        <td>
                            <input class="form-control" name="description" value="{{ $station->description }}">
                        </td>

                        <!-- زر التعديل -->
                        <td>
                            <button type="submit" class="btn btn-warning btn-sm w-100">
                                تعديل
                            </button>
                        </td>
                    </form>

                    <!-- زر الحذف -->
                    <td>
                        <form method="POST" action="{{ route('admin.stations.destroy', $station->id) }}">
                            @csrf
                            @method('DELETE')
                            <button
                                type="submit"
                                class="btn btn-danger btn-sm w-100"
                                onclick="return confirm('متأكد من الحذف؟')"
                            >
                                حذف
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
</div>


    <!-- ================= ROUTE STATIONS ================= -->
  <div class="card mb-4">
    <div class="card-header bg-secondary text-white">
        🔗 ربط خط بمحطات (بالترتيب)
    </div>

    <div class="card-body">
        <form class="row g-3" method="POST" action="{{ route('admin.route-stations.store') }}">
            @csrf

            <!-- الخط -->
            <div class="col-md-4">
                <label class="form-label fw-bold">🛣️ الخط</label>
                <select class="form-select" name="route_id" required>
                    <option value="">-- اختر خط --</option>
                    @foreach(\App\Models\Route::all() as $route)
                        <option value="{{ $route->id }}">{{ $route->route_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- المحطات -->
            <div class="col-md-6">
                <label class="form-label fw-bold">📍 المحطات + الترتيب</label>

                <div class="border rounded p-3" style="max-height: 260px; overflow-y: auto;">
                    @foreach(\App\Models\Station::all() as $index => $station)
                        <div class="d-flex align-items-center mb-2 gap-2">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="stations[{{ $index }}][station_id]"
                                value="{{ $station->id }}"
                                id="station_{{ $station->id }}"
                            >

                            <label class="form-check-label flex-grow-1" for="station_{{ $station->id }}">
                                {{ $station->station_name }}
                            </label>

                            <input
                                type="number"
                                class="form-control form-control-sm"
                                name="stations[{{ $index }}][order]"
                                placeholder="الترتيب"
                                style="width: 90px;"
                                min="1"
                            >
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- زر -->
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-success w-100">
                    ربط
                </button>
            </div>
        </form>
    </div>
</div>

</div>

</div>

<!-- ================= ROUTE ↔ STATIONS MANAGE ================= -->
<div class="card mb-4">
    <div class="card-header bg-dark text-white">
        📋 إدارة محطات الخطوط
    </div>

    <div class="card-body">

        @foreach(\App\Models\Route::with('stations')->get() as $route)
            <div class="border rounded mb-4">

                <div class="bg-light px-3 py-2 fw-bold">
                    🛣️ {{ $route->route_name }}
                </div>

                <!-- إضافة محطة جديدة -->
                <div class="p-3 border-bottom">
                 <!-- إضافة أكثر من محطة لنفس الخط -->
<!-- إضافة محطات غير مرتبطة فقط -->
<form method="POST" action="{{ route('admin.route-stations.store') }}">
    @csrf

    <input type="hidden" name="route_id" value="{{ $route->id }}">

    @php
        $linkedStationIds = $route->stations->pluck('id')->toArray();
        $availableStations = \App\Models\Station::whereNotIn('id', $linkedStationIds)->get();
    @endphp

    @if($availableStations->count())

        <div class="border rounded p-3 mb-3" style="max-height:260px; overflow-y:auto;">

            @foreach($availableStations as $index => $station)
                <div class="d-flex align-items-center gap-2 mb-2">

                    <!-- اختيار المحطة -->
                    <input
                        type="checkbox"
                        class="form-check-input"
                        name="stations[{{ $index }}][station_id]"
                        value="{{ $station->id }}"
                    >

                    <span class="flex-grow-1">
                        {{ $station->station_name }}
                    </span>

                    <!-- الترتيب -->
                    <input
                        type="number"
                        name="stations[{{ $index }}][order]"
                        class="form-control form-control-sm"
                        placeholder="الترتيب"
                        style="width:90px"
                        min="1"
                    >
                </div>
            @endforeach

        </div>

        <button class="btn btn-success w-100">
            إضافة المحطات المختارة
        </button>

    @else
        <div class="alert alert-info text-center">
            ✅ جميع المحطات مرتبطة بهذا الخط
        </div>
    @endif
</form>

                </div>

                <!-- جدول المحطات -->
                @if($route->stations->count())
                    <table class="table table-bordered text-center align-middle mb-0">
                        <thead class="table-secondary">
                            <tr>
                                <th>#</th>
                                <th>اسم المحطة</th>
                                <th>الترتيب</th>
                                <th>حذف</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($route->stations as $station)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $station->station_name }}</td>

                                <!-- تعديل الترتيب -->
                                <td>
                                    <form method="POST" action="{{ route('admin.route-stations.order') }}"
                                          class="d-flex gap-2 justify-content-center">
                                        @csrf
                                        @method('PUT')

                                        <input type="hidden" name="route_id" value="{{ $route->id }}">
                                        <input type="hidden" name="station_id" value="{{ $station->id }}">

                                        <input
                                            type="number"
                                            name="order"
                                            value="{{ $station->pivot->order }}"
                                            class="form-control form-control-sm text-center"
                                            style="width:80px"
                                            min="1"
                                        >

                                        <button class="btn btn-sm btn-primary">
                                            حفظ
                                        </button>
                                    </form>
                                </td>

                                <!-- حذف -->
                                <td>
                                    <form method="POST"
                                          action="{{ route('admin.route-stations.destroy') }}"
                                          onsubmit="return confirm('متأكد من الحذف؟')">
                                        @csrf
                                        @method('DELETE')

                                        <input type="hidden" name="route_id" value="{{ $route->id }}">
                                        <input type="hidden" name="station_id" value="{{ $station->id }}">

                                        <button class="btn btn-sm btn-danger">
                                            حذف
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="p-3 text-center text-muted">
                        لا توجد محطات مرتبطة بهذا الخط
                    </div>
                @endif

            </div>
        @endforeach

    </div>
</div>



</body>
</html>
