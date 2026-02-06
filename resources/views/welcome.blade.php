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
            <input class="form-control" name="name" placeholder="اسم الخط" required>
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
                                name="name"
                                value="{{ $route->name }}"
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
                <input class="form-control" name="name" placeholder="اسم المحطة" required>
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
                            <input class="form-control" name="name" value="{{ $station->name }}">
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
        🔗 ربط خط بمحطات
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
                        <option value="{{ $route->id }}">{{ $route->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- المحطات -->
            <div class="col-md-6">
                <label class="form-label fw-bold">📍 المحطات</label>

                <div class="border rounded p-3" style="max-height: 220px; overflow-y: auto;">
                    @foreach(\App\Models\Station::all() as $station)
                        <div class="form-check mb-2">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="station_ids[]"
                                value="{{ $station->id }}"
                                id="station_{{ $station->id }}"
                            >
                            <label class="form-check-label" for="station_{{ $station->id }}">
                                {{ $station->name }}
                            </label>
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
<div class="card mb-4">
    
        
    </div>
    <div class="card mb-4">
    <div class="card-header bg-dark text-white">
        📍 المحطات والخطوط المرتبطة
    </div>

    <div class="card-body">

        @foreach(\App\Models\Route::with('stations')->get() as $route)
            <div class="mb-4">

                <h5 class="fw-bold text-success">
                    🛣️ خط {{ $route->name }}
                </h5>

                <!-- جدول المحطات -->
                @if($route->stations->count())
                    <table class="table table-bordered table-sm text-center align-middle">
                        <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>اسم المحطة</th>
                            <th>حذف</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($route->stations as $station)
                            <tr>
                                <td>{{ $station->id }}</td>
                                <td>{{ $station->name }}</td>
                                <td>
                                   <form
    method="POST"
    action="{{ route('admin.route-stations.destroy') }}"
    onsubmit="return confirm('حذف المحطة من الخط؟')"
>
    @csrf
    @method('DELETE')

    <input type="hidden" name="route_id" value="{{ $route->id }}">
    <input type="hidden" name="station_id" value="{{ $station->id }}">

    <button class="btn btn-danger btn-sm">
        حذف
    </button>
</form>

                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-secondary">
                        لا توجد محطات مرتبطة
                    </div>
                @endif

                <!-- إضافة محطة -->
             <form method="POST"
      action="{{ route('admin.route-stations.store') }}"
      class="mt-3">
    @csrf

    <input type="hidden" name="route_id" value="{{ $route->id }}">

    <div class="border rounded p-3"
         style="max-height: 220px; overflow-y:auto">

        @foreach(\App\Models\Station::all() as $station)
            <div class="form-check mb-2">
                <input
                    class="form-check-input"
                    type="checkbox"
                    name="station_ids[]"
                    value="{{ $station->id }}"
                    id="st_{{ $station->id }}"
                >
                <label class="form-check-label" for="st_{{ $station->id }}">
                    {{ $station->name }}
                </label>
            </div>
        @endforeach

    </div>

    <button class="btn btn-success w-100 mt-2">
        إضافة المحطات
    </button>
</form>



                <hr>
            </div>
        @endforeach

    </div>
</div>


 



</div>


</div>

</body>
</html>
